<?php

// Defines
define( 'FL_CHILD_THEME_DIR', get_stylesheet_directory() );
define( 'FL_CHILD_THEME_URL', get_stylesheet_directory_uri() );

// Classes
require_once 'classes/class-fl-child-theme.php';

// Actions
add_action( 'wp_enqueue_scripts', 'FLChildTheme::enqueue_scripts', 1000 );

//* Enqueue scripts and styles
add_action( 'wp_enqueue_scripts', 'parallax_enqueue_scripts_styles', 1000 );
function parallax_enqueue_scripts_styles() {
	// Styles
	wp_enqueue_style( 'custom', get_stylesheet_directory_uri() . '/style.css?v='.time(), array() );
	wp_enqueue_style( 'icomoon-fonts', get_stylesheet_directory_uri() . '/icomoon.css', array() );
	
}

// Removes Query Strings from scripts and styles
function remove_script_version( $src ){
  if ( strpos( $src, 'uploads/bb-plugin' ) !== false || strpos( $src, 'uploads/bb-theme' ) !== false ) {
    return $src;
  }
  else {
    $parts = explode( '?ver', $src );
    return $parts[0];
  }
}
add_filter( 'script_loader_src', 'remove_script_version', 15, 1 );
add_filter( 'style_loader_src', 'remove_script_version', 15, 1 );

//Remove Gutenberg Block Library CSS from loading on the frontend
function smartwp_remove_wp_block_library_css(){
    wp_dequeue_style( 'wp-block-library' );
    wp_dequeue_style( 'wp-block-library-theme' );
    wp_dequeue_style( 'wc-block-style' ); // Remove WooCommerce block CSS
    wp_enqueue_style( 'custom-fonts', get_stylesheet_directory_uri() . '/fonts/stylesheet.css', array() );
    //wp_enqueue_script( 'main', get_stylesheet_directory_uri() . '/main.js', array('jquery') );

} 
add_action( 'wp_enqueue_scripts', 'smartwp_remove_wp_block_library_css', 999 );

add_action( 'wp_enqueue_scripts', function() {
    wp_dequeue_style( 'font-awesome' ); // FontAwesome 4
    wp_enqueue_style( 'font-awesome-5' ); // FontAwesome 5

    wp_dequeue_style( 'jquery-magnificpopup' );
    wp_dequeue_script( 'jquery-magnificpopup' );

    wp_dequeue_script( 'bootstrap' );
//    wp_dequeue_script( 'imagesloaded' ); //Commented by Saqib on 11/16/21
    wp_dequeue_script( 'jquery-fitvids' );
//    wp_dequeue_script( 'jquery-throttle' ); //Commented by Saqib on 11/16/21
    wp_dequeue_script( 'jquery-waypoints' );
}, 9999 );

// Our custom post type function
function create_posttype() {
 
    register_post_type( 'Stories',
    // CPT Options
        array(
            'labels' => array(
                'name' => __( 'Stories' ),
                'singular_name' => __( 'Stories' )
            ),
            'public' => true,
            'has_archive' => true,
            'rewrite' => array('slug' => 'stories'),
            'show_in_rest' => true,
 
        )
    );
}
// Hooking up our function to theme setup
add_action( 'init', 'create_posttype' );

/*
* Creating a function to create our CPT
*/
 
function custom_post_type() {
 
// Set UI labels for Custom Post Type
    $labels = array(
        'name'                => _x( 'Stories', 'Post Type General Name', 'twentytwenty' ),
        'singular_name'       => _x( 'Stories', 'Post Type Singular Name', 'twentytwenty' ),
        'menu_name'           => __( 'Stories', 'twentytwenty' ),
        'parent_item_colon'   => __( 'Parent Stories', 'twentytwenty' ),
        'all_items'           => __( 'All Stories', 'twentytwenty' ),
        'view_item'           => __( 'View Stories', 'twentytwenty' ),
        'add_new_item'        => __( 'Add New Stories', 'twentytwenty' ),
        'add_new'             => __( 'Add New', 'twentytwenty' ),
        'edit_item'           => __( 'Edit Stories', 'twentytwenty' ),
        'update_item'         => __( 'Update Stories', 'twentytwenty' ),
        'search_items'        => __( 'Search Stories', 'twentytwenty' ),
        'not_found'           => __( 'Not Found', 'twentytwenty' ),
        'not_found_in_trash'  => __( 'Not found in Trash', 'twentytwenty' ),
    );
     
// Set other options for Custom Post Type
     
    $args = array(
        'label'               => __( 'stories', 'twentytwenty' ),
        'description'         => __( 'Stories', 'twentytwenty' ),
        'labels'              => $labels,
        // Features this CPT supports in Post Editor
        'supports'            => array( 'title', 'editor', 'excerpt', 'author', 'thumbnail', 'comments', 'revisions', 'custom-fields', ),
        // You can associate this CPT with a taxonomy or custom taxonomy. 
        'taxonomies'          => array( 'genres' ),
        /* A hierarchical CPT is like Pages and can have
        * Parent and child items. A non-hierarchical CPT
        * is like Posts.
        */ 
        'hierarchical'        => false,
        'public'              => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'show_in_nav_menus'   => true,
        'show_in_admin_bar'   => true,
        'menu_position'       => 5,
        'can_export'          => true,
        'has_archive'         => true,
        'exclude_from_search' => false,
        'publicly_queryable'  => true,
        'capability_type'     => 'post',
        'show_in_rest' => true,
 
    );
     
    // Registering your Custom Post Type
    register_post_type( 'stories', $args );
 
}
 
