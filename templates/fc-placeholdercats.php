<?php
/*
 * MT Flycards
* placeholder container for categories link
*/
global $page_categories;
$cats='';
if (isset($page_categories) && count($page_categories)>0)
	$cats=implode(' ',array_unique($page_categories));
?>
<div id="fc-phcats">
	<div id="fc-phcats-content">
		<?php if ( '' != $cats) echo $cats;?>
	</div>
</div>
