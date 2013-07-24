<?php
/*
 * MT Flycards
 * placeholder for tags link
 */
if ( ! defined( 'ABSPATH' ) ) exit;
global $page_tags;
$tags_nav_filter='';
if (isset($page_tags) && count($page_tags)>0)
	$tags=implode(' ',array_unique($page_tags));
?>
<div id="fc-phtags">
<div id="fc-phtags-content">
	<?php if ( '' != $tags) echo $tags;?>
	</div>
</div>
