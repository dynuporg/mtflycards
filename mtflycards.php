<?php
/*
Plugin Name: MT Flycards Standard Edition
Plugin URI: http://mtflycards.dynup.org
Description: this plugin teming shop page of woocommerce 2.0.10 and later
Author: Marco Tomaselli at sys@dynup.org
Version: 1.1.bs
Author URI: http://mtflycards.dynup.org/
*/

//check if woocommerce is active
If (in_array('woocommerce/woocommerce.php', apply_filters('active_plugins', get_option('active_plugins')))) {

	if (!class_exists('MT_Flycards')) {

		class MT_Flycards{

			var $version="1.1.bs";
			var $plugin_path;
			var $plugin_url;
			var $options=array();
			var $defaults=array();
			var $message=array();
			var $js_message=array();
			var $error=array();
			var $easing=array();
			var $mtflycards_animation_time_range=array();
			var $mtflycards_product_excerpt_len_range=array();
			var $mtflycards_products_per_page_range=array();
			var $mtflycards_skin=array();
			var $is_theme_compat=false;


			public function  __construct(){

				//version
				define('FLYCARDS_VERSION', $this->version );
				//locale
				$this->load_plugin_textdomain();
				//check theme compatibility
				$this->is_theme_compat= $this->check_theme_compat();
				// create custom plugin settings menu
				add_action('admin_menu', array(&$this,'create_menu'));
				add_action('admin_init', array(&$this, 'register_settings'));
				//init
				add_action( 'init' , array(&$this, 'core' ));
				//hook
				do_action('after_mtflycards_core');
				//hook wp_enqueue_scripts
				add_action( 'wp_enqueue_scripts', array(&$this, 'scripts'));
				//hook styles
				add_action('wp_print_styles', array(&$this,'styles'),90);
				//hook
				do_action('after_mtflycards_head');
				//init globals
				$this->register_globals();
				//hook
				do_action('mtflycards_loaded');

			}
			
			function core(){

				//check if existsts wrapper files for current wordpress theme
				$this->check_theme_compat(); 
				//defaults options
				apply_filters('mtflycards_defaults', $this->defaults=array(

				'skin'=>'cupertino',
				'animation' => 'swing',
				'animation_time' => 450,
				'infinitescroll' =>1,
				'ajax_page_load' =>1,
				'show_catsf_tab'=>1,
				'show_tagsf_tab'=>1,
				'pe_image_overlay'=>1,
				'product_excerpt_len'=>100,
				'products_per_page'=>6,
				'bgc_product_img'=>'#ffffff',
				'bgc_excerpt'=>'#f2f5f7',
				'fg_excerpt'=>'#362b3a',
				'card_width'=>'180px',
				'card_height'=>'237px',
				'star_color'=>'#fff1a0'


						));

				//valid range
				apply_filters('mtflycards_animation_time_range',$this->mtflycards_animation_time_range=array(200,800));
				apply_filters('mtflycards_product_excerpt_len_range',$this->mtflycards_product_excerpt_len_range=array(10,1000));
				apply_filters('mtflycards_products_per_page_range',$this->mtflycards_products_per_page_range=array(1,100));

				//message for js script
				apply_filters('mtflycards_js_message', $this->js_message=array(
				'js_loading'=>__('Loading','flycards'),
				'js_wait'=>__('Please wait ..','flycards'),
				'js_loading_next_page'=>__('Loading next page','flycards'),
				'js_no_more_pages'=>__('No more pages','flycards')
				));

				//message
				apply_filters('mtflycards_message', $this->message=array(
				'min_max_range'=>__('min: %d max: %d','flycards'),
				'card_dimension_type'=>__('valid value are in px, em, percent','flycards'),
				'restore_defaults'=>__('All settings restored to its defaults','flycards'),
				'mtflycards_settings_sa'=>__('<p style="width:400px">Select jQuery-UI theme and animation type</p>','flycards'),
				'mtflycards_settings_theme'=>__('<p>Select compatibility for your current active wordpress theme.</p>','flycards'),
				'mtflycards_settings_ajax'=>__('<p>Activate or disable ajax and infinetscroll </p>','flycards'),
				'mtflycards_settings_parts_prop'=>__('<p>Product summary length, width and higth of cards, show products per page</p>','flycards'),
				'mtflycards_settings_parts'=>__('<p>Product excerpt over images, category tab filters, tags tab filters </p>','flycards'),
				'mtflycards_settings_colors'=>__('<p>Can choose color for non ui widget parts</p>','flycards')
				));

				//error
				apply_filters('mtflycards_error', $this->error=array(
				'not_theme_compatible' => sprintf(__('Sorry %s theme is not currently supported by MT Flycards','flycards'),get_option('template')),
				'animation_time'=> sprintf(__('Duration as ms is invalid or not in valid range (min:%d max:%d), previous valid value restored','flycards'),$this->mtflycards_animation_time_range[0],$this->mtflycards_animation_time_range[1]),
				'product_excerpt_len'=> sprintf(__('Product excerpt length is invalid or not in valid range (min:%d max:%d), previous valid value restored','flycards'),$this->mtflycards_product_excerpt_len_range[0],$this->mtflycards_product_excerpt_len_range[1]),
				'products_per_page'=> sprintf(__('Products per page is invalid or not in valid range (min:%d max:%d), previous valid value restored','flycards'),$this->mtflycards_products_per_page_range[0],$this->mtflycards_products_per_page_range[1]),
				'bgc_product_img'=>sprintf(__('Please insert valid hex color for background of product image, only accept hex color','flycards')),
				'bgc_excerpt'=>sprintf(__('Please insert valid hex color for background of product excerpt, previous valid value restored','flycards')),
				'star_color'=>sprintf(__('Please insert valid hex color for star rating, previous valid value restored','flycards')),
				'fg_excerpt'=>sprintf(__('Please insert valid hex color for text of product excerpt,  previous valid value restored','flycards')),
				'card_width'=>sprintf(__('No valid value inserted for card width valid value are in px , em, percent, previous valid value restored','flycards')),
				'card_height'=>sprintf(__('No valid value inserted for card height valid value are in px, em, percent, previous valid value restored','flycards'))
				));

				//easing
				apply_filters('mtflycards_animation_type', $this->easing=array(
				'easeOutQuad',
				'swing',
				'easeInQuad',
				'easeOutQuad',
				'easeInOutQuad',
				'easeInCubic',
				'easeOutCubic',
				'easeInOutCubic',
				'easeInQuart',
				'easeOutQuart',
				'easeInOutQuart',
				'easeInQuint',
				'easeOutQuint',
				'easeInOutQuint',
				'easeInSine',
				'easeOutSine',
				'easeInOutSine',
				'easeInExpo',
				'easeOutExpo',
				'easeInOutExpo',
				'easeInCirc',
				'easeOutCirc',
				'easeInOutCirc',
				'easeInElastic',
				'easeOutElastic',
				'easeInOutElastic',
				'easeOutBack',
				'easeInOutBack',
				'easeInBounce',
				'easeOutBounce',
				'easeInOutBounce'

						));

				//jquery-ui themes 1.9.2
				apply_filters('mtflycards_skin', $this->mtflycards_skin=array(
				'base',
				'black-tie',
				'blitzer',
				'cupertino',
				'dark-hive',
				'dot-luv',
				'eggplant',
				'excite-bike',
				'flick',
				'hot-sneaks',
				'humanity',
				'le-frog',
				'mint-choc',
				'overcast',
				'pepper-grinder',
				'redmond',
				'smoothness',
				'south-street',
				'start',
				'sunny',
				'swanky-purse',
				'trontastic',
				'ui-darkness',
				'ui-lightness',
				'vader'
						));

				//init options
				$this->init_options();
				
				if (!$this->is_theme_compat) return;
				//functions
				include_once $this->plugin_path() . '/mtflycards-functions.php';
				//after init hooks
				include_once $this->plugin_path() . '/mtflycards-hooks.php';

			}
				
			

			function scripts(){


				if (!$this->is_theme_compat && $this->is_target_page()
				) return;

				/*
				 * jquery scripts / plugin
				********************************************************************************************************************/
				wp_register_script('jquery-infinitescroll', $this->plugin_url() . '/assets/js/jquery.infinitescroll.js', array('jquery'), '2.0b2.120519', true);
				wp_register_script('jquery-easing', $this->plugin_url() . '/assets/js/jquery.easing.js', array('jquery'), '1.3', true);
				wp_register_script('jquery-metadata', $this->plugin_url() . '/assets/js/jquery.metadata.js', array('jquery'), true);
				wp_register_script('jquery-imageoverlay', $this->plugin_url() . '/assets/js/jquery.imageoverlay.js', array('jquery'), '1.3.2', true);
				wp_register_script('jquery-flycards', $this->plugin_url() . '/assets/js/jquery.flycards.js', array('jquery','woocommerce'), '1.0.b', true);
				wp_register_script('jquery-flycards-boot', $this->plugin_url() . '/assets/js/jquery.flycards.boot.js', array('jquery','jquery-flycards'), '1.0.b', true);

				//load masonry included in wordpress
				wp_enqueue_script('jquery-masonry',null,array('jquery'),null,true);
				//load jquery infinitescroll
				wp_enqueue_script('jquery-infinitescroll');
				//load jquery easing
				wp_enqueue_script('jquery-easing');
				//load jquery blockui
				wp_enqueue_script('jquery-blockui',null,array('jquery'),null,true);
				//load jquery ui.tabs and button and accordion and position
				wp_enqueue_script('jquery-ui-tabs',null,array('jquery','jquery-ui-core','jquery-ui-widget'),null,true);
				wp_enqueue_script('jquery-ui-button',null,array('jquery','jquery-ui-core','jquery-ui-widget'),null,true);
				//load jquery imageoverlay and metadata
				wp_enqueue_script('jquery-metadata');
				wp_enqueue_script('jquery-imageoverlay');
				//load jquery flycards
				wp_enqueue_script('jquery-flycards');
				//load jquery flycards boot
				wp_enqueue_script('jquery-flycards-boot');
				//passing options to jquery.flycards
				wp_localize_script( 'jquery-flycards', 'mtflycards', array_merge($this->get_options(),$this->js_message));
			}
				
			function styles(){

				if (!$this->is_theme_compat && $this->is_target_page()
				) return;

				// load jQuery-UI  theme
				wp_enqueue_style($this->options['skin'],$this->plugin_url() . '/assets/jquery-ui-themes/themes/'.$this->options['skin'].'/jquery-ui.css');

				// load mtflycards styles
				wp_enqueue_style('mtflycards-core',$this->plugin_url() . '/assets/css/mtflycards-core.css');

			}

			function create_menu() {

				//add menu to settings menu item
				add_options_page(__('MT Flycards Settings','flycards'), 'MT Flycards', 'manage_options', 'edit_mtflycards_settings', array(&$this, 'settings_page'));

			}

			function register_settings() {

				register_setting('mtflycards_options', 'mtflycards_options', array(&$this, 'validate_options'));
				
				if(!$this->is_theme_compat)
					add_settings_error('mtflycards_options', 'setting-error-settings_updated', $this->error['not_theme_compatible'],'error');

				//skin & animation settings
				add_settings_section(
				'mtflycards_settings_sa',
				__('jQuery-UI Theme and Animation','flycards'),
				array(&$this,'desc_mtflycards_settings_sa'),
				'edit_mtflycards_settings'
				);



				add_settings_field(
				'skin',
				__('Theme','flycards'),
				array(&$this, 'input_select_field'),
				'edit_mtflycards_settings',
				'mtflycards_settings_sa',
				array(
				'id'=>'skin',
				'ar'=>$this->mtflycards_skin)
				);

				add_settings_field(
				'animation',
				__('Animation type','flycards'),
				array(&$this, 'input_select_field'),
				'edit_mtflycards_settings',
				'mtflycards_settings_sa',
				array(
				'id'=>'animation',
				'ar'=>$this->easing
				)
				);

				add_settings_field(
				'animation_time',
				__('Duration as ms','flycards'),
				array(&$this, 'input_text_field'),
				'edit_mtflycards_settings',
				'mtflycards_settings_sa',
				array(
				'id'=>'animation_time',
				'size'=>'5',
				'text'=>"<p class='description'>".sprintf($this->message['min_max_range'],$this->mtflycards_animation_time_range[0],$this->mtflycards_animation_time_range[1])."</p>"
						)
				);

				//ajax catalog page load section
				add_settings_section(
				'mtflycards_settings_ajax',
				__('Enable or disable Ajax Plugins ','flycards'),
				array(&$this,'desc_mtflycards_settings_ajax'),
				'edit_mtflycards_settings'
				);

				add_settings_field(
				'ajax_page_load',
				__('Shop page loading by ajax','flycards'),
				array(&$this, 'input_checkbox_field'),
				'edit_mtflycards_settings',
				'mtflycards_settings_ajax',
				'ajax_page_load'
						);

				add_settings_field(
				'infinitescroll',
				__('Infinite scroll','flycards'),
				array(&$this, 'input_checkbox_field'),
				'edit_mtflycards_settings',
				'mtflycards_settings_ajax',
				'infinitescroll'
						);

				//show or hide parts section
				add_settings_section(
				'mtflycards_settings_parts',
				__('Show or hide parts','flycards'),
				array(&$this,'desc_mtflycards_settings_parts'),
				'edit_mtflycards_settings'
				);

				add_settings_field(
				'show_catsf_tab',
				__('Show Categories filter tab','flycards'),
				array(&$this, 'input_checkbox_field'),
				'edit_mtflycards_settings',
				'mtflycards_settings_parts',
				'show_catsf_tab'
						);

				add_settings_field(
				'show_tagsf_tab',
				__('Show Tags filter tab','flycards'),
				array(&$this, 'input_checkbox_field'),
				'edit_mtflycards_settings',
				'mtflycards_settings_parts',
				'show_tagsf_tab'
						);


				add_settings_field(
				'pe_image_overlay',
				__('Show product excerpt over image','flycards'),
				array(&$this, 'input_checkbox_field'),
				'edit_mtflycards_settings',
				'mtflycards_settings_parts',
				'pe_image_overlay'
						);

				//parts properties section
				add_settings_section(
				'mtflycards_settings_parts_prop',
				__('Parts properties','flycards'),
				array(&$this,'desc_mtflycards_settings_parts_prop'),
				'edit_mtflycards_settings'
				);

				add_settings_field(
				'product_excerpt_len',
				__('Product excerpt length','flycards'),
				array(&$this, 'input_text_field'),
				'edit_mtflycards_settings',
				'mtflycards_settings_parts_prop',
				array(
				'id'=>'product_excerpt_len',
				'size'=>'5',
				'text'=>"<p class='description'>".sprintf($this->message['min_max_range'],$this->mtflycards_product_excerpt_len_range[0],$this->mtflycards_product_excerpt_len_range[1])."</p>"
						)
				);

				add_settings_field(
				'products_per_page',
				__('Products per page','flycards'),
				array(&$this, 'input_text_field'),
				'edit_mtflycards_settings',
				'mtflycards_settings_parts_prop',
				array(
				'id'=>'products_per_page',
				'size'=>'5',
				'text'=>"<p class='description'>".sprintf($this->message['min_max_range'],$this->mtflycards_products_per_page_range[0],$this->mtflycards_products_per_page_range[1])."</p>"
						)
				);

				add_settings_field(
				'card_width',
				__('Card width','flycards'),
				array(&$this, 'input_text_field'),
				'edit_mtflycards_settings',
				'mtflycards_settings_parts_prop',
				array(
				'id'=>'card_width',
				'size'=>'10',
				'text'=>"<p class='description'>".$this->message['card_dimension_type']."</p>"
						)
				);

				add_settings_field(
				'card_height',
				__('Card height','flycards'),
				array(&$this, 'input_text_field'),
				'edit_mtflycards_settings',
				'mtflycards_settings_parts_prop',
				array(
				'id'=>'card_height',
				'size'=>'10',
				'text'=>"<p class='description'>".$this->message['card_dimension_type']."</p>"
						)
				);


				//colors section
				add_settings_section(
				'mtflycards_settings_colors',
				__('Colors','flycards'),
				array(&$this,'desc_mtflycards_colors'),
				'edit_mtflycards_settings'
				);


				add_settings_field(
				'bgc_product_img',
				__('Product image background color','flycards'),
				array(&$this, 'input_text_field'),
				'edit_mtflycards_settings',
				'mtflycards_settings_colors',
				array(
				'id'=>'bgc_product_img',
				'size'=>'10',
				'text'=>"<p class='description'></p><div></div>"
						)
				);

				add_settings_field(
				'bgc_excerpt',
				__('Product excerpt background color','flycards'),
				array(&$this, 'input_text_field'),
				'edit_mtflycards_settings',
				'mtflycards_settings_colors',
				array(
				'id'=>'bgc_excerpt',
				'size'=>'10',
				'text'=>"<p class='description'></p><div></div>"
						)
				);

				add_settings_field(
				'fg_excerpt',
				__('Product excerpt text color','flycards'),
				array(&$this, 'input_text_field'),
				'edit_mtflycards_settings',
				'mtflycards_settings_colors',
				array(
				'id'=>'fg_excerpt',
				'size'=>'10',
				'text'=>"<p class='description'></p><div></div>"
						)
				);

				add_settings_field(
				'star_color',
				__('Star rating color','flycards'),
				array(&$this, 'input_text_field'),
				'edit_mtflycards_settings',
				'mtflycards_settings_colors',
				array(
				'id'=>'star_color',
				'size'=>'10',
				'text'=>"<p class='description'></p><div></div>"
						)
				);


				//submit and reset
				add_settings_section(
				'mtflycards_sr',
				null,
				array(&$this,'input_submit'),
				'edit_mtflycards_settings'
				);

			}

			function input_text_field($args, $size='', $text=''){

				extract($args);
				if( !isset($id)) return;
				$options = $this->get_options();
				$input= "<input id='{$id}' name='mtflycards_options[{$id}]' size='{$size}' type='text' value='{$options[$id]}' />";
				echo $input.=$text;
					
			}

			function input_checkbox_field($id){

				if(!isset($id)) return;
				$options = $this->get_options();
				$checked= checked(1,$options[$id], false);
				echo "<input id='{$id}' name='mtflycards_options[{$id}]' value='1' type='checkbox'  {$checked} />";
			}

			function input_select_field($args){

				extract($args);
				if( !isset($id) || ! is_array($ar)) return;
				$options=$this->get_options();
				sort($ar);
				$select="<select id='{$id}' name='mtflycards_options[{$id}]'>";
				foreach ($ar as $el){
					if(is_dir($el) || is_file($el)) $el=basename($el);
					$selected=selected($options[$id],$el,false);
					$select.= "<option value='{$el}' {$selected}>{$el}</option>";
				}
				echo $select.="</select>";

			}

			//section callback
			function input_submit(){

				echo "<p class='submit'>"
						.sprintf("<input type='submit' id='submit' name='submit' class='button button-primary' value='%s' />",__('Save Changes','flycards'))
						.sprintf("<input style='margin-left:10px' type='submit' id='restore_defaults' name='restore_defaults' class='button button-secondary' value='%s' />",__('Restore Defaults','flycards'))
						."</p>";

			}


			/*
			 * Description of settings section
			*/

			function desc_mtflycards_colors(){
					
				echo $this->message['mtflycards_settings_colors'];
					
			}


			function desc_mtflycards_settings_sa(){

				echo $this->message['mtflycards_settings_sa'];

			}

			function desc_mtflycards_settings_theme(){

				echo $this->message['mtflycards_settings_theme'];

			}

			function desc_mtflycards_settings_ajax(){

				echo $this->message['mtflycards_settings_ajax'];

			}

			function desc_mtflycards_settings_parts(){

				echo $this->message['mtflycards_settings_parts'];

			}

			function desc_mtflycards_settings_parts_prop(){

				echo $this->message['mtflycards_settings_parts_prop'];

			}

			//validate options
			function validate_options($options){
				
				
			    

				//restore to defaults confirmed
				if($_POST['restore_defaults']) {
					add_settings_error('mtflycards_options', 'setting-error-settings_updated', $this->message['restore_defaults'],'updated');
					return $this->defaults;
				}

				//set for restore value if error occurs
				$old_options=$this->get_options();

				if(!$this->is_digits_range($options['animation_time'],$this->mtflycards_animation_time_range)){
					add_settings_error('mtflycards_options', 'animation_time', $this->error['animation_time'],'error');
					$options['animation_time']=$old_options['animation_time'];
				}
				if(!$this->is_digits_range($options['product_excerpt_len'],$this->mtflycards_product_excerpt_len_range)){
					add_settings_error('mtflycards_options', 'product_excerpt_len', $this->error['product_excerpt_len'],'error');
					$options['product_excerpt_len']=$old_options['product_excerpt_len'];
				}
				if(!$this->is_digits_range($options['products_per_page'],$this->mtflycards_products_per_page_range)){
					add_settings_error('mtflycards_options', 'products_per_page', $this->error['products_per_page'],'error');
					$options['products_per_page']=$old_options['products_per_page'];
				}
				if(!$this->is_hex_color($options['bgc_product_img'])){
					add_settings_error('mtflycards_options', 'bgc_product_img', $this->error['bgc_product_img'],'error');
					$options['bgc_product_img']=$old_options['bgc_product_img'];
				}
				if(!$this->is_hex_color($options['bgc_excerpt'])){
					add_settings_error('mtflycards_options', 'bgc_excerpt', $this->error['bgc_excerpt'],'error');
					$options['bgc_excerpt']=$old_options['bgc_excerpt'];
				}
				if(!$this->is_hex_color($options['fg_excerpt'])){
					add_settings_error('mtflycards_options', 'fg_excerpt', $this->error['fg_excerpt'],'error');
					$options['fg_excerpt']=$old_options['fg_excerpt'];
				}
				if(!$this->is_card_dimension($options['card_width'])){
					add_settings_error('mtflycards_options', 'card_width', $this->error['card_width'],'error');
					$options['card_width']=$old_options['card_width'];
				}
				if(!$this->is_card_dimension($options['card_height'])){
					add_settings_error('mtflycards_options', 'card_height', $this->error['card_height'],'error');
					$options['card_height']=$old_options['card_height'];
				}
				if(!$this->is_hex_color($options['star_color'])){
					add_settings_error('mtflycards_options', 'star_color', $this->error['star_color'],'error');
					$options['star_color']=$old_options['star_color'];
				}

				return $options;

			}

			//helper validate int range
			function is_digits_range($input, $range) {
				return preg_match ("/^[0-9][0-9]*$/", $input) && (int)$input>=$range[0] && (int)$input<=$range[1] ;
			}

			//helper validate hex color
			function is_hex_color($input){
				return preg_match("/^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/", $input);
			}

			//helper validate card dimension
			function is_card_dimension($input){

				return preg_match("/^[0-9]+\.?([0-9]+)?(px|em|%)$/",$input);
			}
			
			//check target page
			function is_target_page(){
				return (is_shop()
						|| is_product_category()
						|| is_product_tag()
						|| is_single()
						|| is_cart()
				)?true:false;
			}
			
			//check if exists compatibility for current active wordpress theme
			function check_theme_compat(){
				$check_compat=false;
				$template=get_option('template');
				$compat=$this->plugin_path().'/compat/'.$template.'/';
				if(is_dir($compat)
				&& is_file($compat.'wrapper-start.php')
				&& is_file($compat.'wrapper-end.php'))
					$check_compat=true;
				return $check_compat;
			
			}


			//settings page
			function settings_page(){

				include_once $this->plugin_path().'/admin/mtflycards-settings.php';
					
			}

			//this plugin path with untrailingslash
			function plugin_path(){
					
				if($this->plugin_path) return $this->plugin_path;
				return $this->plugin_path=untrailingslashit(plugin_dir_path(__FILE__));
					
			}

			function plugin_url(){
					
				if($this->plugin_url) return $this->plugin_url;
				return $this->plugin_url=plugins_url( basename( plugin_dir_path(__FILE__) ), basename( __FILE__ ) );
					
			}

			function register_globals(){
					
				$GLOBALS['page_categories']=null;
				$GLOBALS['page_tags']=null;
					
			}

			function load_plugin_textdomain(){
					
				load_plugin_textdomain('flycards',false,dirname( plugin_basename( __FILE__ ) ).'/languages');
					
			}


			function get_options(){
					
				if($this->options) return $this->options;
				return $this->options= get_option('mtflycards_options');
					
			}


			function init_options(){
					
				if(!get_option('mtflycards_options'))
					add_option('mtflycards_options',$this->defaults);
					
			}
		}

		$GLOBALS['mtflycards'] = new MT_Flycards();
	}
}