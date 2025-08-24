# Plugin_Prueba_wordpress
Plugin en WordPress para prueba técnica: incluye página One Page con Bootstrap, formulario multi-step para jóvenes (18-23), guarda datos en MySQL, CRUD básico desde admin, gráfica por edades y exportación a CSV.

## ⚡ **INSTALACIÓN RÁPIDA - 5 MINUTOS**

### 🔥 **Opción 1: Instalación Automática (Recomendada)**

```bash
# Descarga directa en tu directorio de plugins
cd /path/to/wordpress/wp-content/plugins/
git clone https://github.com/tu-usuario/formulario-jovenes.git
```

### 💾 **Opción 2: Instalación Manual**

1. **Descarga el ZIP** del repositorio
2. **Extrae** en `/wp-content/plugins/`  
3. **Renombra** la carpeta a `formulario-jovenes`

### 📁 **Estructura Final:**
```
wp-content/
└── plugins/
    └── formulario-jovenes/
        ├── formulario-jovenes.php    ← ARCHIVO PRINCIPAL
        ├── README.md
        ├── assets/
        │   ├── style.css            ← ESTILOS
        │   └── script.js            ← JAVASCRIPT
        └── templates/
            ├── formulario.php       ← TEMPLATE FORMULARIO  
            ├── tabla.php           ← TEMPLATE TABLA
            └── admin.php           ← TEMPLATE ADMIN
```

### ⚙️ **Activación:**
1. Ve a **WordPress Admin → Plugins**
2. Busca "Formulario de Jóvenes Multi-Step" 
3. Haz clic en **ACTIVAR** ✅
4. ¡Listo! El plugin creará automáticamente las tablas necesarias

---

## 🚀 **CONFIGURACIÓN INICIAL - 2 PASOS**

### **PASO 1: Crear Páginas**

**📝 Página del Formulario:**
- **Páginas → Añadir nueva**
- **Título:** "Registro de Jóvenes"  
- **Contenido:** `[formulario_jovenes]`
- **Publicar** ✅

**📊 Página de Administración:**
- **Páginas → Añadir nueva**
- **Título:** "Administrar Registros"
- **Contenido:** `[tabla_jovenes]`  
- **Publicar** ✅

### **PASO 2: Verificar Funcionamiento**
- Ve a la página del formulario
- ¡Deberías ver el formulario multi-step funcionando! 🎉

---

## 💻 **BASE DE DATOS - YA LA TIENES LISTA**

Como ya creaste la base de datos con XAMPP, el plugin automáticamente:

✅ **Detecta las tablas existentes**  
✅ **Usa tu estructura actual**  
✅ **Agrega datos faltantes si es necesario**  
✅ **Mantiene tus registros existentes**

### **Tablas que usa:**
- `wp_fj_countries` (países)
- `wp_fj_departments` (departamentos)  
- `wp_fj_jovenes` (registros principales)

---

## 🎨 **DISEÑO Y THEMES**

### **✅ Themes 100% Compatibles:**
- **Astra** (Gratuito) - ⭐ MUY RECOMENDADO
- **G# 🎯 Plugin Formulario de Jóvenes - WordPress

Un plugin completo y profesional para WordPress que implementa un formulario multi-step para registro de jóvenes entre 18-23 años, con tabla de administración, gráficas y exportación de datos.

## ✨ Características

- 📝 **Formulario Multi-Step**: 3 pasos con validaciones en tiempo real
- 🖼️ **Subida de Fotos**: Con preview y validación de archivos
- 🌍 **Países y Departamentos**: Sistema de dependencias dinámicas
- 📊 **Gráficas Interactivas**: Estadísticas con Chart.js
- 📋 **Tabla de Administración**: CRUD completo con modales
- 📤 **Exportación de Datos**: Descarga en formato CSV
- 📱 **Diseño Responsive**: Bootstrap 5 integrado
- 🔒 **Seguridad**: Validaciones y nonces de WordPress
- ⚡ **AJAX**: Navegación fluida sin recargas

