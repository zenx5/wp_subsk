# BH_Subsk

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

- BH_Subsk_content_before
- BH_Subsk_content_after
- BH_Subsk_cost_before
- BH_Subsk_cost_after
- BH_Subsk_period_before
- BH_Subsk_period_after

## Filters:

- BH_Subsk_currency
- BH_Subsk_period
- BH_Subsk_period_min
- BH_Subsk_period_max
- BH_Subsk_period_step
- BH_Subsk_period_format
- BH_Subsk_cost
- BH_Subsk_cost_min
- BH_Subsk_cost_max
- BH_Subsk_cost_step
