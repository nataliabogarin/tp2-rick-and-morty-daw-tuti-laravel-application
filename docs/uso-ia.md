# Uso de herramientas de Inteligencia Artificial

## Introducción

El punto 1.5 de la consigna del Trabajo Práctico N.º 2 pide documentar y justificar el uso de herramientas de Inteligencia Artificial (IA) durante el desarrollo. Este documento describe con qué fines se utilizaron, qué aportes se incorporaron al proyecto y con qué criterios se analizaron y validaron los resultados.

## Herramienta utilizada

Se utilizó **Claude Code**, de Anthropic. Es un asistente de programación que se integra en el editor Visual Studio Code.

## Finalidad del uso

La herramienta se utilizó con dos fines: **educativo** y **configuración de las pruebas automatizadas** de la aplicación. Las funcionalidades del sistema fueron desarrolladas por los integrantes del grupo: el explorador público, la autenticación de usuarios y las listas personalizadas.

### Uso con fines educativos

La IA se usó como recurso de consulta, complementario a la documentación oficial y al material de la cátedra, para comprender conceptos del framework. Entre los temas consultados se encuentran:

- **Configuración de modelos Eloquent sobre un esquema que no sigue las convenciones de Laravel.**
  - Uso de las propiedades `$primaryKey`, `$incrementing`, `$keyType` y `$timestamps`.
  - Definición de las relaciones `belongsTo`, `hasMany` y `belongsToMany` indicando explícitamente claves foráneas y tablas intermedias.
- **Paginación de resultados.** Diferencias entre `all()` y `paginate()`, y su impacto en el rendimiento cuando se trabaja con conjuntos grandes de datos.
- **Control de acceso.**
  - Funcionamiento de los middleware `auth` y `guest`.
  - Diferencia entre middleware y políticas de autorización (*policies*).
- **Seguridad en formularios.** Propósito del token CSRF y de la protección contra asignación masiva (`$fillable`).
- **Interpretación de mensajes de error** que aparecieron durante el desarrollo. Las consultas se orientaron a comprender la causa del error y no a obtener una solución directa.

### Configuración de las pruebas automatizadas

Las pruebas automatizadas en Laravel no formaban parte de los contenidos abordados previamente por el grupo, por lo que la IA se utilizó como guía para:

- Comprender la organización de las pruebas en Laravel (`tests/Feature` y `tests/Unit`) y su ejecución con `php artisan test`.
- Configurar el entorno de pruebas en `phpunit.xml` para usar una base de datos SQLite en memoria. Así las pruebas quedan aisladas de la base de datos de desarrollo.
- Aplicar el trait `RefreshDatabase`, para que cada prueba comience con la base de datos en un estado conocido.
- Simular las respuestas de la API de Rick and Morty con `Http::fake()`. De esta forma, las pruebas del comando de importación no dependen de la conexión a internet ni de la disponibilidad del servicio externo.
- Interpretar y aplicar las aserciones `assertDatabaseHas`, `assertSuccessful`, `assertCount` y `assertStatus`.

### Revisión y documentación

En la etapa final, la herramienta se utilizó para contrastar el proyecto con los requisitos de la consigna e identificar aspectos pendientes de corrección. También sirvió de apoyo en la redacción del archivo `README.md` y del presente documento. Las correcciones derivadas de esa revisión fueron realizadas por los integrantes del grupo.

## Aportes incorporados

- **Conocimientos conceptuales.** Se aplicaron en el desarrollo de los modelos, las rutas, los controladores y las vistas.
- **Configuración del entorno de pruebas.** Incluye el uso de `RefreshDatabase` y `Http::fake()`.
- **Listado de observaciones.** Se obtuvo al contrastar la aplicación con la consigna y se utilizó como guía para la revisión final.
- **Redacción de la documentación.** Fue revisada y ajustada por el grupo.

## Criterios de análisis y validación

1. **Contraste con la documentación oficial.** Las explicaciones obtenidas se verificaron con la documentación oficial de Laravel (<https://laravel.com/docs>), prestando atención a las diferencias entre versiones del framework.
2. **Verificación práctica.** Los conceptos se probaron sobre el proyecto para comprobar su funcionamiento. En el caso de las pruebas automatizadas, se verificó que detectaran correctamente tanto los casos exitosos como los fallidos.
3. **Comprensión previa a la incorporación.** No se incorporó al proyecto ningún contenido que los integrantes no pudieran explicar y justificar.
4. **Revisión entre pares.** Los cambios se integraron mediante *pull requests* en GitHub y fueron revisados por otro integrante del grupo.
5. **Análisis crítico de las respuestas.** Algunas respuestas fueron incorrectas o no se ajustaban al proyecto; por ejemplo, sugerencias que suponían claves primarias `id` en lugar de las definidas en el modelo del Trabajo Práctico N.º 1. Por eso todas las respuestas se contrastaron con el código y el modelo de datos propios.

## Conclusión

El uso de la IA facilitó la comprensión de conceptos del framework y la incorporación de pruebas automatizadas, un aspecto nuevo para el grupo. Su utilización no reemplazó el análisis, el diseño ni el desarrollo de la solución, que fueron realizados por los integrantes. Cada integrante está en condiciones de explicar, justificar y modificar cualquier parte de la implementación presentada, como lo establece la consigna.
