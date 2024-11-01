<?php
/*
Plugin Name: wp-pastrank
Plugin URI: http://www.pastrank.com/wp-pastrank.php
Description: You can add your blog to pastrank track queue by wp-pastrank, pastrank.com will track indaxed datas of different search engings and Alexa traffic rank. its founctions includes:1.view your analysis chart of domain datas 2.add pastrank chart to your posts 3. it will show the newest track datas of your blog or other domains on widget. more information about pastrank, please visit <a href="http://www.pastrank.com/">www.pastrank.com</a>
Author: PastRank
Version: 1.1.0
Author URI: http://www.pastrank.com/
*/
?>

<?php

//widget
class wp_pastrank_widget extends WP_Widget {
    function wp_pastrank_widget() {     
		load_plugin_textdomain('wppr', $path = 'wp-content/plugins/wp-pastrank/lang');
		//echo _e('Add PastRank chart to your widget','wppr');
		$widget_ops = array(			
			'description' => __('Show newest indexed datas by search engines & alexa traffic rank.','wppr')
		);
        parent::WP_Widget('Weather', $name = __('Pastrank widget'),$widget_ops);	
    }


    function widget($args, $instance) {
		GLOBAL $wpprPlugin;
    	extract($args);
		$domain = $instance['wpprWidgetDomain'];
		$id = $this->get_field_id('wpprWigetID');
		/*$my_Weather_content = '<div id="'.$id.'"><img src="'.$wpprPlugin->staticsPath.'103.gif" />'.__("Loading...",'wppr').'</div>
			<div class="wpprMore"><a href="http://www.pastrank.com/view.php?sites='.$domain
			.'" target="_blank" title="'.__('View more indexed datas by search engines & alexa traffic rank.', 'wppr').'">'.__('Provided by Pastrank', 'wppr').'</a></div>
			<script language="javascript">wpprGetLast("'.$domain.'", "#'.$id.'","'.$wpprPlugin->staticsPath.'");
			</script>';
		echo $before_widget.$before_title.'<h3>'.$instance['wpprWidgetTitle'].'</h3>'.
			$after_title.$my_Weather_content.$after_widget;
		*/
		echo $before_widget.$before_title.'<h3>'.$instance['wpprWidgetTitle'].'</h3>'.
			$after_title.$wpprPlugin->getWidget($domain, $id).$after_widget;
	}

    function update($new_instance, $old_instance) {	
		var_dump($new_instance);
        return $new_instance;
    }

    function form($instance) {
		GLOBAL $wpprPlugin;
		$domain = esc_attr($instance['wpprWidgetDomain']);
		if(!isset($domain) || $domain == "") $domain = $wpprPlugin->options["siteName"];
		$title = esc_attr($instance['wpprWidgetTitle']);
		echo '<ul class="wpprWidgetEditor">
		<li><label for="'.$this->get_field_id("wpprWidgetTitle").'">'.__("Title",'wppr').
		'<input type="text" value="'. $title.'" id="'.$this->get_field_id('wpprWidgetTitle').
		'" name="'.$this->get_field_name('wpprWidgetTitle').'" size="30" /></label></li>
		<li><label for="'.$this->get_field_id("wpprWidgetDomain").'">'.__("Domain name",'wppr').
		'<input type="text" value="'. $domain.'" id="'.$this->get_field_id('wpprWidgetDomain').
		'" name="'.$this->get_field_name('wpprWidgetDomain').'"  size="30"/></label></li></ul>';
    }
}

