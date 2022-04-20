# wp_subsk

    Plugin para administración de suscripciones, control de accesos a contenidos y a caracteristicas de wordpress

## Caracteristicas

- Crear, editar, borrar tipos de suscripciones (type post: subs_types)
- Control de Accesos a tipos de contenidos (post, page, image, y otros)
- Control de Accesos a caracteristicas de Wordpress
- Hooks propios del plugin
  - Esto permitirá extender las funcionalidades del mismo
- Desarrollo de funciones con filtros antes del return para poder formatear las salidas antes de ser devueltas

## Hooks

- wp_subsk_content_before
- wp_subsk_content_after
- wp_subsk_cost_before
- wp_subsk_cost_after
- wp_subsk_period_before
- wp_subsk_period_after

## Filters

- wp_subsk_currency
- wp_subsk_period
- wp_subsk_period_min
- wp_subsk_period_max
- wp_subsk_period_step
- wp_subsk_period_format
- wp_subsk_cost
- wp_subsk_cost_min
- wp_subsk_cost_max
- wp_subsk_cost_step
