<?php
/*
 * MT Flycards
 * copyright 2013 Marco Tomaselli sys@dynup.org
 */

//product meta
function mtflycards_meta() {
	global $product, $page_categories, $page_tags;
	$categories[] = array();
	$tags[] = array();
	$meta = '';
	$categories[] = explode(', ', strip_tags(trim($product -> get_categories())));
	$tags[] = explode(', ', strip_tags(trim($product -> get_tags())));
	foreach ($categories as $catsgroup) {
		foreach ($catsgroup as $key => $value) {
			$sanitize_value = preg_replace("/\W+/", '_', 'cat_' . strtolower($value));
			$page_categories[] = '<a href="#" ' . 'data-filter=".' . $sanitize_value . '">' . $value . '</a>';
			$meta .= $sanitize_value . ' ';
		}
	}
	foreach ($tags as $tagsgroup) {
		foreach ($tagsgroup as $key => $value) {
			$sanitize_value = preg_replace("/\W+/", '_', 'tag_' . strtolower($value));
			$page_tags[] = '<a href="#" ' . 'data-filter=".' . $sanitize_value . '">' . $value . '</a>';
			$meta .= $sanitize_value . ' ';
		}
	}

	return $meta;
}

//cart link
function woocommerce_header_add_to_cart_fragment( $fragments ) {
	global $woocommerce;
	ob_start();
	?>
<a class="fc-cart-contents"
	href="<?php echo $woocommerce->cart->get_cart_url(); ?>"
	title="<?php _e('View your shopping cart', 'flycards'); ?>"><img
	class="ui-icon ui-icon-cart" /> <?php echo sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'flycards'), $woocommerce->cart->cart_contents_count);?>
	- <?php echo $woocommerce->cart->get_cart_total(); ?> </a>
<?php
$fragments['a.fc-cart-contents'] = ob_get_clean();
return $fragments;
}

// filter - product short description
function mtflycards_product_excerpt($excerpt) {
	$overlay_len = apply_filters('mtflycards_strlen_overlay', mtflycards_get_option('product_excerpt_len'));
	$excerpt = (strlen(strip_tags($excerpt)) <= $overlay_len) ? strip_tags($excerpt) : substr(strip_tags($excerpt), 0, $overlay_len) . ' ' . __('[More..]', 'flycards');
	return '<div class="caption"><p>' . $excerpt . '</p></div>';
}

// template loader
function mtflycards_get_template($template) {
	woocommerce_get_template($template, FALSE, FALSE, mtflycards_get_plugin_path() . '/templates/');
}

// template loader
function mtflycards_get_theme_compat($template) {
$compat=mtflycards_get_plugin_path() . '/compat/' . get_option('template') . '/';
if (is_dir($compat)
&& is_file($compat.'wrapper-start.php')
&& is_file($compat.'wrapper-end.php')
)
	woocommerce_get_template($template, FALSE, FALSE, mtflycards_get_plugin_path() . '/compat/' . get_option('template') . '/');
}

// helper - plugin path loader
function mtflycards_get_plugin_path(){
	global $mtflycards;
	return $url=$mtflycards->plugin_path();
}

// helper - plugin url loader
function mtflycards_get_plugin_url(){
	global $mtflycards;
	return $path=$mtflycards->plugin_url();
}

//helper - get mtflycards_options
function mtflycards_get_options(){
	global $mtflycards;
	return $mtflycards->get_options();
}

//helper - get mtflycards_option
function mtflycards_get_option($text){
	global $mtflycards;
	$options= $mtflycards->get_options();
	return $options[$text];
}






