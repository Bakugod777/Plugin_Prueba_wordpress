jQuery(document).ready(function($) {
    let currentStep = 1;
    const totalSteps = 3;
    let uploadedPhotoUrl = '';

    // Inicializar el formulario
    initializeForm();

    function initializeForm() {
        // Manejar cambio de país para cargar departamentos
        $('#pais_id').on('change', function() {
            const countryId = $(this).val();
            loadDepartments(countryId);
        });

        // Manejar subida de foto
        $('#foto').on('change', function() {
            handlePhotoUpload(this);
        });

        // Botones de navegación
        $('.next-step').on('click', function() {
            if (validateCurrentStep()) {
                nextStep();
            }
        });

        $('.prev-step').on('click', function() {
            prevStep();
        });

        // Submit del formulario
        $('#formulario-jovenes').on('submit', function(e) {
            e.preventDefault();
            if (validateCurrentStep()) {
                submitForm();
            }
        });

        // Validación en tiempo real
        setupRealTimeValidation();
    }

    function loadDepartments(countryId) {
        if (!countryId) {
            $('#departamento_id').html('<option value="">Selecciona primero un país</option>').prop('disabled', true);
            return;
        }

        $('#departamento_id').html('<option value="">Cargando...</option>').prop('disabled', true);

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'get_departments',
                country_id: countryId,
                nonce: ajax_object.nonce
            },
            success: function(response) {
                if (response.success) {
                    let options = '<option value="">Selecciona un departamento</option>';
                    response.data.forEach(function(department) {
                        options += `<option value="${department.id}">${department.name}</option>`;
                    });
                    $('#departamento_id').html(options).prop('disabled', false);
                } else {
                    $('#departamento_id').html('<option value="">Error al cargar departamentos</option>');
                }
            },
            error: function() {
                $('#departamento_id').html('<option value="">Error de conexión</option>');
            }
        });
    }

    function handlePhotoUpload(input) {
        if (input.files && input.files[0]) {
            const file = input.files[0];
            
            // Validar tipo de archivo
            const allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            if (!allowedTypes.includes(file.type)) {
                showError('Por favor selecciona un archivo de imagen válido (JPG, PNG, GIF)');
                input.value = '';
                return;
            }

            // Validar tamaño (2MB máximo)
            if (file.size > 2 * 1024 * 1024) {
                showError('El archivo debe ser menor a 2MB');
                input.value = '';
                return;
            }

            // Mostrar preview
            const reader = new FileReader();
            reader.onload = function(e) {
                $('#preview-container').html(`
                    <img src="${e.target.result}" alt="Preview" class="img-thumbnail">
                    <p class="text-success mt-2"><i class="fas fa-check"></i> Imagen seleccionada</p>
                `);
            };
            reader.readAsDataURL(file);

            // Subir archivo
            uploadPhoto(file);
        }
    }

    function uploadPhoto(file) {
        const formData = new FormData();
        formData.append('foto', file);
        formData.append('action', 'upload_foto');
        formData.append('nonce', ajax_object.nonce);

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    uploadedPhotoUrl = response.data.url;
                    $('#preview-container').append('<small class="text-success">✓ Foto subida correctamente</small>');
                } else {
                    showError('Error al subir la foto: ' + (response.data.message || 'Error desconocido'));
                }
            },
            error: function() {
                showError('Error de conexión al subir la foto');
            }
        });
    }

    function setupRealTimeValidation() {
        // Validación de nombres y apellidos (solo letras y espacios)
        $('#nombres, #apellidos').on('input', function() {
            const value = $(this).val();
            const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
            
            if (value && !regex.test(value)) {
                $(this).addClass('is-invalid');
                $(this).siblings('.invalid-feedback').text('Solo se permiten letras y espacios');
            } else {
                $(this).removeClass('is-invalid').addClass('is-valid');
            }
        });

        // Validación de teléfono
        $('#telefono').on('input', function() {
            const value = $(this).val();
            const regex = /^[0-9+\-\s()]+$/;
            
            if (value && !regex.test(value)) {
                $(this).addClass('is-invalid');
                $(this).siblings('.invalid-feedback').text('Formato de teléfono inválido');
            } else if (value.length < 7) {
                $(this).addClass('is-invalid');
                $(this).siblings('.invalid-feedback').text('El teléfono debe tener al menos 7 dígitos');
            } else {
                $(this).removeClass('is-invalid').addClass('is-valid');
            }
        });

        // Validación de edad
        $('#edad').on('change', function() {
            const value = parseInt($(this).val());
            if (value < 18 || value > 23) {
                $(this).addClass('is-invalid');
                $(this).siblings('.invalid-feedback').text('La edad debe estar entre 18 y 23 años');
            } else {
                $(this).removeClass('is-invalid').addClass('is-valid');
            }
        });
    }

    function validateCurrentStep() {
        let isValid = true;
        const currentStepElement = $(`#step-${currentStep}`);

        // Limpiar validaciones anteriores
        currentStepElement.find('.form-control, .form-select').removeClass('is-invalid');

        // Validar campos requeridos del step actual
        currentStepElement.find('[required]').each(function() {
            const field = $(this);
            const value = field.val();

            if (!value || value.trim() === '') {
                field.addClass('is-invalid');
                field.siblings('.invalid-feedback').text('Este campo es obligatorio');
                isValid = false;
            } else {
                // Validaciones específicas por campo
                if (field.attr('id') === 'nombres' || field.attr('id') === 'apellidos') {
                    const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
                    if (!regex.test(value)) {
                        field.addClass('is-invalid');
                        field.siblings('.invalid-feedback').text('Solo se permiten letras y espacios');
                        isValid = false;
                    }
                }

                if (field.attr('id') === 'telefono') {
                    const regex = /^[0-9+\-\s()]+$/;
                    if (!regex.test(value) || value.length < 7) {
                        field.addClass('is-invalid');
                        field.siblings('.invalid-feedback').text('Formato de teléfono inválido o muy corto');
                        isValid = false;
                    }
                }

                if (field.attr('id') === 'edad') {
                    const edad = parseInt(value);
                    if (edad < 18 || edad > 23) {
                        field.addClass('is-invalid');
                        field.siblings('.invalid-feedback').text('La edad debe estar entre 18 y 23 años');
                        isValid = false;
                    }
                }
            }
        });

        // Validar radio buttons (estado civil)
        if (currentStep === 2) {
            const estadoCivil = $('input[name="estado_civil"]:checked').val();
            if (!estadoCivil) {
                showError('Por favor selecciona tu estado civil');
                isValid = false;
            }
        }

        // Validar checkbox de términos
        if (currentStep === 3) {
            const aceptaTerminos = $('#acepta_terminos').is(':checked');
            if (!aceptaTerminos) {
                $('#acepta_terminos').addClass('is-invalid');
                $('#acepta_terminos').siblings('.invalid-feedback').text('Debes aceptar los términos y condiciones');
                isValid = false;
            }
        }

        return isValid;
    }

    function nextStep() {
        if (currentStep < totalSteps) {
            // Ocultar step actual
            $(`#step-${currentStep}`).removeClass('active');
            $('.step-indicator').eq(currentStep - 1).removeClass('active');

            // Mostrar siguiente step
            currentStep++;
            setTimeout(() => {
                $(`#step-${currentStep}`).addClass('active');
                $('.step-indicator').eq(currentStep - 1).addClass('active');
                updateProgressBar();
            }, 300);
        }
    }

    function prevStep() {
        if (currentStep > 1) {
            // Ocultar step actual
            $(`#step-${currentStep}`).removeClass('active');
            $('.step-indicator').eq(currentStep - 1).removeClass('active');

            // Mostrar step anterior
            currentStep--;
            setTimeout(() => {
                $(`#step-${currentStep}`).addClass('active');
                $('.step-indicator').eq(currentStep - 1).addClass('active');
                updateProgressBar();
            }, 300);
        }
    }

    function updateProgressBar() {
        const progress = (currentStep / totalSteps) * 100;
        $('#progress-bar').css('width', progress + '%');
    }

    function submitForm() {
        const submitBtn = $('#submit-btn');
        const originalText = submitBtn.html();

        // Mostrar loading
        submitBtn.html('<span class="spinner-border spinner-border-sm me-2"></span>Guardando...').prop('disabled', true);

        // Recopilar datos del formulario
        const formData = {
            action: 'save_joven',
            nonce: ajax_object.nonce,
            nombres: $('#nombres').val(),
            apellidos: $('#apellidos').val(),
            direccion: $('#direccion').val(),
            pais_id: $('#pais_id').val(),
            departamento_id: $('#departamento_id').val(),
            foto: uploadedPhotoUrl,
            telefono: $('#telefono').val(),
            estado_civil: $('input[name="estado_civil"]:checked').val(),
            edad: $('#edad').val(),
            descripcion: $('#descripcion').val(),
            acepta_terminos: $('#acepta_terminos').is(':checked') ? 1 : 0
        };

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: formData,
            success: function(response) {
                submitBtn.html(originalText).prop('disabled', false);

                if (response.success) {
                    // Mostrar modal de éxito
                    $('#successModal').modal('show');
                    
                    // Limpiar formulario después de 3 segundos
                    setTimeout(() => {
                        resetForm();
                    }, 3000);
                } else {
                    showError('Error al guardar: ' + (response.data.message || 'Error desconocido'));
                }
            },
            error: function() {
                submitBtn.html(originalText).prop('disabled', false);
                showError('Error de conexión. Por favor intenta nuevamente.');
            }
        });
    }

    function resetForm() {
        // Reset formulario
        $('#formulario-jovenes')[0].reset();
        
        // Reset steps
        currentStep = 1;
        $('.step-content').removeClass('active');
        $('.step-indicator').removeClass('active');
        $('#step-1').addClass('active');
        $('.step-indicator').eq(0).addClass('active');
        updateProgressBar();
        
        // Reset departamentos
        $('#departamento_id').html('<option value="">Selecciona primero un país</option>').prop('disabled', true);
        
        // Reset preview
        $('#preview-container').empty();
        uploadedPhotoUrl = '';
        
        // Reset validaciones
        $('.form-control, .form-select').removeClass('is-invalid is-valid');
    }

    function showError(message) {
        // Crear y mostrar alerta de error
        const alert = $(`
            <div class="alert alert-danger alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; max-width: 400px;">
                <i class="fas fa-exclamation-triangle me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
        
        $('body').append(alert);
        
        // Auto-remove después de 5 segundos
        setTimeout(() => {
            alert.alert('close');
        }, 5000);
    }

    function showSuccess(message) {
        // Crear y mostrar alerta de éxito
        const alert = $(`
            <div class="alert alert-success alert-dismissible fade show position-fixed" 
                 style="top: 20px; right: 20px; z-index: 9999; max-width: 400px;">
                <i class="fas fa-check-circle me-2"></i>${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `);
        
        $('body').append(alert);
        
        // Auto-remove después de 3 segundos
        setTimeout(() => {
            alert.alert('close');
        }, 3000);
    }

    // Función para exportar datos (si está en la página de tabla)
    window.exportData = function() {
        const link = document.createElement('a');
        link.href = ajax_object.ajax_url + '?action=export_data&nonce=' + ajax_object.nonce;
        link.download = 'registros_jovenes.csv';
        link.click();
    };

    // Funciones globales para la tabla
    window.verPerfil = function(id) {
        $.ajax({
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
                    $('#perfil-content').html(content);
                    new bootstrap.Modal(document.getElementById('perfilModal')).show();
                } else {
                    showError('No se pudo cargar el perfil');
                }
            }
        });
    };

    let deleteId = null;
    window.eliminarJoven = function(id) {
        deleteId = id;
        new bootstrap.Modal(document.getElementById('deleteModal')).show();
    };

    // Event listener para confirmación de eliminación
    $(document).on('click', '#confirm-delete', function() {
        if (deleteId) {
            $.ajax({
                url: ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'delete_joven',
                    id: deleteId,
                    nonce: ajax_object.nonce
                },
                success: function(response) {
                    if (response.success) {
                        showSuccess('Registro eliminado correctamente');
                        setTimeout(() => {
                            location.reload();
                        }, 1500);
                    } else {
                        showError('Error al eliminar el registro');
                    }
                }
            });
        }
    });

    window.editarJoven = function(id) {
        showError('Funcionalidad de edición en desarrollo');
    };
});