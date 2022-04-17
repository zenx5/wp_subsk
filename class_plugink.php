<?php


class PluginK
{
    public static function create_type_post($type_name, $singular, $plural, $data = [])
    {
        if (!post_type_exists('subs_types')) {
            $labels = [
                'name' => $plural,
                'singular_name' => $singular,
                'add_new' => 'Añadir nuevo',
                'add_new_item' => "Añadir nuevo $singular",
                'edit_item' => "Editar $singular",
                'featured_image' => 'Imagen destacada',
                'set_featured_image' => 'Establecer imagen destacada'
            ];
            $config = array(
                'label' => $labels['singular_name'],
                'labels' => $labels
            );
            foreach ($data as $key => $value) {
                $config[$key] = $value;
            }
            register_post_type($type_name, $config);
        }
    }

    // public static function create_meta($metas)
    // {
    //     //add_meta_box('id', 'title', )
    //     // foreach($metas as $key => $value){
    //     //     add_meta_box($key,);
    //     // }

    // }
}
