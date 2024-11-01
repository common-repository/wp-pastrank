<?php

$wpconfig = realpath("../../../wp-config.php");
if (!file_exists($wpconfig))  {
	echo "Could not found wp-config.php. Error in path :\n\n".$wpconfig ;
	die;
}// stop when wp-config is not there

require_once($wpconfig);
require_once(ABSPATH.'/wp-admin/admin.php');
// check for rights
if(!current_user_can('edit_posts')) die;

global $wpprPlugin;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>
<?php _e("Add pastrank chart to your posts", "wppr")?>
</title>
<style type="text/css">
<!--
body {
	font-size: 12px;
	margin: 0px;
	padding: 0px;
}
-->
</style>
<?php 
	wp_print_scripts("jquery");
?>
<script language="javascript" type="text/javascript" src="<?php echo $wpprPlugin->staticsPath.'main.js' ?>"></script>
<script language="javascript" type="text/javascript" src="<?php echo $wpprPlugin->staticsPath.'admin.js' ?>"></script>
<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/tiny_mce_popup.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/mctabs.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo get_option('siteurl') ?>/wp-includes/js/tinymce/utils/form_utils.js"></script>
<script language="javascript" type="text/javascript" src="<?php echo WSAP_URLPATH ?>tinymce3/tinymce.js"></script>
<link href="<?php echo $wpprPlugin->staticsPath.'admin.css' ?>" rel="stylesheet" type="text/css" />
</head>
<body>
<script language="javascript">
	$().ready(function(){
		initPastrankEditor();
	});
</script>
<ul class="wpprPostEditor">
  <li><span class="editorCaption">
    <?php _e("domain", "wppr")?>
    </span>
    <input type="text" id="txtSites" value="<?php echo $wpprPlugin->options["siteName"]?>" />
  </li>
  <li><span class="editorCaption">
    <?php _e("width/height", "wppr")?>
    </span>
    <input name="txtWidth" type="text" id="txtWidth" value="100%" size="5" />
    /
    <input name="txtHeight" type="text" id="txtHeight" value="300px" size="5" />
    <?php _e("px or %", "wppr")?></li>
  <li><span class="editorCaption">
    <?php _e("date range", "wppr")?>
    </span>
    <input name="txtStartDate" type="text" id="txtStartDate" size="10" />
    -
    <input name="txtToDate" type="text" id="txtToDate" size="10" />
    yyyy-MM-dd</li>
   <li><span class="editorCaption"><?php _e("show first&last datas", "wppr")?></span>
   <input type="checkbox" id="chkGetMinMax" checked="checked"/>
   </li> 
  <li><span class="editorCaption">
    <?php _e("chart type", "wppr")?>
    </span>
    <select  id="selChartType">
      <option value="flash">
      <?php _e("flash(using FusionCharts)", "wppr")?>
      </option>
      <!--
      <option value="image">
      <?php _e("image(using Google chart)", "wppr")?>
      </option>
      -->
    </select>
  </li>
  <li><span class="editorCaption">
    <?php _e("show type", "wppr")?>
    </span>
    <select id="wpprShowType">
      <option value="search">
      <?php _e("indexed by search engines", "wppr")?>
      </option>
      <option value="linkin">
      <?php _e("link in", "wppr")?>
      </option>
      <option value="alexa" selected="selected">
      <?php _e("alexa traffic rank", "wppr")?>
      </option>
    </select>
    <select id="wpprSearchOption" style="display:none;">
      <option value="c" selected="selected">
      <?php _e("all", "wppr")?>
      </option>
      <option value="ct">
      <?php _e("today", "wppr")?>
      </option>
      <option value="cw">
      <?php _e("weekly", "wppr")?>
      </option>
      <option value="cm">
      <?php _e("monthly", "wppr")?>
      </option>
      <option value="cy">
      <?php _e("yearly", "wppr")?>
      </option>
    </select>
  </li>
  <li style="text-align: right;"><span id="wpprLinkinAlexa" style="display:none;" >
    <input type="checkbox" value="alexa" checked="checked"/>
    Alexa</span><span id="wpprSearchList" style="display:none;"></span>
    <div id="wpprAlexaList">
      <input type="checkbox" value="ya" />
      <label color="FF00FF"><?php _e("yesterday", "wppr")?></label>
      <input type="checkbox" value="wa" checked="checked"/>
      <label color="00FFFF"><?php _e("7 day", "wppr")?></label>
      <input type="checkbox" value="ma"/>
      <label color="FF00FF"><?php _e("1 month", "wppr")?></label>
      <input type="checkbox" value="ta" checked="checked"/>
      <label color="FFFF00"><?php _e("3 month", "wppr")?></label>
    </div>
  </li>
  <li><span class="editorCaption">&nbsp;</span>
    <input type="submit" name="button" id="button" value="<?php _e("submit", "wppr")?>" onclick="insertPastrank()" />
  </li>
</ul>
</body>
</html>
