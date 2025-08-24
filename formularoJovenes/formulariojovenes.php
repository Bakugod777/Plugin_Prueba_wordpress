<?php
/**
 * Plugin Name: Formulario de Jóvenes
 * Description: Formulario de prueba 
 * Version: 1.0.0
 * Author: Manuel Ardila
 */

// Prevenir acceso directo
if (!defined('ABSPATH')) {
    exit;
}

// Definir constantes
define('FORMULARIO_JOVENES_PATH', plugin_dir_path(__FILE__));
define('FORMULARIO_JOVENES_URL', plugin_dir_url(__FILE__));

class FormularioJovenes {
    
    public function __construct() {
        add_action('init', array($this, 'init'));
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    public function init() {
        // Hooks para frontend
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_shortcode('formulario_jovenes', array($this, 'render_formulario'));
        add_shortcode('tabla_jovenes', array($this, 'render_tabla'));
        
        // Hooks para AJAX
        add_action('wp_ajax_get_departments', array($this, 'ajax_get_departments'));
        add_action('wp_ajax_nopriv_get_departments', array($this, 'ajax_get_departments'));
        add_action('wp_ajax_save_joven', array($this, 'ajax_save_joven'));
        add_action('wp_ajax_nopriv_save_joven', array($this, 'ajax_save_joven'));
        add_action('wp_ajax_delete_joven', array($this, 'ajax_delete_joven'));
        add_action('wp_ajax_export_data', array($this, 'ajax_export_data'));
        add_action('wp_ajax_nopriv_export_data', array($this, 'ajax_export_data'));
        
        // Hook para admin
        add_action('admin_menu', array($this, 'add_admin_menu'));
        
        // Hook para manejo de archivos
        add_action('wp_ajax_upload_foto', array($this, 'ajax_upload_foto'));
        add_action('wp_ajax_nopriv_upload_foto', array($this, 'ajax_upload_foto'));
    }
    
    public function activate() {
        $this->create_tables();
        $this->insert_sample_data();
    }
    
    public function deactivate() {
        // Cleanup si es necesario
    }
    
    public function enqueue_scripts() {
        // Bootstrap CSS
        wp_enqueue_style('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css');
        
        // Bootstrap JS
        wp_enqueue_script('bootstrap', 'https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js', array('jquery'));
        
        // Chart.js
        wp_enqueue_script('chartjs', 'https://cdn.jsdelivr.net/npm/chart.js');
        
        // Estilos personalizados
        wp_enqueue_style('formulario-jovenes', FORMULARIO_JOVENES_URL . 'assets/style.css');
        
        // Scripts personalizados
        wp_enqueue_script('formulario-jovenes', FORMULARIO_JOVENES_URL . 'assets/script.js', array('jquery'), '1.0', true);
        
        // Localizar script para AJAX
        wp_localize_script('formulario-jovenes', 'ajax_object', array(
            'ajax_url' => admin_url('admin-ajax.php'),
            'nonce' => wp_create_nonce('formulario_jovenes_nonce')
        ));
    }
    
    private function create_tables() {
        global $wpdb;
        
        $charset_collate = $wpdb->get_charset_collate();
        
        // Tabla países
        $table_countries = $wpdb->prefix . 'fj_countries';
        $sql_countries = "CREATE TABLE $table_countries (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            code varchar(5) NOT NULL,
            name varchar(100) NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        // Tabla departamentos
        $table_departments = $wpdb->prefix . 'fj_departments';
        $sql_departments = "CREATE TABLE $table_departments (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            country_id mediumint(9) NOT NULL,
            name varchar(100) NOT NULL,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        // Tabla jóvenes
        $table_jovenes = $wpdb->prefix . 'fj_jovenes';
        $sql_jovenes = "CREATE TABLE $table_jovenes (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            nombres varchar(100) NOT NULL,
            apellidos varchar(100) NOT NULL,
            direccion text,
            pais_id mediumint(9) NOT NULL,
            departamento_id mediumint(9),
            foto varchar(255),
            telefono varchar(30),
            estado_civil enum('Soltero','Casado','Union Libre','Otro') DEFAULT 'Soltero',
            edad tinyint(3) NOT NULL,
            descripcion text,
            acepta_terminos tinyint(1) NOT NULL DEFAULT 0,
            creado_at datetime DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (id)
        ) $charset_collate;";
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql_countries);
        dbDelta($sql_departments);
        dbDelta($sql_jovenes);
    }
    
    private function insert_sample_data() {
        global $wpdb;
        
        $table_countries = $wpdb->prefix . 'fj_countries';
        $table_departments = $wpdb->prefix . 'fj_departments';
        
        // Insertar países
        $countries_data = array(
            array('code' => 'CO', 'name' => 'Colombia'),
            array('code' => 'MX', 'name' => 'México'),
            array('code' => 'AR', 'name' => 'Argentina'),
            array('code' => 'PE', 'name' => 'Perú'),
            array('code' => 'CL', 'name' => 'Chile')
        );
        
        foreach($countries_data as $country) {
            $exists = $wpdb->get_var($wpdb->prepare(
                "SELECT COUNT(*) FROM $table_countries WHERE code = %s",
                $country['code']
            ));
            
            if (!$exists) {
                $wpdb->insert($table_countries, $country);
            }
        }
        
        // Insertar departamentos para Colombia
        $co_id = $wpdb->get_var($wpdb->prepare(
            "SELECT id FROM $table_countries WHERE code = %s",
            'CO'
        ));
        
        if ($co_id) {
            $departments_co = array(
                'Bogotá D.C.', 'Antioquia', 'Valle del Cauca', 'Atlántico', 'Santander',
                'Bolívar', 'Cundinamarca', 'Norte de Santander', 'Córdoba', 'Tolima'
            );
            
            foreach($departments_co as $dept) {
                $exists = $wpdb->get_var($wpdb->prepare(
                    "SELECT COUNT(*) FROM $table_departments WHERE country_id = %d AND name = %s",
                    $co_id, $dept
                ));
                
                if (!$exists) {
                    $wpdb->insert($table_departments, array(
                        'country_id' => $co_id,
                        'name' => $dept
                    ));
                }
            }
        }
    }
    
    public function render_formulario($atts) {
        ob_start();
        include FORMULARIO_JOVENES_PATH . 'templates/formulario.php';
        return ob_get_clean();
    }
    
    public function render_tabla($atts) {
        ob_start();
        include FORMULARIO_JOVENES_PATH . 'templates/tabla.php';
        return ob_get_clean();
    }
    
    public function ajax_get_departments() {
        check_ajax_referer('formulario_jovenes_nonce', 'nonce');
        
        global $wpdb;
        $country_id = intval($_POST['country_id']);
        
        $table_departments = $wpdb->prefix . 'fj_departments';
        $departments = $wpdb->get_results($wpdb->prepare(
            "SELECT id, name FROM $table_departments WHERE country_id = %d ORDER BY name",
            $country_id
        ));
        
        wp_send_json_success($departments);
    }
    
    public function ajax_save_joven() {
        check_ajax_referer('formulario_jovenes_nonce', 'nonce');
        
        global $wpdb;
        $table_jovenes = $wpdb->prefix . 'fj_jovenes';
        
        $data = array(
            'nombres' => sanitize_text_field($_POST['nombres']),
            'apellidos' => sanitize_text_field($_POST['apellidos']),
            'direccion' => sanitize_textarea_field($_POST['direccion']),
            'pais_id' => intval($_POST['pais_id']),
            'departamento_id' => intval($_POST['departamento_id']),
            'foto' => sanitize_text_field($_POST['foto']),
            'telefono' => sanitize_text_field($_POST['telefono']),
            'estado_civil' => sanitize_text_field($_POST['estado_civil']),
            'edad' => intval($_POST['edad']),
            'descripcion' => sanitize_textarea_field($_POST['descripcion']),
            'acepta_terminos' => intval($_POST['acepta_terminos'])
        );
        
        $result = $wpdb->insert($table_jovenes, $data);
        
        if ($result !== false) {
            wp_send_json_success(array('message' => 'Registro guardado exitosamente'));
        } else {
            wp_send_json_error(array('message' => 'Error al guardar el registro'));
        }
    }
    
    public function ajax_upload_foto() {
        check_ajax_referer('formulario_jovenes_nonce', 'nonce');
        
        if (!empty($_FILES['foto']['name'])) {
            $uploaded = wp_handle_upload($_FILES['foto'], array('test_form' => false));
            
            if (!isset($uploaded['error'])) {
                wp_send_json_success(array('url' => $uploaded['url']));
            } else {
                wp_send_json_error(array('message' => $uploaded['error']));
            }
        }
        
        wp_send_json_error(array('message' => 'No se seleccionó archivo'));
    }
    
    public function ajax_delete_joven() {
        check_ajax_referer('formulario_jovenes_nonce', 'nonce');
        
        global $wpdb;
        $table_jovenes = $wpdb->prefix . 'fj_jovenes';
        $id = intval($_POST['id']);
        
        $result = $wpdb->delete($table_jovenes, array('id' => $id));
        
        if ($result !== false) {
            wp_send_json_success(array('message' => 'Registro eliminado'));
        } else {
            wp_send_json_error(array('message' => 'Error al eliminar'));
        }
    }
    
    public function ajax_get_joven() {
        check_ajax_referer('formulario_jovenes_nonce', 'nonce');
        
        global $wpdb;
        $id = intval($_POST['id']);
        
        $joven = $wpdb->get_row($wpdb->prepare("
            SELECT j.*, c.name as pais_name, d.name as departamento_name 
            FROM {$wpdb->prefix}fj_jovenes j
            LEFT JOIN {$wpdb->prefix}fj_countries c ON j.pais_id = c.id
            LEFT JOIN {$wpdb->prefix}fj_departments d ON j.departamento_id = d.id
            WHERE j.id = %d
        ", $id));
        
        if ($joven) {
            wp_send_json_success($joven);
        } else {
            wp_send_json_error(array('message' => 'Registro no encontrado'));
        }
    }
    
    public function add_admin_menu() {
        add_menu_page(
            'Formulario Jóvenes',
            'Formulario Jóvenes',
            'manage_options',
            'formulario-jovenes',
            array($this, 'admin_page'),
            'dashicons-groups',
            30
        );
    }
    
    public function ajax_export_data() {
        check_ajax_referer('formulario_jovenes_nonce', 'nonce');
        
        global $wpdb;
        
        // Obtener todos los datos
        $jovenes = $wpdb->get_results("
            SELECT j.nombres, j.apellidos, j.direccion, c.name as pais, 
                   d.name as departamento, j.telefono, j.estado_civil, 
                   j.edad, j.descripcion, j.creado_at
            FROM {$wpdb->prefix}fj_jovenes j
            LEFT JOIN {$wpdb->prefix}fj_countries c ON j.pais_id = c.id
            LEFT JOIN {$wpdb->prefix}fj_departments d ON j.departamento_id = d.id
            ORDER BY j.creado_at DESC
        ");
        
        // Crear archivo CSV
        $filename = 'registros_jovenes_' . date('Y-m-d_H-i-s') . '.csv';
        
        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Pragma: no-cache');
        header('Expires: 0');
        
        $output = fopen('php://output', 'w');
        
        // BOM para UTF-8
        fprintf($output, chr(0xEF).chr(0xBB).chr(0xBF));
        
        // Encabezados
        fputcsv($output, array(
            'Nombres',
            'Apellidos', 
            'Dirección',
            'País',
            'Departamento',
            'Teléfono',
            'Estado Civil',
            'Edad',
            'Descripción',
            'Fecha Registro'
        ));
        
        // Datos
        foreach($jovenes as $joven) {
            fputcsv($output, array(
                $joven->nombres,
                $joven->apellidos,
                $joven->direccion,
                $joven->pais,
                $joven->departamento,
                $joven->telefono,
                $joven->estado_civil,
                $joven->edad,
                $joven->descripcion,
                $joven->creado_at
            ));
        }
        
        fclose($output);
        exit;
    }
}

// Inicializar plugin
new FormularioJovenes();