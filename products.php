<?php
/*
Plugin Name: Product Post Type
Plugin URI: http://wordpress.org/#
Description: Product Post Type
Author: Dima Stelian
Version: 1.0.0
Author URI: #
*/

    
add_action( 'init',        'register_product_posttype');
add_action( 'init',        'register_product_category_taxonomy');
add_action( 'init',        'add_plugin_capabilities');

add_action( 'admin_menu',  'register_meta_boxes');
add_action( 'save_post',    'product_save' );




function register_product_category_taxonomy(){

    register_taxonomy(
            'product_category',
            array( 'product' ),
            array(
       
                'hierarchical' => true,
                'capabilities' => array(
                        'manage_terms' => 'manage_product_categories', 
                        ),
                'labels' => array(
                    'name' => 'Product Categories',
                    'singular_name' => 'Product Cateogry',
                    'search_items' => 'Search Product Categories',
                    'popular_items' => 'Popular Product Categories',
                    'all_items' => 'All Product Categories',
                    'parent_item' => 'Parent Product Category',
                    'parent_item_colon' => 'Parent Product Category',
                    'edit_item' => 'Edit Product Category',
                    'update_item' => 'Edit Product Category',
                    'add_new_item' => 'Add New Category',
                    'new_item_name' => 'New Product Category',
                    'separate_items_with_commas' => 'separate product categories with commas',
                    'add_or_remove_items' => 'add or remove product categories',
                    'choose_from_most_used' => 'choose from most used',
                ),
           )
    );

}

function register_product_posttype(){

    register_post_type('product', array(

                'labels' =>  array(
                    'name' => 'Products',
                    'singular_name' => 'Product',                    
                    'add_new' => 'Add New',
                    'add_new_item' => 'Add New Product',
                    'edit_item' => 'Edit Product',
                    'new_item' => 'New Product',
                    'view_item' => 'View Product',
                    'search_items' => 'Search Products',
                    'not_found' =>  'No products found',
                    'not_found_in_trash' => 'No products found in Trash',
                    'parent_item_colon' => 'Parent Product'
                ),

                'description'   => 'Product custom post type description',
                'capabilities'   => array(
                        'manage_products',
                        ),

                'supports' => array('title','editor','thumbnail','custom','comments'),

                'publicly_queryable' => true,
                'exclude_from_search' => false,
                'show_in_nav_menus'  => true,
                'show_ui' => true,
                'menu_position' => 5,
                'hierarchical' => false,                
                
                'taxonomies' => array('product_category'),                
                )
    );


}

    function add_plugin_capabilities(){

        $role = get_role('administrator');

        $caplist = array(
            'manage_products',
            'manage_product_categories',
        );

        foreach($caplist as $cap){
            if( ! $role->has_cap($cap)){
                $role->add_cap($cap);
            }
        }
    }

function register_meta_boxes(){
      //add_meta_box($id, $title, $callback, $page, $context, $priority, $callback_args);
        add_meta_box('product_meta_box' ,'Product Details','product_meta_box' ,'product','normal','high' );
}

function product_meta_box($post_id){
    global $post;


        $nonce_value = wp_create_nonce( plugin_basename(__FILE__) );

        $formdata = get_post_meta($post->ID, '_product_meta');

        ?>
        <input type="hidden" name="my_nonce" id="myplugin_noncename" value="<?php echo $nonce_value; ?>" />

        <label for="product_price">Price</label>
        <input type="text" name="product_price" value="<?php echo $formdata[0]['product_price']?>" size="25" /> USD
        <?php


}

function product_save($post_id){


       
         // verify this came from the our screen and with proper authorization, because save_post can be triggered at other times
          if ( !wp_verify_nonce( $_POST['my_nonce'], plugin_basename(__FILE__) )) {
            

            return $post_id;
          }
         
          // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want to do anything
          if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
            return $post_id;


          // Check permissions
          if ( ( 'product' == $_POST['post_type'] ) && ( !current_user_can( 'manage_products', $post_id ) ) ) {
             return $post_id;

          }

          // OK, we're authenticated: we need to find and save the data

          $data = array(
                'product_price' => $_POST['product_price'],
          );
  
          
          if(get_post_meta($post_id, '_product_meta') == ""){

              add_post_meta($post_id, '_product_meta', $data);


          }elseif($data != get_post_meta($post_id, '_product_meta', true)){

              update_post_meta($post_id, '_product_meta', $data);

          }elseif( $data == "" ){

            delete_post_meta($post_id, '_product_meta', get_post_meta($post_id, '_product_meta'));

          }

    }



?>
