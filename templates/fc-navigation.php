<?php
/*
 * Mt Flycards
 * tabbed navigation
 */
if ( ! defined( 'ABSPATH' ) ) exit;
class MT_Accordion_Walker extends Walker_Category {
	function start_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= "<ul class='fc-menu-children'>";
	}
	function end_lvl( &$output, $depth = 0, $args = array() ) {
		$output .= "</ul></li>";
	}
	function start_el( &$output, $category, $depth = 0, $args = array(), $id = 0 ) {
		extract($args);

		$cat_name = esc_attr( $category->name );
		$cat_name = apply_filters( 'list_cats', $cat_name, $category );

		$link = '<a href="'. esc_url( get_term_link($category) ) . '" ';
		if ( $use_desc_for_title == 0 || empty($category->description) )
			$link .= 'title="' . esc_attr( sprintf(__( 'View all products filed under %s','flycards' ), $cat_name) ) . '"';
		else
			$link .= 'title="' . esc_attr( strip_tags( apply_filters( 'category_description', $category->description, $category ) ) ) . '"';
		$link .= '>';

		if(!$has_children){
			$link .= "<span class='ui-icon ui-icon-carat-1-e fc-menu-child-icon'></span><span>".$cat_name.'</span></a>';
			$output.="<li class='fc-menu fc-menu-child'>".$link.'</li>';
		}
		else{
			$link.="<span class='ui-icon ui-icon-triangle-1-e fc-menu-head-icon'></span><span>".$cat_name.'</span></a>';
			$output.="<li class='fc-menu fc-menu-head'>".$link."";
		}

	}
	function end_el( &$output, $page, $depth = 0, $args = array() ) {
		return;
	}
}
$args = array(
		'style' => '',
		'show_option_all'    => '',
		'orderby'            => 'slug',
		'order'              => 'ASC',
		'taxonomy'           => 'product_cat',
		'echo'	=>1,
		'hierarchical'       => 1,
		'walker'             => new MT_Accordion_Walker()
);
?>
<div class="fc-tabs-spacer"></div>
<div id="fc-nav">
	<div id="fc-bar">
		<ul>
			<li><a href="#fc-search"><?php echo __('Search','flycards');?> </a></li>
			<li><a href="#fc-ordering"><?php echo __('Ordering','flycards');?> </a>
			</li>
			<li><a class="fc-cart-contents"></a></li>
		</ul>
		<div id="fc-ordering">
			<?php woocommerce_catalog_ordering();?>
		</div>
		<div id="fc-search">
			<?php get_product_search_form();?>
		</div>
	</div>
	<div class="fc-tabs-spacer"></div>
	<div  class="ui-widget-content ui-corner-all">
	<div id="fc-tabs-cat-subcat"></div>
	</div>
	<div class="fc-tabs-spacer"></div>
	<div id="fc-tabs">
		<ul>
			<li><a href="#fc-tabs-cat"><?php echo __('Shop Menu','flycards');?> </a>
			</li>
			<li><a
				title="<?php echo __('Categories present on this page','flycards');?>"
				href="#fc-tabs-catfilters"><?php echo __('Page Categories','flycards');?>
			</a></li>
			<li><a
				title="<?php echo __('Keywords present on this page','flycards');?>"
				href="#fc-tabs-tagfilters"><?php echo __('Page Tags','flycards');?>
			</a>
			</li>
			<li><a href="#fc-tabs-rviewed"><?php echo __('Viewed','flycards');?>
			</a></li>
			<li><a href="#fc-tabs-onsale"><?php echo __('On Sale','flycards');?>
			</a></li>
			<li><a href="#fc-tabs-bests"><?php echo __('Best Sellers','flycards');?>
			</a></li>
		</ul>
		<div id="fc-tabs-cat" class="ui-helper-clearfix">
			<div id="fc-tabs-cat-accordion">
				<ul class="fc-accordion">
					<?php wp_list_categories($args); ?>
				</ul>
			</div>

		</div>
		<div id="fc-tabs-catfilters"></div>
		<div id="fc-tabs-tagfilters"></div>
		<div id="fc-tabs-rviewed">
			<?php the_widget('WC_Widget_Recently_Viewed','title= ');?>
		</div>
		<div id="fc-tabs-onsale">
			<?php the_widget('WC_Widget_Onsale','title= ');?>
		</div>
		<div id="fc-tabs-bests">
			<?php the_widget('WC_Widget_Best_Sellers','title= ');?>
		</div>
	</div>

</div>
<div class="fc-tabs-spacer"></div>
<div class="fc-tabs-spacer"></div>

