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

- You can create custom post types with the [register_post_type()](http://codex.wordpress.org/Function_Reference/register_post_type) function.
- You can create custom taxonomies with the [register_taxonomy()](http://codex.wordpress.org/Function_Reference/register_taxonomy) function.
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
        // We can use custom fields or add additional form fields to the product editing page
        // using the register_meta_boxes() function.
        add_action( 'admin_menu',  'register_meta_boxes');

        //Grabs the custom metabox POST data (the price field’s value) and saves it to DB
        add_action( 'save_post',    'product_save' );

        ?>

# Creating the product post type

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

                        'query_var' => true,
                        'rewrite' => true,
                        'hierarchical' => false,

                        'taxonomies' => array('product_category'),
                        )
            );
        }

## register_post_type() breakdown:

`register_post_type($post_type, $args);`

### Parameters:
* `$post_type` is the handle used by Wordpress to identify this post type. It can also be used to add custom taxonomies or additional fields or meta boxes to the post type's editing page.
* `$args` contains an array of arguments, which defines the post type's behavior, from text that appears in menus and editing forms to capabilities and editor features.

### Arguments:

**labels**

* _(optional)_ An array of labels for this post type. Basicaly these labels are used as text in the admin area, in the post type's editing pages. [More Info](http://codex.wordpress.org/Function_Reference/register_post_type#Arguments)

**description**

* _(optional)_ A short descriptive summary of what the post type is.

**capabilities**

* _(array) (optional)_ An array of the capabilities for this post type. You can define specific capabilities for editing, deleting or publishing a post type as [seen here](http://codex.wordpress.org/Function_Reference/register_post_type#Arguments). We used one capability to handle all actions. By default the *post* capabilities are used, meaning that any user that can edit a post, can also edit a product. [More Info](http://codex.wordpress.org/Function_Reference/register_post_type#Arguments)

**supports**

* _(array) (optional)_ Tells the post type to use WordPress features like a title field, editor or post thumbnail.
* **valid arguments:**
    * **'title'**
    * **'editor'** (content)
    * **'author'**
    * **'thumbnail'** (featured image) (current theme must also support post-thumbnails)
    * **'excerpt'**
    * **'trackbacks'**
    * **'custom-fields'**
    * **'comments'** (also will see comment count balloon on edit screen)
    * **'revisions'** (will store revisions)
    * **'page-attributes'** (template and menu order) (hierarchical must be true)
    * [More Info](http://codex.wordpress.org/Function_Reference/register_post_type#Arguments)

**'publicly_queryable' => true,**

* Product queries can be performed from the front end. Important if you want to create a products listing page.

**'exclude_from_search' => false,**

* Include products in search results.

**'show_in_nav_menus'  => true,**

* Product post type is available for selection in navigation menus.

**'show_ui' => true,**

* Generate a default UI for managing products.

**'menu_position' => 5,**

* The position in the menu order the post type should appear.
* Default: null - defaults to below Comments
* 5 - below Posts
* 10 - below Media
* 20 - below Pages

# Creating the product categories taxonomy

        <?php

        function register_product_category_taxonomy(){

                register_taxonomy(
                        'product_category',
                        array( 'product' ),
                        array(
                            'public' => true,
                            'show_ui' => true,
                            'hierarchical' => true,
                            '_builtin'     => false,
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

        };

        ?>

# Adding product specific capabilities

# Adding additional fields to the product editing page