/* Hook into the 'init' action so that the function
* Containing our post type registration is not 
* unnecessarily executed. 
*/
 
add_action( 'init', 'custom_post_type', 0 );

add_action( 'pre_get_posts', 'add_my_post_types_to_query' );
 
function add_my_post_types_to_query( $query ) {
    if ( is_home() && $query->is_main_query() )
        $query->set( 'post_type', array( 'post', 'stories' ) );
    return $query;
}

/* Create Continue shopping Button after Add To Cart button */
function add_button_after_addtocart() {
    echo '<a href="/shop/" target="_self" class="fl-button continue-shopping-btn" role="button"><span class="fl-button-text">Continue Shopping</span></a>';
}
add_action( 'woocommerce_after_add_to_cart_button', 'add_button_after_addtocart', 10 );

/* Create Buy Now Button dynamically after Add To Cart button */
function add_content_after_addtocart() {
     
    // get the current post/product ID
    $current_product_id = get_the_ID();
 
    // get the product based on the ID
    $product = wc_get_product( $current_product_id );
 
    // get the "Checkout Page" URL
    $checkout_url = WC()->cart->get_checkout_url();
 
    // run only on simple products
    if( $product->is_type( 'simple' ) ){
        echo '<div class="clear-sec">';
        echo '</div>';
        echo '<a href="'.$checkout_url.'?add-to-cart='.$current_product_id.'" class="buy-now button">Buy Now</a>';
        //echo '<a href="'.$checkout_url.'" class="buy-now button">Buy Now</a>';
    }
}
add_action( 'woocommerce_after_add_to_cart_button', 'add_content_after_addtocart' );

function wpb_hook_javascript() {
    if (is_woocommerce ('') || is_page('18693') || is_page('18694') || is_page('18695')) { 
      ?>
          <script type="text/javascript" src="https://www.bugherd.com/sidebarv2.js?apikey=wybs4obpbdlgsyp9avuksa" async="true"></script>
      <?php
    }
  }
  add_action('wp_head', 'wpb_hook_javascript');


// function to change Flat Rate Shipping Label
function thrive_package_rates( $rates ) {
    foreach( $rates as &$rate ) {
        // Flat Rate Shipping Label when Flat Rate is $0.00
        if( $rate->get_method_id() == 'flat_rate' && floatval( $rate->get_cost() ) == 0.0 ) {
            $rate->set_label( __( "Free Shipping", 'woocommerce' ) );
        }
    }
 
    return $rates;
}
add_filter( 'woocommerce_package_rates', 'thrive_package_rates', 20);

/*Plus Minus Quantity Buttons on WooCommerce Product Page & Cart*/

// 1. Show plus minus buttons
  
add_action( 'woocommerce_after_quantity_input_field', 'display_quantity_plus' );
  
function display_quantity_plus() {
   echo '<button type="button" class="plus">+</button>';
}
  
add_action( 'woocommerce_before_quantity_input_field', 'display_quantity_minus' );
  
function display_quantity_minus() {
   echo '<button type="button" class="minus">-</button>';
}

// 2. Trigger update quantity script
  
add_action( 'wp_footer', 'add_cart_quantity_plus_minus' );
  
function add_cart_quantity_plus_minus() {
 
   if ( ! is_product() && ! is_cart() ) return;
    
   wc_enqueue_js( "   
           
      $(document).on( 'click', 'button.plus, button.minus', function() {
  
         var qty = $( this ).parent( '.quantity' ).find( '.qty' );
         var val = parseFloat(qty.val());
         var max = parseFloat(qty.attr( 'max' ));
         var min = parseFloat(qty.attr( 'min' ));
         var step = parseFloat(qty.attr( 'step' ));
 
         if ( $( this ).is( '.plus' ) ) {
            if ( max && ( max <= val ) ) {
               qty.val( max ).change();
            } else {
               qty.val( val + step ).change();
            }
         } else {
            if ( min && ( min >= val ) ) {
               qty.val( min ).change();
            } else if ( val > 1 ) {
               qty.val( val - step ).change();
            }
         }
 
      });
        
   " );
}