## 🚀 Instalación Rápida

### 1. Preparar la Base de Datos
Ya tienes la base de datos lista con XAMPP. ¡Perfecto! 

### 2. Crear la Estructura del Plugin

Crea la siguiente estructura de carpetas en `/wp-content/plugins/`:

```
formulario-jovenes/
├── formulario-jovenes.php          (Archivo principal)
├── assets/
│   ├── style.css                   (Estilos CSS)
│   └── script.js                   (JavaScript)
└── templates/
    ├── formulario.php              (Template del formulario)
    ├── tabla.php                   (Template de la tabla)
    └── admin.php                   (Panel de administración)
```

### 3. Subir los Archivos

1. **Archivo Principal**: `formulario-jovenes/formulario-jovenes.php`
   - Copia el código del "Plugin WordPress - Formulario Jóvenes"

2. **Estilos**: `formulario-jovenes/assets/style.css`
   - Copia el código CSS proporcionado

3. **JavaScript**: `formulario-jovenes/assets/script.js`
   - Copia el código JavaScript proporcionado

4. **Templates**:
   - `templates/formulario.php` - Template del formulario multi-step
   - `templates/tabla.php` - Template de tabla con gráficas
   - `templates/admin.php` - Panel de administración

### 4. Activar el Plugin

1. Ve a **Plugins → Plugins Instalados** en tu panel de WordPress
2. Busca "Formulario de Jóvenes Multi-Step"
3. Haz clic en **Activar**

## 📋 Configuración Post-Instalación

### 1. Verificar Creación de Tablas
El plugin automáticamente:
- ✅ Crea las tablas necesarias
- ✅ Inserta datos de países y departamentos
- ✅ Configura los índices requeridos

### 2. Crear Páginas

**Página del Formulario:**
- Crea una nueva página: **Páginas → Añadir nueva**
- Título: "Registro de Jóvenes"
- Contenido: `[formulario_jovenes]`
- **Publicar**

**Página de Administración:**
- Crea una nueva página: **Páginas → Añadir nueva**
- Título: "Gestión de Registros"
- Contenido: `[tabla_jovenes]`
- **Publicar**

### 3. Configurar Menú (Opcional)
- Ve a **Apariencia → Menús**
- Añade las páginas creadas al menú principal

## 🎨 Personalización de Diseño

### Themes Recomendados
Para mejores resultados visuales, usa themes que sean compatibles con Bootstrap:

1. **Astra** (Gratuito) - Muy liviano y compatible
2. **GeneratePress** (Gratuito) - Excelente rendimiento
3. **OceanWP** (Gratuito) - Muy personalizable
4. **Kadence** (Gratuito) - Moderno y responsive

### Configuración del Theme
1. **Ancho de Contenido**: Configura el ancho máximo a 1200px
2. **Padding**: Asegúrate de que las páginas tengan padding adecuado
3. **Sidebar**: Desactiva la sidebar en las páginas del formulario para mejor experiencia

## 🔧 Shortcodes Disponibles

### `[formulario_jovenes]`
Muestra el formulario completo de registro con:
- 3 steps con indicadores visuales
- Validaciones en tiempo real
- Subida de fotos
- Progress bar animado
- Modal de confirmación

### `[tabla_jovenes]`
Muestra la tabla de administración con:
- Lista de todos los registros
- Botones de Ver/Editar/Eliminar
- Gráfica de distribución por edad
- Estadísticas generales
- Exportación a CSV

## 📊 Panel de Administración

Accede al panel desde **Formulario Jóvenes** en el menú lateral del admin:

- 📈 **Estadísticas generales**
- 📊 **Gráficas interactivas**
- 📝 **Registros recientes**
- 📖 **Instrucciones de uso**

## 🛠️ Funcionalidades Técnicas

