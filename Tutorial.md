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

    // For a product we need to enter additional fields, like the price. 
    // We can use custom fields or add additional form fields to the 
    // product editing page using the register_meta_boxes() function.
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

* Next we register the new post type like so: 
        <?php
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
        ?>

## register_post_type() breakdown:

**register_post_type(__$post_type__, __$arguments__);**

### Parameters:
* `$post_type` is the handle used by Wordpress to identify this post type. It can also be used to add custom taxonomies or additional fields or meta boxes to the post type's editing page.
* `$arguments` contains an array of arguments, which defines the post type's behavior, from text that appears in menus and editing forms to capabilities and editor features.

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

**'hierarchical' => false,**

* `hierarchical` TRUE/FALSE - Whether the post type is hierarchical. Allows Parent to be specified. In this case, the products are not hierarhical.

**'taxonomies' => array('product_category'),**

* An array of registered taxonomies that will be used with this post type. 
* This can be use instead of calling [register_taxonomy_for_object_type()](http://codex.wordpress.org/Function_Reference/register_taxonomy_for_object_type) directly. 
* Taxonomies still need to be registered with [register_taxonomy()](http://codex.wordpress.org/Function_Reference/register_taxonomy).
* In our case we assign the `product_category` taxonomy to the `product` post type.

# Creating the product categories taxonomy

* Next we register the new post type like so:

        <?php

        function register_product_category_taxonomy(){

                register_taxonomy( 'product_category', 'product' ,array(                            
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
        };

        ?>

## register_taxonomy() breakdown:

**register_taxonomy(__$taxonomy__, __$object_type__, $arguments);**

### Parameters:

* `$taxonomy` -  The name of the taxonomy. 

* `$object_type` - Name of the object type for the taxonomy object. In our case `$object_type` is **product** (the handle of our custom post type).

* `$arguments` - contains an array of arguments, which defines the taxonomy behavior, from text that appears in menus and editing forms to capabilities and editor features.


### Arguments:

**'labels' => array()**

* An array of labels for this taxonomy. By default tag labels are used for non-hierarchical types and category labels for hierarchical ones. 

* For more information [visit the Wordpress register_taxonomy() page](http://codex.wordpress.org/Function_Reference/register_taxonomy#Arguments).

**'hierarchical' => true**

* `hierarchical` sets wether this taxonomy is hierarchical (have descendants) like categories or not hierarchical like tags. In our case, the product categories taxonomy is hierarchical.

**'capabilities' => array()***

*  _(array) (optional)_ An array of the capabilities for this taxonomy. You can define specific capabilities for editing or deleting a taxonomies as [seen here](http://codex.wordpress.org/Function_Reference/register_taxonomy#Arguments). We used one capability to handle all actions. By default the *term* capabilities are used, meaning that any user that can edit categories or tags, can also edit product categories. [More Info](http://codex.wordpress.org/Function_Reference/register_taxonomy#Arguments)

# Adding product specific capabilities

If you add custom capabilities to your post types or taxonomies, you must add those capabilities to a user role. To understand how Roles and Capabilities work, please read the Wordpress Codex Page for [Roles and Capabilities](http://codex.wordpress.org/Roles_and_Capabilities).

The code for our plugin is:

        function add_plugin_capabilities(){

            // We are adding our capabilities to the administrator role, 
            // meaning only the admin can edit products or product categories.

            // get the administrator role, so we can add our capabilities.
            $role = get_role('administrator');

            // We create an array which contain our custom capabilities;
            $caplist = array(
                'manage_products',
                'manage_product_categories',
            );

            // Next, we add each capability to the 'administrator'.
            foreach($caplist as $cap){
                if( ! $role->has_cap($cap)){
                    $role->add_cap($cap);
                }
            }
        }

# Adding additional fields to the product editing page

## Registering Meta Boxes

* We need a 'product details' section in the product post type editor.
* We can create this section using `add_meta_box()`;
* The `add_meta_box()` function was introduced in Version 2.5. It allows plugin developers to add sections to the Write Post, Write Page, and Write Link editing pages. Now, we can add these sections to our custom post type.

### Usage:

<?php add_meta_box( $id, $title, $callback, $page, $context, $priority, $callback_args ); ?> 

**Parameters:**

`$id` - HTML 'id' attribute of the edit screen section 

`$title` - Title of the edit screen section, visible to user 

`$callback` - Function that prints out the HTML for the edit screen section. 

`$page` - The type of Write screen on which to show the edit screen section ('post', 'page', 'link', or 'custom_post_type') 

`$context` - The part of the page where the edit screen section should be shown ('normal', 'advanced', or 'side') 

`$priority` - The priority within the context where the boxes should show ('high' or 'low') 

`$callback_args` -  Arguments to pass into your callback function. The callback will receive the $post object and whatever parameters are passed through this variable. 

### Our Plugin Code:

        function register_meta_boxes(){        
                add_meta_box('product_meta_box' ,'Product Details','product_meta_box' ,'product','normal','high' );
        }

* The code above creates a meta box called **product_meta_box**
* It sets the title for the box to **'Product details'**
* Calls the **product_meta_box** function which renders the HTML form fields used to enter the product's price.
* Adds the meta box to the **product** post type editing screen
* Adds the meta box to the **normal** screen section (this means under the Wordpress editor).
* Sets the priority of the box to **high**, so it should appear directly under the 'editor', and not lower.

## Adding the meta box form fields

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
`global $post;` - include the $post variable, we need the post id so we can retrieve the current post's meta data (or custom fields).

`$nonce_value = wp_create_nonce( plugin_basename(__FILE__) );` - we must create a unique nonce field so we can validate the form data when saving the product.

`$formdata = get_post_meta($post->ID, '_product_meta');` - retrieve any saved values in our post meta field / custom field called '_product_meta'.

## Saving the meta box form values

         function product_save($post_id){

         // verify this came from our editing screen and with proper authorization, 
         // because save_post can be triggered at other times
          if ( !wp_verify_nonce( $_POST['my_nonce'], plugin_basename(__FILE__) )) {

            return $post_id;
          }
         
          // verify if this is an auto save routine. 
          // If our form has not been submitted, we dont want to do anything
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
          
          // If this is the first time when we save the data, add the post's custom field:
          if(get_post_meta($post_id, '_product_meta') == ""){

              add_post_meta($post_id, '_product_meta', $data);

          // If the custom field already exist, update it's value.
          }elseif($data != get_post_meta($post_id, '_product_meta', true)){

              update_post_meta($post_id, '_product_meta', $data);

          }

    }

# Screenshots

![Editing a product](http://github.com/dsbecmedia/WP3-Tutorial/raw/master/screenshots/scr_product_editing.jpg)

![Product Listing](http://github.com/dsbecmedia/WP3-Tutorial/raw/master/screenshots/scr_product_list.jpg)

![Product Categories](http://github.com/dsbecmedia/WP3-Tutorial/raw/master/screenshots/scr_product_categories.jpg)