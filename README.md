# wp_subsk

    Plugin para administración de suscripciones, control de accesos a contenidos
    y a caracteristicas de wordpress

## Caracteristicas

- Crear, editar, borrar tipos de suscripciones (type post: subs_types)
- Control de Accesos a tipos de contenidos (post, page, image, y otros)
- Control de Accesos a caracteristicas de Wordpress
- Hooks propios del plugin
  - Esto permitirá extender las funcionalidades del mismo
- Desarrollo de funciones con filtros antes del return para poder formatear las salidas antes de ser devueltas
- Cada subs_types posee una serie de metas para configurar la suscripcion

## Metas

### Precio:

    En este metabox se controla el precio y moneda en la que se cobra la suscripcion

### Periodo:

    En este metabox se controla el periodo que dura la suscripcion  antes del siguiente cobro,
    este periodo viene por defecto en diaz pero puede presentarse en otros formatos.

### Post Type permitidos:

    En este metabox se decide a que tipos de contenidos son accesibles, puede ser de manera generica
    o una entrada en particular

### Accesos Especiales:

    En este metabox se decide a que tipos de funcionalidades de wordpress
    se tendra acceso con este tipo de suscripcion

## Hooks:

    Los hooks "before" permiten agregar contenido antes de que se muestre el correspondiente contenido,
    de la misma manera los hooks "after" lo hacen despues

- wp_subsk_content_before
- wp_subsk_content_after
- wp_subsk_cost_before
- wp_subsk_cost_after
- wp_subsk_period_before
- wp_subsk_period_after

## Filters:

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

# TEST

## paypal test account:

### Personal

email: sb-lgyve6555691@personal.example.com
password: L:94I&uW

### Business

sb-qk0bw6555151@business.example.com
password: g.X$/0rf
