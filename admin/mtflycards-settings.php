<div class="wrap">
	<?php screen_icon(); ?>
	<h2>MT Flycards Settings</h2>
	<div class="widget-liquid-left">
		<div class="widgets-left">
			<form method="post" action="options.php">
				<?php
				// This prints out all hidden setting fields
				settings_fields('mtflycards_options');
				do_settings_sections('edit_mtflycards_settings');
				?>
			</form>
		</div>
	</div>
	<div class="widgets-right">
		<table>
			<tr>
				<td style="padding: 0px 10px 10px"><p
						style="background-color: #e8e8e8; padding: 1px; text-indent: 2px;">
						<strong>Donations:</strong>
					</p>
					<p>MT Flycards is provided to you free of charge, to help me
						keep it updated make a donation</p>
					<div style="text-align: center">
						<form action="https://www.paypal.com/cgi-bin/webscr" method="post">
							<input type="hidden" name="cmd" value="_s-xclick" /> <input
								type="hidden" name="encrypted"
								value="-----BEGIN PKCS7-----MIIHLwYJKoZIhvcNAQcEoIIHIDCCBxwCAQExggEwMIIBLAIBADCBlDCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb20CAQAwDQYJKoZIhvcNAQEBBQAEgYAn+bJhjRPd6AaVYCyY/mXI0V0sDVYzayhhipaJwEjTdEHRsHXo+YBMvzvQbEE02VoVkdtjhPcojTNU4fzkho4ctsNTDzz9dgiKjcB11YKzsQvOvLuMRA3GNrdTC70Aaj/cj9H6QmGes8o6dlYiwHXUvJhjzCKqECgXbpoEebFntjELMAkGBSsOAwIaBQAwgawGCSqGSIb3DQEHATAUBggqhkiG9w0DBwQIl30TbXsQkHiAgYhaP49MYb+dEiHjVuVZ613lwuFMN+aeKxSTGK9z1Wh7mSutxSDh+rtCs4POQzfJ9ectlvnvQXcYn9ra2FTRA4HJ+0VcPkzpGJ+jiyIJRRG1rMqQPw1ItahshfvbajoYHjyYZwinoarp8D6MhVN79OBeX4S4d3EbYrz2XbiOxBVLqL7XO5yZXw3NoIIDhzCCA4MwggLsoAMCAQICAQAwDQYJKoZIhvcNAQEFBQAwgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tMB4XDTA0MDIxMzEwMTMxNVoXDTM1MDIxMzEwMTMxNVowgY4xCzAJBgNVBAYTAlVTMQswCQYDVQQIEwJDQTEWMBQGA1UEBxMNTW91bnRhaW4gVmlldzEUMBIGA1UEChMLUGF5UGFsIEluYy4xEzARBgNVBAsUCmxpdmVfY2VydHMxETAPBgNVBAMUCGxpdmVfYXBpMRwwGgYJKoZIhvcNAQkBFg1yZUBwYXlwYWwuY29tMIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDBR07d/ETMS1ycjtkpkvjXZe9k+6CieLuLsPumsJ7QC1odNz3sJiCbs2wC0nLE0uLGaEtXynIgRqIddYCHx88pb5HTXv4SZeuv0Rqq4+axW9PLAAATU8w04qqjaSXgbGLP3NmohqM6bV9kZZwZLR/klDaQGo1u9uDb9lr4Yn+rBQIDAQABo4HuMIHrMB0GA1UdDgQWBBSWn3y7xm8XvVk/UtcKG+wQ1mSUazCBuwYDVR0jBIGzMIGwgBSWn3y7xm8XvVk/UtcKG+wQ1mSUa6GBlKSBkTCBjjELMAkGA1UEBhMCVVMxCzAJBgNVBAgTAkNBMRYwFAYDVQQHEw1Nb3VudGFpbiBWaWV3MRQwEgYDVQQKEwtQYXlQYWwgSW5jLjETMBEGA1UECxQKbGl2ZV9jZXJ0czERMA8GA1UEAxQIbGl2ZV9hcGkxHDAaBgkqhkiG9w0BCQEWDXJlQHBheXBhbC5jb22CAQAwDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQUFAAOBgQCBXzpWmoBa5e9fo6ujionW1hUhPkOBakTr3YCDjbYfvJEiv/2P+IobhOGJr85+XHhN0v4gUkEDI8r2/rNk1m0GA8HKddvTjyGw/XqXa+LSTlDYkqI8OwR8GEYj4efEtcRpRYBxV8KxAW93YDWzFGvruKnnLbDAF6VR5w/cCMn5hzGCAZowggGWAgEBMIGUMIGOMQswCQYDVQQGEwJVUzELMAkGA1UECBMCQ0ExFjAUBgNVBAcTDU1vdW50YWluIFZpZXcxFDASBgNVBAoTC1BheVBhbCBJbmMuMRMwEQYDVQQLFApsaXZlX2NlcnRzMREwDwYDVQQDFAhsaXZlX2FwaTEcMBoGCSqGSIb3DQEJARYNcmVAcGF5cGFsLmNvbQIBADAJBgUrDgMCGgUAoF0wGAYJKoZIhvcNAQkDMQsGCSqGSIb3DQEHATAcBgkqhkiG9w0BCQUxDxcNMTMwMjA3MTUyMTE2WjAjBgkqhkiG9w0BCQQxFgQUAjkWrMTvk6semfY27LQGKy+7qzowDQYJKoZIhvcNAQEBBQAEgYBJhc5hSBBt9j2s8coTAX9HFFy+PEVH49mOsdif+myEAlE59jKQgn5ee/0/RA52+LGQdxWh3I6GpaQ6B3hSjMwnOPlaoqAS6A0tOAJkbAH17urdrYGB6HVyZ+LAuWES3rJyMAO5yVF6f+dUFMm71Bz6TWFJyo83bK81LKMAFUMGqQ==-----END PKCS7----- " />
							<input type="image"
								alt="PayPal — The safer, easier way to pay online."
								name="submit"
								src="https://www.paypalobjects.com/en_US/GB/i/btn/btn_donateCC_LG.gif" />
							<img alt=""
								src="https://www.paypalobjects.com/it_IT/i/scr/pixel.gif"
								width="1" height="1" border="0" />
						</form>
					</div></td>
			</tr>
			<tr>
				<td style="padding: 0px 10px 10px"><p
						style="background-color: #e8e8e8; padding: 1px; text-indent: 2px;">
						<strong>Plugin Information:</strong>
					</p>
					<p>
						MT Flycards v.
						<?php echo $this->version;?>
						was developed by Marco Tomaselli and released under GPL license,
						you can contact me by email at sys@dynup.org.
					</p>
					<p>
						You can contribute to the development of the plugin at <a
							href="https://github.com/dynuporg/mtflycards">https://github.com/dynuporg/mtflycards</a>
					</p>
					<p>
						Please visit our site for support and documentation of MT
						Flycards. <a href="http://mtflycards.dynup.org">mtflycards.dynup.org</a>
					</p>
					<p>If you'd like to translate this plugin, the language files
						are in the languages folder of this plugin. Please email any
						translations to languages@mtflycards.dynup.org and we'll
						incorporate it into the plugin in the future release.</p></td>
			</tr>
		</table>
	</div>
</div>
<?php $error=get_settings_errors('mtflycards_options');
wp_enqueue_style( 'farbtastic' );
wp_enqueue_script( 'farbtastic');
?>
<script type="text/javascript">
jQuery(document).ready(function($) {
var error;
var array_error=new Array(<?php echo json_encode($error);?>);
$('#restore_defaults').click(function(){
return confirm('<?php _e('Are you sure restore defaults settings of MT Flycards ?', 'flycards')?>')?true:false;
});
$.each(array_error,function(key,value){
$.each(value,function(key,value){
error=value;
error?$error=$('#'+error.code):false;
error.type=='error'?$error.next('p').html(error.message).css('color','red'):false;
});});
/*farbastic color picker*/
$.each(['#bgc_product_img','#bgc_excerpt','#fg_excerpt','#star_color'],function(i,v){
var $this=$(v);
var $farb=$this.next().next().hide();
$farb.farbtastic(v);
$this.click(function(){
$farb.fadeIn();
}); 
$this.focusout(function(){
$farb.each(function(){
var display=$(this).css('display');
if (display == 'block')
$(this).fadeOut();
});});});});
</script>

