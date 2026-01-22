# Reseña de Código y Recomendaciones de Mejora

Esta reseña analiza el estado actual del código fuente del sistema "URL Histórico". Se han identificado áreas críticas que requieren atención inmediata, principalmente en términos de seguridad, así como oportunidades para mejorar la arquitectura y mantenibilidad del proyecto.

## 1. Seguridad (Crítico)

Esta es el área más preocupante y debe ser priorizada.

### 1.1. Inyección SQL
El código es vulnerable a ataques de Inyección SQL en múltiples lugares.
- **Problema**: Se concatenan variables directamente en las cadenas de consulta SQL.
- **Ejemplos**:
  - `ModeloUsuario::getDatosUsuario`: `WHERE kUsuario = '$usuario'`
  - `ModeloUsuario::updateDatosUsuario`: `SET sNombre = '$datos[1]'...`
  - `ModeloGeneral::borrar`: `WHERE $key = '$id'`
- **Riesgo**: Un atacante podría manipular las entradas para ejecutar comandos SQL arbitrarios, accediendo, modificando o eliminando datos de la base de datos.
- **Solución**: Utilizar **siempre** sentencias preparadas (Prepared Statements) con parámetros vinculados, como se hace parcialmente en `ModeloValidarUsuario`.

### 1.2. Almacenamiento de Contraseñas
- **Problema**: Las contraseñas parecen almacenarse en texto plano.
- **Evidencia**: En `ModeloUsuario::registrarUsuario` se inserta `$datos[3]` directamente. En `ModeloValidarUsuario` se compara la contraseña directamente en la consulta `sPassword = ?`.
- **Riesgo**: Si la base de datos se ve comprometida, todas las cuentas de usuario serán expuestas inmediatamente.
- **Solución**: Almacenar solo el *hash* de las contraseñas utilizando `password_hash()` al registrar/actualizar y verificarlas con `password_verify()` al iniciar sesión.

### 1.3. Cross-Site Scripting (XSS)
- **Problema**: Los datos de usuario se imprimen en las vistas sin una sanitización consistente.
- **Ejemplos**: En `ControladorUsuario`, se inyectan valores en la plantilla usando `str_replace`. Si `$r[0]['sNombre']` contiene script malicioso, se ejecutará en el navegador del usuario.
- **Solución**: Escapar todas las salidas HTML utilizando `htmlspecialchars()` antes de enviarlas a la vista.

### 1.4. Credenciales en el Código
- **Problema**: Las credenciales de la base de datos están "hardcoded" en `modelo/conexion.php`.
- **Riesgo**: Si el código se comparte o se expone (por ejemplo, en un repositorio público), las credenciales quedan comprometidas.
- **Solución**: Utilizar variables de entorno (archivo `.env`) para manejar la configuración sensible y no incluirlas en el control de versiones.

## 2. Arquitectura y Calidad de Código

### 2.1. Patrón MVC y Responsabilidades
- **Observación**: Aunque hay carpetas `controlador`, `modelo`, y `vista`, la separación no es estricta.
- **Problema**: Los controladores (`ControladorUsuario`) están construyendo HTML (concatenando strings con etiquetas) y haciendo `echo`. Esto debería ser responsabilidad exclusiva de la vista.
- **Mejora**: Usar un motor de plantillas (como Twig o Smarty) o separar completamente la lógica de la presentación, pasando solo datos a los archivos de vista.

### 2.2. Uso de Variables Globales
- **Observación**: Se hace un uso extensivo de `$GLOBALS['url']`, `$GLOBALS['usuario_rol']`, etc.
- **Problema**: Esto hace que el código sea difícil de probar y mantener, ya que las dependencias están ocultas y el estado global es impredecible.
- **Mejora**: Usar inyección de dependencias o un contenedor de servicios para manejar la configuración y el estado de la sesión.

### 2.3. Estilo de Código y Estándares
- **Observación**: El código sigue un estilo antiguo (PHP 5.x/7.0).
- **Problema**:
  - No se usan **Namespaces**, lo que puede causar colisiones de nombres.
  - No hay un sistema de **Autoloading** (se usan muchos `include_once` manuales).
  - Acceso directo a superglobales (`$_POST`, `$_GET`) en los métodos.
- **Mejora**: Adoptar estándares PSR (PSR-1, PSR-4, PSR-12). Implementar Composer para la gestión de dependencias y autocarga.

## 3. Recomendaciones Prioritarias

Para estabilizar y asegurar el proyecto, se sugiere seguir este plan de acción:

1.  **Refactorización de Seguridad (Inmediato)**:
    -   Reemplazar todas las consultas SQL concatenadas por sentencias preparadas.
    -   Implementar el hashing de contraseñas.
    -   Mover las credenciales de base de datos a un archivo de configuración externo fuera del directorio web.

2.  **Limpieza de Código**:
    -   Eliminar la generación de HTML dentro de los controladores.
    -   Centralizar la validación y sanitización de entradas.

3.  **Modernización (A medio plazo)**:
    -   Introducir Composer y un autoloader.
    -   Migrar a PDO para la conexión a base de datos (ofrece mejor manejo de errores y consistencia).
    -   Considerar el uso de un micro-framework (como Slim o Laravel Lumen) si se planea expandir el sistema, para manejar el enrutamiento y la inyección de dependencias de manera más robusta.
