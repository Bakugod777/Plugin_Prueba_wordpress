<?php
global $wpdb;

// Obtener datos de jóvenes con información de país y departamento
$jovenes = $wpdb->get_results("
    SELECT j.*, c.name as pais_name, d.name as departamento_name 
    FROM {$wpdb->prefix}fj_jovenes j
    LEFT JOIN {$wpdb->prefix}fj_countries c ON j.pais_id = c.id
    LEFT JOIN {$wpdb->prefix}fj_departments d ON j.departamento_id = d.id
    ORDER BY j.creado_at DESC
");

// Obtener datos para gráfica
$stats = $wpdb->get_results("
    SELECT edad, COUNT(*) as cantidad 
    FROM {$wpdb->prefix}fj_jovenes 
    GROUP BY edad 
    ORDER BY edad
");
?>

<div class="container-fluid mt-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-info text-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0">
                            <i class="fas fa-users me-2"></i>Registro de Jóvenes
                        </h4>
                        <div>
                            <button class="btn btn-light btn-sm me-2" onclick="exportData()">
                                <i class="fas fa-download me-1"></i>Exportar
                            </button>
                            <span class="badge bg-light text-dark fs-6">
                                Total: <?php echo count($jovenes); ?> registros
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="card-body p-0">
                    <?php if (empty($jovenes)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-users text-muted" style="font-size: 4rem;"></i>
                            <h5 class="mt-3 text-muted">No hay registros disponibles</h5>
                            <p class="text-muted">Los registros aparecerán aquí una vez que se completen formularios.</p>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-hover table-striped mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        <th scope="col">Foto</th>
                                        <th scope="col">Nombre Completo</th>
                                        <th scope="col">Edad</th>
                                        <th scope="col">País</th>
                                        <th scope="col">Departamento</th>
                                        <th scope="col">Teléfono</th>
                                        <th scope="col">Estado Civil</th>
                                        <th scope="col">Fecha Registro</th>
                                        <th scope="col" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach($jovenes as $index => $joven): ?>
                                    <tr>
                                        <td><?php echo $index + 1; ?></td>
                                        <td>
                                            <?php if($joven->foto): ?>
                                                <img src="<?php echo esc_url($joven->foto); ?>" 
                                                     alt="Foto perfil" class="rounded-circle" 
                                                     style="width: 40px; height: 40px; object-fit: cover;">
                                            <?php else: ?>
                                                <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center text-white" 
                                                     style="width: 40px; height: 40px;">
                                                    <i class="fas fa-user"></i>
                                                </div>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <strong><?php echo esc_html($joven->nombres . ' ' . $joven->apellidos); ?></strong>
                                        </td>
                                        <td>
                                            <span class="badge bg-info"><?php echo $joven->edad; ?> años</span>
                                        </td>
                                        <td><?php echo esc_html($joven->pais_name); ?></td>
                                        <td><?php echo esc_html($joven->departamento_name); ?></td>
                                        <td>
                                            <a href="tel:<?php echo esc_attr($joven->telefono); ?>" class="text-decoration-none">
                                                <i class="fas fa-phone me-1"></i><?php echo esc_html($joven->telefono); ?>
                                            </a>
                                        </td>
                                        <td>
                                            <span class="badge bg-outline-primary"><?php echo esc_html($joven->estado_civil); ?></span>
                                        </td>
                                        <td>
                                            <small><?php echo date('d/m/Y H:i', strtotime($joven->creado_at)); ?></small>
                                        </td>
                                        <td class="text-center">
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-info btn-sm" 
                                                        onclick="verPerfil(<?php echo $joven->id; ?>)" 
                                                        title="Ver Perfil">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-warning btn-sm" 
                                                        onclick="editarJoven(<?php echo $joven->id; ?>)" 
                                                        title="Editar">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button type="button" class="btn btn-danger btn-sm" 
                                                        onclick="eliminarJoven(<?php echo $joven->id; ?>)" 
                                                        title="Eliminar">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Gráfica de Estadísticas -->
    <?php if (!empty($stats)): ?>
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-bar me-2"></i>Distribución por Edad
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="ageChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-gradient-warning text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>Estadísticas Generales
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="border-end">
                                <h3 class="text-primary"><?php echo count($jovenes); ?></h3>
                                <p class="text-muted mb-0">Total Registros</p>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <h3 class="text-success">
                                <?php 
                                if(!empty($jovenes)) {
                                    echo round(array_sum(array_column($jovenes, 'edad')) / count($jovenes), 1);
                                } else {
                                    echo "0";
                                }
                                ?>
                            </h3>
                            <p class="text-muted mb-0">Edad Promedio</p>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <h6>Registros por Edad:</h6>
                        <?php foreach($stats as $stat): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span><?php echo $stat->edad; ?> años</span>
                                <span class="badge bg-primary"><?php echo $stat->cantidad; ?></span>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Modal Ver Perfil -->
<div class="modal fade" id="perfilModal" tabindex="-1">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title">
                    <i class="fas fa-user-circle me-2"></i>Perfil del Joven
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="perfil-content">
                <!-- Contenido dinámico -->
            </div>
        </div>
    </div>
</div>

<!-- Modal Confirmación Eliminar -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle me-2"></i>Confirmar Eliminación
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>¿Estás seguro de que deseas eliminar este registro?</p>
                <p class="text-muted">Esta acción no se puede deshacer.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <button type="button" class="btn btn-danger" id="confirm-delete">
                    <i class="fas fa-trash me-1"></i>Eliminar
                </button>
            </div>
        </div>
    </div>
</div>

<script>
// Datos para la gráfica
const ageData = {
    labels: [<?php echo implode(',', array_map(function($s) { return "'{$s->edad} años'"; }, $stats)); ?>],
    datasets: [{
        label: 'Cantidad de Jóvenes',
        data: [<?php echo implode(',', array_column($stats, 'cantidad')); ?>],
        backgroundColor: [
            'rgba(54, 162, 235, 0.8)',
            'rgba(255, 99, 132, 0.8)',
            'rgba(255, 205, 86, 0.8)',
            'rgba(75, 192, 192, 0.8)',
            'rgba(153, 102, 255, 0.8)',
            'rgba(255, 159, 64, 0.8)'
        ],
        borderColor: [
            'rgba(54, 162, 235, 1)',
            'rgba(255, 99, 132, 1)',
            'rgba(255, 205, 86, 1)',
            'rgba(75, 192, 192, 1)',
            'rgba(153, 102, 255, 1)',
            'rgba(255, 159, 64, 1)'
        ],
        borderWidth: 2
    }]
};

// Configurar gráfica
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('ageChart');
    if (ctx) {
        new Chart(ctx, {
            type: 'bar',
            data: ageData,
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    },
                    title: {
                        display: true,
                        text: 'Distribución de Jóvenes por Edad'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    }
});

// Funciones JavaScript para las acciones
function verPerfil(id) {
    jQuery.ajax({
        url: ajax_object.ajax_url,
        type: 'POST',
        data: {
            action: 'get_joven',
            id: id,
            nonce: ajax_object.nonce
        },
        success: function(response) {
            if (response.success) {
                const joven = response.data;
                const content = `
                    <div class="row">
                        <div class="col-md-4 text-center mb-3">
                            ${joven.foto ? 
                                `<img src="${joven.foto}" class="img-fluid rounded-circle mb-3" style="width: 150px; height: 150px; object-fit: cover;">` :
                                `<div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center text-white mx-auto mb-3" style="width: 150px; height: 150px; font-size: 3rem;"><i class="fas fa-user"></i></div>`
                            }
                            <h4>${joven.nombres} ${joven.apellidos}</h4>
                            <span class="badge bg-info fs-6">${joven.edad} años</span>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-sm-6 mb-3">
                                    <strong><i class="fas fa-globe me-2 text-primary"></i>País:</strong><br>
                                    ${joven.pais_name}
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <strong><i class="fas fa-map-marker-alt me-2 text-primary"></i>Departamento:</strong><br>
                                    ${joven.departamento_name}
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <strong><i class="fas fa-phone me-2 text-primary"></i>Teléfono:</strong><br>
                                    <a href="tel:${joven.telefono}">${joven.telefono}</a>
                                </div>
                                <div class="col-sm-6 mb-3">
                                    <strong><i class="fas fa-heart me-2 text-primary"></i>Estado Civil:</strong><br>
                                    ${joven.estado_civil}
                                </div>
                                <div class="col-12 mb-3">
                                    <strong><i class="fas fa-home me-2 text-primary"></i>Dirección:</strong><br>
                                    ${joven.direccion}
                                </div>
                                ${joven.descripcion ? `
                                <div class="col-12 mb-3">
                                    <strong><i class="fas fa-user-edit me-2 text-primary"></i>Descripción:</strong><br>
                                    ${joven.descripcion}
                                </div>
                                ` : ''}
                                <div class="col-12">
                                    <small class="text-muted">
                                        <i class="fas fa-calendar me-1"></i>
                                        Registrado el: ${new Date(joven.creado_at).toLocaleDateString('es-ES')}
                                    </small>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                document.getElementById('perfil-content').innerHTML = content;
                new bootstrap.Modal(document.getElementById('perfilModal')).show();
            }
        }
    });
}
let deleteId = null;
function eliminarJoven(id) {
    deleteId = id;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}

document.getElementById('confirm-delete').addEventListener('click', function() {
    if (deleteId) {
        jQuery.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'delete_joven',
                id: deleteId,
                nonce: ajax_object.nonce
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Error al eliminar el registro');
                }
            }
        });
    }
});

function exportData() {
    window.open(ajax_object.ajax_url + '?action=export_data&nonce=' + ajax_object.nonce, '_blank');
}
</script>