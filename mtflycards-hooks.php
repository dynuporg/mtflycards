<?php
/*
 * MT Flycards Standard Edition
* copyright 2013 Marco Tomaselli sys@dynup.org
*/

/**
 * FILTERS
 *****************************************************************************************************/

//card totals
add_filter('add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');
//activate product excerpt
add_filter('woocommerce_short_description', 'mtflycards_product_excerpt');

//product per page
add_filter( 'loop_shop_per_page', create_function( '$cols', 'return mtflycards_get_option("products_per_page");' ), 20 );




/**
 * ACTIONS
 ******************************************************************************************************/

/*
 * woocommerce_before_shop_loop
 * @hooked woocommerce_catalog_ordering - 30
 */
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count',20);
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );
remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display');

add_action('woocommerce_before_shop_loop',function(){mtflycards_get_template('fc-navigation.php');},40);
add_action('woocommerce_before_shop_loop',function(){mtflycards_get_template('fc-wrap-cards-start.php');},50);

//woocommerce_before_subcategory
add_action('woocommerce_before_subcategory', function(){mtflycards_get_template('fc-wrap-cat-start.php');},10);

//woocommerce_after_subcategory
add_action('woocommerce_after_subcategory', function(){mtflycards_get_template('fc-wrap-cat-end.php');},10);

//woocommerce_before_shop_loop_item
add_action('woocommerce_before_shop_loop_item', function(){mtflycards_get_template('fc-wrap-card-start.php');},10);

//woocommerce_before_shop_loop_item_title
add_action('woocommerce_before_shop_loop_item_title', 'woocommerce_template_single_excerpt', 10);

//woocommerce_after_shop_loop_item
//add_action('woocommerce_after_shop_loop_item', 'mtflycards_rated', 10);
add_action('woocommerce_after_shop_loop_item', function(){mtflycards_get_template('fc-wrap-card-end.php');},20);

//woocommerce_after_shop_loop
add_action('woocommerce_after_shop_loop',function(){mtflycards_get_template('fc-wrap-cards-end.php');},5);
add_action('woocommerce_after_shop_loop', function(){mtflycards_get_template('fc-wrap-pagination-start.php');},6);
add_action('woocommerce_after_shop_loop', function(){mtflycards_get_template('fc-wrap-pagination-end.php');},11);
add_action('woocommerce_after_shop_loop', function(){mtflycards_get_template('fc-placeholdercats.php');},25);
add_action('woocommerce_after_shop_loop', function(){mtflycards_get_template('fc-placeholdertags.php');},30);

/*
 * woocommerce_after_single_product_summary hook
 *
 * @hooked woocommerce_output_product_data_tabs - 10
 * @hooked woocommerce_output_upsell_products - 15
 * @hooked woocommerce_output_related_products - 20
 */
add_action('woocommerce_after_single_product_summary', function(){mtflycards_get_template('fc-wrap-single-start.php');},14);
add_action('woocommerce_after_single_product_summary', function(){mtflycards_get_template('fc-wrap-single-end.php');},16);
add_action('woocommerce_after_single_product_summary', function(){mtflycards_get_template('fc-wrap-single-start.php');},19);
add_action('woocommerce_after_single_product_summary', function(){mtflycards_get_template('fc-wrap-single-end.php');},21);


/*
 * theme compatibility
 */

remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
add_action( 'woocommerce_before_main_content', function(){mtflycards_get_template('fc-wrap-breadcrumbs-start.php');}, 19);
add_action( 'woocommerce_before_main_content', function(){mtflycards_get_template('fc-wrap-breadcrumbs-end.php');}, 21);
add_action('woocommerce_before_main_content', function(){mtflycards_get_theme_compat('wrapper-start.php');}, 10);
add_action('woocommerce_after_main_content', function(){mtflycards_get_theme_compat('wrapper-end.php');}, 10);
 












