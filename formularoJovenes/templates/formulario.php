<?php
global $wpdb;

// Obtener países para el select
$table_countries = $wpdb->prefix . 'fj_countries';
$countries = $wpdb->get_results("SELECT id, name FROM $table_countries ORDER BY name");
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-lg">
                <div class="card-header bg-gradient-primary text-white">
                    <div class="text-center">
                        <h3 class="fw-light mb-0">Registro de Jóvenes</h3>
                        <p class="mb-0">Completa tu información en 3 sencillos pasos</p>
                    </div>
                </div>
                
                <div class="card-body p-5">
                    <div class="progress mb-4" style="height: 8px;">
                        <div class="progress-bar bg-gradient-primary progress-bar-striped progress-bar-animated" 
                             role="progressbar" style="width: 33%" id="progress-bar">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mb-5">
                        <div class="step-indicator active" data-step="1">
                            <div class="step-circle">
                                <i class="fas fa-user"></i>
                            </div>
                            <small>Datos Personales</small>
                        </div>
                        <div class="step-indicator" data-step="2">
                            <div class="step-circle">
                                <i class="fas fa-map-marker-alt"></i>
                            </div>
                            <small>Ubicación y Contacto</small>
                        </div>
                        <div class="step-indicator" data-step="3">
                            <div class="step-circle">
                                <i class="fas fa-check"></i>
                            </div>
                            <small>Información Final</small>
                        </div>
                    </div>
                    
                    <form id="formulario-jovenes" enctype="multipart/form-data">
                        <?php wp_nonce_field('formulario_jovenes_nonce', 'formulario_nonce'); ?>
                        
              
                        <div class="step-content active" id="step-1">
                            <h4 class="mb-4 text-center text-primary">
                                <i class="fas fa-user me-2"></i>Datos Personales
                            </h4>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="nombres" class="form-label">
                                        <i class="fas fa-user me-1"></i>Nombres *
                                    </label>
                                    <input type="text" class="form-control form-control-lg" 
                                           id="nombres" name="nombres" required
                                           placeholder="Ingresa tus nombres">
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="apellidos" class="form-label">
                                        <i class="fas fa-user me-1"></i>Apellidos *
                                    </label>
                                    <input type="text" class="form-control form-control-lg" 
                                           id="apellidos" name="apellidos" required
                                           placeholder="Ingresa tus apellidos">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="direccion" class="form-label">
                                    <i class="fas fa-home me-1"></i>Dirección *
                                </label>
                                <textarea class="form-control form-control-lg" 
                                          id="direccion" name="direccion" rows="3" required
                                          placeholder="Ingresa tu dirección completa"></textarea>
                                <div class="invalid-feedback"></div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="pais_id" class="form-label">
                                    <i class="fas fa-globe me-1"></i>País *
                                </label>
                                <select class="form-select form-select-lg" id="pais_id" name="pais_id" required>
                                    <option value="">Selecciona un país</option>
                                    <?php foreach($countries as $country): ?>
                                        <option value="<?php echo $country->id; ?>">
                                            <?php echo esc_html($country->name); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <div class="invalid-feedback"></div>
                            </div>
                            
                            <div class="text-end">
                                <button type="button" class="btn btn-primary btn-lg px-4 next-step">
                                    Siguiente <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                        
                        <div class="step-content" id="step-2">
                            <h4 class="mb-4 text-center text-primary">
                                <i class="fas fa-map-marker-alt me-2"></i>Ubicación y Contacto
                            </h4>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="departamento_id" class="form-label">
                                        <i class="fas fa-map-pin me-1"></i>Departamento/Estado *
                                    </label>
                                    <select class="form-select form-select-lg" 
                                            id="departamento_id" name="departamento_id" required disabled>
                                        <option value="">Selecciona primero un país</option>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                                
                                <div class="col-md-6 mb-3">
                                    <label for="telefono" class="form-label">
                                        <i class="fas fa-phone me-1"></i>Teléfono *
                                    </label>
                                    <input type="tel" class="form-control form-control-lg" 
                                           id="telefono" name="telefono" required
                                           placeholder="Ingresa tu número de teléfono">
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="foto" class="form-label">
                                        <i class="fas fa-camera me-1"></i>Foto de Perfil
                                    </label>
                                    <input type="file" class="form-control form-control-lg" 
                                           id="foto" name="foto" accept="image/*">
                                    <div class="form-text">Formatos permitidos: JPG, PNG, GIF (máx. 2MB)</div>
                                    <div class="mt-2" id="preview-container"></div>
                                </div>
                                
                                <div class="col-md-6 mb-4">
                                    <label class="form-label">
                                        <i class="fas fa-heart me-1"></i>Estado Civil *
                                    </label>
                                    <div class="mt-2">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="estado_civil" 
                                                   id="soltero" value="Soltero" required>
                                            <label class="form-check-label" for="soltero">Soltero(a)</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="estado_civil" 
                                                   id="casado" value="Casado">
                                            <label class="form-check-label" for="casado">Casado(a)</label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="estado_civil" 
                                                   id="union_libre" value="Union Libre">
                                            <label class="form-check-label" for="union_libre">Unión Libre</label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="estado_civil" 
                                                   id="otro" value="Otro">
                                            <label class="form-check-label" for="otro">Otro</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary btn-lg px-4 prev-step">
                                    <i class="fas fa-arrow-left me-1"></i> Anterior
                                </button>
                                <button type="button" class="btn btn-primary btn-lg px-4 next-step">
                                    Siguiente <i class="fas fa-arrow-right ms-1"></i>
                                </button>
                            </div>
                        </div>
                        
                     
                        <div class="step-content" id="step-3">
                            <h4 class="mb-4 text-center text-primary">
                                <i class="fas fa-check me-2"></i>Información Final
                            </h4>
                            
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="edad" class="form-label">
                                        <i class="fas fa-calendar me-1"></i>Edad *
                                    </label>
                                    <select class="form-select form-select-lg" id="edad" name="edad" required>
                                        <option value="">Selecciona tu edad</option>
                                        <?php for($i = 18; $i <= 23; $i++): ?>
                                            <option value="<?php echo $i; ?>"><?php echo $i; ?> años</option>
                                        <?php endfor; ?>
                                    </select>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            
                            <div class="mb-4">
                                <label for="descripcion" class="form-label">
                                    <i class="fas fa-edit me-1"></i>Descripción Personal
                                </label>
                                <textarea class="form-control form-control-lg" 
                                          id="descripcion" name="descripcion" rows="4"
                                          placeholder="Cuéntanos un poco sobre ti (opcional)"></textarea>
                            </div>
                            
                            <div class="mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" 
                                           id="acepta_terminos" name="acepta_terminos" value="1" required>
                                    <label class="form-check-label" for="acepta_terminos">
                                        Acepto los <a href="#" class="text-decoration-none">términos y condiciones</a> *
                                    </label>
                                    <div class="invalid-feedback"></div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-outline-secondary btn-lg px-4 prev-step">
                                    <i class="fas fa-arrow-left me-1"></i> Anterior
                                </button>
                                <button type="submit" class="btn btn-success btn-lg px-4" id="submit-btn">
                                    <i class="fas fa-save me-1"></i> Guardar Registro
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal de éxito -->
<div class="modal fade" id="successModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title">
                    <i class="fas fa-check-circle me-2"></i>¡Registro Exitoso!
                </h5>
            </div>
            <div class="modal-body text-center">
                <div class="mb-3">
                    <i class="fas fa-check-circle text-success" style="font-size: 4rem;"></i>
                </div>
                <p class="mb-0">Tu información ha sido guardada correctamente.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" data-bs-dismiss="modal">Entendido</button>
            </div>
        </div>
    </div>
</div>