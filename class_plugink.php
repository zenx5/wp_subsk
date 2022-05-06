<?php
if (!class_exists('PluginK')) {
    class PluginK
    {
        public $name_plugin;
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

        public static function create_meta($type, $metas)
        {
            foreach ($metas as $meta) {
                add_meta_box('id_' . $meta['title'], $meta['title'], $meta['render_callback'], $type, 'normal', 'high');
            }
        }

        public static function set_var_meta($post_id, $vars, $values = [])
        {
            foreach ($vars as $var) {
                if (isset($_POST[$var])) {
                    if (count($value) == 0) {
                        update_post_meta($post_id, $var, $_POST[$var]);
                    } else {
                        update_post_meta($post_id, $var, $values[$var]);
                    }
                }
            }
        }


        public static function get_var_meta($name, $post_id = null)
        {
            if (!$post_id) {
                $post_id = get_the_ID();
            }
            $metas = get_post_meta($post_id);
            if (isset($metas[$name])) {
                $meta = $metas[$name];
                if (count($meta) > 1) {
                    return $meta;
                } else {
                    return $meta[0];
                }
            }
            return '';
        }


        public static function create_db($tables, $pk)
        {
            global $wpdb;
            $sql = "";
            foreach ($tables as $tableName => $table) {
                $sql = "CREATE TABLE IF NOT EXISTS `{$wpdb->prefix}$tableName ` (";
                foreach ($table as $field => $description) {
                    $sql .= " `$field` $description, ";
                }
                $sql .= "PRIMARY KEY (`" . $pk[$tableName] . "`)) ENGINE = InnoDB";
            }
            $wpdb->get_results($sql);
        }

        public static function get_post_name($post_type, $id)
        {
            $post = new WP_Query([
                'p' => $id,
                'post_type' => $post_type
            ]);

            return $post->post->post_name;
        }


        public static function get_template($template_dir)
        {
            return __DIR__ . '/' . $template_dir;
        }
    }
}