add_action('widgets_init', create_function('', 'return register_widget("wp_pastrank_widget");'));
?>
<?php
if(!class_exists("wp_pastrank_plugin"))
{
	class wp_pastrank_plugin{
		var $apiServer = "http://www.pastrank.com/api/";
		var $pluginPath, $staticsPath, $apiJsPath, $apiPath;
		var $optionName = "wp_pastrank_option";
		var $version = "1.0.0";
		var $options = array(
			"siteName"=>"www.pastrank.com",
			"showToPost"=>"1"
		);
		
		//Constructor
		function wp_pastrank_plugin(){
			load_plugin_textdomain('wppr', $path = 'wp-content/plugins/wp-pastrank/lang');
			$this->pluginPath  = get_option( 'siteurl' )."/wp-content/plugins/wp-pastrank/";
			$this->staticsPath = $this->pluginPath."statics/";
			$this->apiJsPath = $this->apiServer."api.js";
			$this->apiPath = $this->apiServer."api.php";
			$this->getChartPath = $this->apiServer."getchart.php";
			$this->regCSSAndScript();
			//注册JS文件及CSS文件			
			if($_POST["saveOption"] == "true")
			{
				$this->saveOption();
			}
			else
			{
				$myOptions = get_option($this->optionName);
				//get option from database
				if ( !empty ($myOptions)) {
					foreach ($myOptions as $key => $option)
						$this->options[$key] = $option;
				}
			}
			
			//echo $_POST["txtSiteName"];
		}
		
		function regCSSAndScript(){
			wp_register_script('wppr_main_js', $this->staticsPath.'main.js', array('jquery'), '1.0');
			wp_enqueue_script("wppr_main_js");
			if(is_admin() || $this->options["showToPost"] == "1"){
				wp_register_script('wppr_api_js', $this->apiJsPath, null, '1.0');
				wp_enqueue_script("wppr_api_js");
			}
			
			if(is_admin()){
				wp_register_style('wppr_admin_css', $this->staticsPath.'admin.css');
				wp_enqueue_style("wppr_admin_css");
				wp_register_script('wppr_admin_js', $this->staticsPath.'admin.js');
				wp_enqueue_script("wppr_admin_js");
			}else{
				wp_register_style('wppr_main_css', $this->staticsPath.'style.css');
				wp_enqueue_style("wppr_main_css");
			}
		}
		
		//save options to database
		function saveOption(){
			$this->options["siteName"] = $_POST["txtSiteName"];
			$this->options["showToPost"] = $_POST["chkShowInPost"] == "1" ? "1" : "0";
			update_option($this->optionName, $this->options); 
		}
		
		function contentHook(){
			//eregi_replace
			add_filter('the_content', 'showPastrank', 0);
			function showPastrank($content = ''){
				GLOBAL $wpprPlugin;
				//get rand id
				$replaceTo = '';
				$pattern = '/\[pastrank url="(.+?)"\s*id="(.+?)"\s*style="(.+?)"\]/i';
				if($wpprPlugin->options["showToPost"] == "1")
				{
					if(is_single())
						$replaceTo = '<div id="\2" style="\3"></div><script language="javascript" src="\\1&amp;id=\2"></script>';
					else
						$replaceTo = '';
				}
	
				$content = preg_replace($pattern, $replaceTo, $content);
				return $content;
			}
		}
		//add menu
		function addMenu(){
			// 添加管理菜单Hook 
			add_action('admin_menu', 'pastrank_menu');
			// action function for above hook
			function pastrank_menu() {
				// 在管理下，添加新的子菜单:
				add_management_page('wp-pastrank', 'wp-pastrank', 9, 'wp-pastrank', "wppr_pastrank_admin");
				add_submenu_page('index.php', __('My pastrank', "wppr"), __('My pastrank', "wppr"), 'manage_options', 'wppr_pastrank_mypastrank', 'wppr_pastrank_mypastrank');
			}
		}
		
		function showAdmin(){
			$admin = realpath(dirname(__FILE__)."\admin.php");
			require_once($admin);
			showAdminHtml();
		}
		
		
		function showMyPastrank(){
			$admin = realpath(dirname(__FILE__)."\admin.php");
			require_once($admin);
			showMyPastrankHtml();
		}
		
		function addPostScript(){
			//add_action('wp_head','momoPrintCSS');
			add_action("wp_head", "addPostScriptFunc");
			function addPostScriptFunc(){
				GLOBAL $wpprPlugin;
				echo '<script language="javascript">
				var PASTRANKOPTION = {
					fusionChartsJS: "'.$wpprPlugin->pluginPath.'statics/FusionCharts.js",
					lineSWF: "'.$wpprPlugin->pluginPath.'statics/MSLine.swf",
					inverseLineSWF: "'.$wpprPlugin->pluginPath.'statics/InverseMSLine.swf",
					onError: function(data){
						alert(data.description);
					}	
				};
				</script>';
			}
		}
		
		function showClientInfo(){
			echo '<iframe src="http://www.pastrank.com/client/wp-pastrank.php?version='
			.$this->version.'&lang='.WPLANG.'" width="100%" height="100px"></iframe>';
		}
		
		function getWidget($domain, $id = ''){
			if($id == '') $id = "pastrank_".rand(1000, 100000);
			$content = '<div id="'.$id.'"><img src="'.$this->staticsPath.'103.gif" />'.__("Loading...",'wppr').'</div>
			<div class="wpprMore"><a href="http://www.pastrank.com/view.php?sites='.$domain
			.'" target="_blank" title="'.__('View more indexed datas by search engines & alexa traffic rank.', 'wppr').
			'">'.__('Provided by Pastrank', 'wppr').'</a></div><script language="javascript">wpprGetLast("'.$domain.
			 '", "#'.$id.'","'.$wpprPlugin->staticsPath.'");</script>';
			return $content;
		}
	}
}


if(class_exists("wp_pastrank_plugin"))
{
	$wpprPlugin = new wp_pastrank_plugin();
	$wpprPlugin->addMenu();			//add menu
	$wpprPlugin->contentHook();		//ad hook
	if($wpprPlugin->options["showToPost"] = "1"){
		$wpprPlugin->addPostScript();
	}
	
	//
	register_activation_hook( __FILE__, 'wp_pastrank_activate' );
	function wp_pastrank_activate(){
		//activate event
	}
	//add menu
	function wppr_pastrank_admin(){
		GLOBAL $wpprPlugin;
		$wpprPlugin->showAdmin();
	}
	
	function wppr_pastrank_mypastrank(){
		GLOBAL $wpprPlugin;
		$wpprPlugin->showMyPastrank();
	}
	
	function wpprShowWidget($domain){
		GLOBAL $wpprPlugin;
		echo $wpprPlugin->getWidget($domain);
	}
	### Function: Add Quick Tag For Poll In TinyMCE >= WordPress 2.5
	add_action('init', 'pastrank_tinymce_addbuttons');
	function pastrank_tinymce_addbuttons() {
		if(!current_user_can('edit_posts') && ! current_user_can('edit_pages')) {
			return;
		}
		if(get_user_option('rich_editing') == 'true') {
			add_filter("mce_external_plugins", "pastrank_tinymce_addplugin");
			add_filter('mce_buttons', 'pastrank_tinymce_registerbutton');
		}
	}
	function pastrank_tinymce_registerbutton($buttons) {
		array_push($buttons, 'separator', 'wp_pastrank');
		return $buttons;
	}
	function pastrank_tinymce_addplugin($plugin_array) {
		GLOBAL $wpprPlugin;
		$plugin_array['wp_pastrank'] = $wpprPlugin->pluginPath.'editor_plugin.js';
		return $plugin_array;
	}
}
?>