### Validaciones Implementadas
- ✅ **Campos obligatorios**: Validación en frontend y backend
- ✅ **Formato de nombres**: Solo letras y espacios
- ✅ **Teléfono**: Formato válido y longitud mínima
- ✅ **Edad**: Rango 18-23 años
- ✅ **Archivos**: Tipo y tamaño de imagen
- ✅ **CSRF Protection**: Nonces de WordPress

### Características de Seguridad
- 🔒 **Sanitización**: Todos los inputs son sanitizados
- 🔒 **Validación de Nonce**: Protección CSRF en todos los AJAX
- 🔒 **Escape de Output**: Prevención XSS
- 🔒 **Validación de Archivos**: Tipo y tamaño controlados

### Performance
- ⚡ **Lazy Loading**: Departamentos cargan bajo demanda
- ⚡ **AJAX**: Sin recargas de página
- ⚡ **CDN**: Bootstrap y Chart.js desde CDN
- ⚡ **Minificación**: Código optimizado

## 🐛 Solución de Problemas

### Problema: El formulario no aparece
**Solución:**
1. Verifica que el plugin esté activado
2. Asegúrate de usar el shortcode correcto: `[formulario_jovenes]`
3. Revisa la consola del navegador por errores JavaScript

### Problema: No se cargan los departamentos
**Solución:**
1. Verifica que AJAX funcione correctamente
2. Revisa que los países estén en la base de datos
3. Comprueba la configuración de permalinks

### Problema: Error al subir fotos
**Solución:**
1. Verifica permisos de la carpeta `wp-content/uploads/`
2. Asegúrate de que el tamaño máximo de archivo esté configurado
3. Comprueba los formatos permitidos (JPG, PNG, GIF)

### Problema: Las gráficas no se muestran
**Solución:**
1. Verifica que Chart.js se esté cargando
2. Comprueba que haya datos en la base de datos
3. Revisa la consola por errores JavaScript

## 🔄 Actualización de Datos

### Agregar Más Países
```sql
INSERT INTO wp_fj_countries (code, name) VALUES 
('BR', 'Brasil'),
('EC', 'Ecuador'),
('VE', 'Venezuela');
```

### Agregar Departamentos
```sql
INSERT INTO wp_fj_departments (country_id, name) VALUES 
((SELECT id FROM wp_fj_countries WHERE code='BR'), 'São Paulo'),
((SELECT id FROM wp_fj_countries WHERE code='BR'), 'Rio de Janeiro');
```

## 📱 Responsive Design

El plugin es totalmente responsive con breakpoints:
- 📱 **Móvil**: < 576px
- 📱 **Tablet**: 576px - 768px
- 💻 **Desktop**: > 768px

### Características Móviles
- Formulario optimizado para touch
- Botones más grandes
- Steps apilados verticalmente
- Tabla con scroll horizontal
- Gráficas adaptativas

## 🎯 Mejores Prácticas

### Para Administradores
1. **Backup Regular**: Exporta los datos regularmente
2. **Monitoreo**: Revisa las estadísticas frecuentemente
3. **Validación**: Verifica los datos ingresados periódicamente

### Para Desarrolladores
1. **Child Theme**: Usa un child theme para customizaciones
2. **Hooks**: Utiliza los hooks de WordPress para extensiones
3. **Cache**: Configura cache para mejor rendimiento

## 🤝 Soporte

### Logs de Errores
Si encuentras problemas, revisa:
- `/wp-content/debug.log`
- Consola del navegador (F12)
- Error logs del servidor

### Información del Sistema
- WordPress: 5.0+
- PHP: 7.4+
- MySQL: 5.6+
- jQuery: Incluido con WordPress

## 🚀 ¡Listo para Usar!

Con esta instalación tendrás:
- ✅ Formulario multi-step completamente funcional
- ✅ Sistema de administración profesional
- ✅ Gráficas y estadísticas
- ✅ Exportación de datos
- ✅ Diseño completamente responsive
- ✅ Validaciones de seguridad

¡Tu plugin está listo para recibir registros de jóvenes! 🎉

---

**Desarrollado con ❤️ para WordPress**
