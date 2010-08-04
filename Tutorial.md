# Plugin Structure

    // Initializing plugin functions…
    add_action( 'init',        'register_product_posttype');
    add_action( 'init',        'register_product_category_taxonomy');
    add_action( 'init',        'add_plugin_capabilities');
    add_action( 'admin_menu',  'register_meta_boxes');
    add_action( 'save_post',    'product_save' );

    // Register and defines custom taxonomies, in this case: ‘Product Categories’, similar to Post Categories.
    function register_product_category_taxonomies(){};

    // Register and defined custom post type, in this case: ‘Product Post Type’, similar to regular posts.
    function register_product_posttypes(){};

    // Creates custom capabilities for the ‘administrator’ role to manage products and product categories
    function add_plugin_capabilities(){};

    // For a product we need to enter additional fields, like the price. We can use custom fields or add additional form fields to the product editing page using the register_meta_boxes() function.
    function register_meta_boxes(){};

    // Holds the HTML for the custom meta box (the price field)
    function product_meta_box($post_id){};

    //Grabs the custom metabox POST data (the price field’s value) and saves it to DB
    function product_save($post_id){};


# General Information

- You can create custom post types with the register_post_type() function.
- You can create custom taxonomies with the register_taxonomy() function.
- You can define custom post types either in the functions.php file in your theme directory or in a plugin.
- In this tutorial we will create a plugin to demonstrate custom post types, taxonomies and capabilities.

# Initializing the Plugin.
- Create a file in wp-content/plugins/ named products.php (wp-content/plugins/products.php);
- Add some basic plugin info:
    <?php
        /*
        Plugin Name: Products Plugin
        Description: Adds Product Post Type
        */
    ?>

- Add initialization functions:
    <?php

        // Register and defined custom post type.
        // In this case: ‘Product Post Type’, similar to regular posts.
        add_action( 'init',        'register_product_posttype');

        // Register and defines custom taxonomies.
        // In this case: ‘Product Categories’, similar to Post Categories.
        add_action( 'init',        'register_product_category_taxonomy');

        // Creates custom capabilities for the ‘administrator’ role to manage products and product categories
        add_action( 'init',        'add_plugin_capabilities');

        // For a product we need to enter additional fields, like the price.
        // We can use custom fields or add additional form fields to the product editing page using the register_meta_boxes() function.
        add_action( 'admin_menu',  'register_meta_boxes');

        //Grabs the custom metabox POST data (the price field’s value) and saves it to DB
        add_action( 'save_post',    'product_save' );
    ?>
# Creating the product post type

# Creating the product categories taxonomy

# Adding product specific capabilities

# Adding additional fields to the product editing page

