<?php

$sub = self::get_sub($post_id);

$name              = $sub->post_title;
$will_manage_stock = true;
$is_virtual        = true;
$price             = $_POST['BH_Subsk_cost'];
$is_on_sale        = true;
$sale_price        = $_POST['BH_Subsk_cost'];
$product           = new \WC_Product();
$image_id = 0; // Attachment ID
//$gallery  = [];
$product->set_props(array(
    'name'               => $name,
    'featured'           => false,
    'catalog_visibility' => 'visible',
    'description'        => 'Suscripcion: ' . $name,
    'short_description'  => '....',
    'sku'                => sanitize_title($name) . '-' . rand(0, 100), // Just an example
    'regular_price'      => $price,
    'sale_price'         => $sale_price,
    'date_on_sale_from'  => '',
    'date_on_sale_to'    => '',
    'total_sales'        => 0,
    'tax_status'         => 'taxable',
    'tax_class'          => '',
    'manage_stock'       => $will_manage_stock,
    'stock_quantity'     => $will_manage_stock ? 100 : null, // Stock quantity or null
    'stock_status'       => 'instock',
    'backorders'         => 'no',
    'sold_individually'  => true,
    'weight'             => $is_virtual ? '' : 15,
    'length'             => $is_virtual ? '' : 15,
    'width'              => $is_virtual ? '' : 15,
    'height'             => $is_virtual ? '' : 15,
    'upsell_ids'         => '',
    'cross_sell_ids'     => '',
    'parent_id'          => 0,
    'reviews_allowed'    => true,
    'purchase_note'      => '',
    'menu_order'         => 10,
    'virtual'            => $is_virtual,
    'downloadable'       => false,
    'category_ids'       => '',
    'tag_ids'            => '',
    'shipping_class_id'  => 0,
    'image_id'           => $image_id,
    //'gallery_image_ids'  => $gallery,
));
unset($_POST['BH_Subsk_create_product']);
$product->save();
