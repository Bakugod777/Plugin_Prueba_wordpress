<?php
if (!defined('ABSPATH')) {
    exit;
}

global $wpdb;

// Obtener estadísticas
$total_registros = $wpdb->get_var("SELECT COUNT(*) FROM {$wpdb->prefix}fj_jovenes");
$promedio_edad = $wpdb->get_var("SELECT AVG(edad) FROM {$wpdb->prefix}fj_jovenes");
$registro_reciente = $wpdb->get_row("SELECT * FROM {$wpdb->prefix}fj_jovenes ORDER BY creado_at DESC LIMIT 1");

// Datos para gráfica
$stats_edad = $wpdb->get_results("
    SELECT edad, COUNT(*) as cantidad 
    FROM {$wpdb->prefix}fj_jovenes 
    GROUP BY edad 
    ORDER BY edad
");
?>

<div class="wrap">
    <h1 class="wp-heading-inline">
        <span class="dashicons dashicons-groups" style="font-size: 30px; vertical-align: middle;"></span>
        Formulario de Jóvenes
    </h1>
    
    <hr class="wp-header-end">
    
    <div class="dashboard-widgets-wrap">
        <div class="metabox-holder">
            <div class="postbox-container" style="width: 100%;">
                <div class="meta-box-sortables">
                    
                    <!-- Estadísticas Generales -->
                    <div class="postbox">
                        <div class="postbox-header">
                            <h2 class="hndle ui-sortable-handle">
                                <span>📊 Estadísticas Generales</span>
                            </h2>
                        </div>
                        <div class="inside">
                            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px;">
                                <div class="dashboard-stat-card" style="background: #f0f6ff; padding: 20px; border-radius: 8px; border-left: 4px solid #0073aa;">
                                    <h3 style="margin: 0; color: #0073aa; font-size: 2em;"><?php echo $total_registros; ?></h3>
                                    <p style="margin: 5px 0 0 0; color: #666;">Total de Registros</p>
                                </div>
                                
                                <div class="dashboard-stat-card" style="background: #f0fff4; padding: 20px; border-radius: 8px; border-left: 4px solid #00a32a;">
                                    <h3 style="margin: 0; color: #00a32a; font-size: 2em;">
                                        <?php echo $promedio_edad ? round($promedio_edad, 1) : '0'; ?>
                                    </h3>
                                    <p style="margin: 5px 0 0 0; color: #666;">Edad Promedio</p>
                                </div>
                                
                                <div class="dashboard-stat-card" style="background: #fffbf0; padding: 20px; border-radius: 8px; border-left: 4px solid #f56e28;">
                                    <h3 style="margin: 0; color: #f56e28; font-size: 1.2em;">
                                        <?php echo $registro_reciente ? date('d/m/Y', strtotime($registro_reciente->creado_at)) : 'N/A'; ?>
                                    </h3>
                                    <p style="margin: 5px 0 0 0; color: #666;">Último Registro</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Gráfica de Edades -->
                    <?php if (!empty($stats_edad)): ?>
                    <div class="postbox">
                        <div class="postbox-header">
                            <h2 class="hndle ui-sortable-handle">
                                <span>📈 Distribución por Edad</span>
                            </h2>
                        </div>
                        <div class="inside">
                            <canvas id="adminAgeChart" width="400" height="200"></canvas>
                        </div>
                    </div>
                    <?php endif; ?>
                    
                    <!-- Instrucciones de Uso -->
                    <div class="postbox">
                        <div class="postbox-header">
                            <h2 class="hndle ui-sortable-handle">
                                <span>📝 Instrucciones de Uso</span>
                            </h2>
                        </div>
                        <div class="inside">
                            <h3>Shortcodes Disponibles:</h3>
                            <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0;">
                                <code>[formulario_jovenes]</code>
                                <p><em>Muestra el formulario multi-step para registro de jóvenes</em></p>
                            </div>
                            
                            <div style="background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0;">
                                <code>[tabla_jovenes]</code>
                                <p><em>Muestra la tabla con todos los registros y la gráfica de estadísticas</em></p>
                            </div>
                            
                            <h3>Características del Plugin:</h3>
                            <ul>
                                <li>✅ Formulario multi-step con validaciones en tiempo real</li>
                                <li>✅ Subida de fotos con preview</li>
                                <li>✅ Dependencias de países y departamentos</li>
                                <li>✅ Tabla de registros con opciones CRUD</li>
                                <li>✅ Gráficas interactivas con Chart.js</li>
                                <li>✅ Exportación de datos</li>
                                <li>✅ Diseño responsive con Bootstrap</li>
                                <li>✅ Validaciones de seguridad</li>
                            </ul>
                            
                            <h3>Páginas Recomendadas:</h3>
                            <ol>
                                <li><strong>Página de Registro:</strong> Crea una página y añade el shortcode <code>[formulario_jovenes]</code></li>
                                <li><strong>Página de Administración:</strong> Crea una página y añade el shortcode <code>[tabla_jovenes]</code></li>
                            </ol>
                        </div>
                    </div>
                    
                    <!-- Registros Recientes -->
                    <div class="postbox">
                        <div class="postbox-header">
                            <h2 class="hndle ui-sortable-handle">
                                <span>🕒 Registros Recientes</span>
                            </h2>
                        </div>
                        <div class="inside">
                            <?php
                            $recientes = $wpdb->get_results("
                                SELECT j.*, c.name as pais_name 
                                FROM {$wpdb->prefix}fj_jovenes j
                                LEFT JOIN {$wpdb->prefix}fj_countries c ON j.pais_id = c.id
                                ORDER BY j.creado_at DESC 
                                LIMIT 5
                            ");
                            ?>
                            
                            <?php if (empty($recientes)): ?>
                                <p style="text-align: center; color: #666; padding: 20px;">
                                    No hay registros disponibles aún.
                                </p>
                            <?php else: ?>
                                <table class="widefat fixed striped">
                                    <thead>
                                        <tr>
                                            <th>Nombre</th>
                                            <th>Edad</th>
                                            <th>País</th>
                                            <th>Teléfono</th>
                                            <th>Fecha</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($recientes as $joven): ?>
                                        <tr>
                                            <td><strong><?php echo esc_html($joven->nombres . ' ' . $joven->apellidos); ?></strong></td>
                                            <td><?php echo $joven->edad; ?> años</td>
                                            <td><?php echo esc_html($joven->pais_name); ?></td>
                                            <td><?php echo esc_html($joven->telefono); ?></td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($joven->creado_at)); ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php if (!empty($stats_edad)): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Datos para la gráfica de administración
    const ageData = {
        labels: [<?php echo implode(',', array_map(function($s) { return "'{$s->edad} años'"; }, $stats_edad)); ?>],
        datasets: [{
            label: 'Cantidad de Jóvenes',
            data: [<?php echo implode(',', array_column($stats_edad, 'cantidad')); ?>],
            backgroundColor: [
                'rgba(0, 115, 170, 0.8)',
                'rgba(0, 163, 42, 0.8)',
                'rgba(245, 110, 40, 0.8)',
                'rgba(51, 51, 51, 0.8)',
                'rgba(153, 102, 255, 0.8)',
                'rgba(255, 159, 64, 0.8)'
            ],
            borderColor: [
                'rgba(0, 115, 170, 1)',
                'rgba(0, 163, 42, 1)',
                'rgba(245, 110, 40, 1)',
                'rgba(51, 51, 51, 1)',
                'rgba(153, 102, 255, 1)',
                'rgba(255, 159, 64, 1)'
            ],
            borderWidth: 2
        }]
    };

    const ctx = document.getElementById('adminAgeChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'doughnut',
            data: ageData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'right'
                    },
                    title: {
                        display: true,
                        text: 'Distribución de Jóvenes por Edad'
                    }
                }
            }
        });
    }
});
</script>
<?php endif; ?>