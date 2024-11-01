<?php
	//wp_register_script("api_pastrank_com_api_js", "http://test.pastrank.com/aip/api.js");
	//add_action("wp_head", "wppraddCSSAndScript");
	//add_action('wp_head', 'wppraddCSSAndScript');
	function showAdminHtml(){
		GLOBAL $wpprPlugin;
?>
<fieldset class="wpprFieldset wpprLogo">
  <legend><?php _e("Options", "wppr")?></legend>
  <table width="100%" border="0" cellspacing="0" cellpadding="0" class="mainTable">
  <tr>
    <td width="40%" valign="top" class="option">
    <form action="" method="post" id="wpprAddSite">
    <table width="100%" border="0" cellspacing="0" cellpadding="8">
      <tr>
        <td class="caption"><?php _e("your domain", "wppr")?></td>
        <td><input type="hidden" name="saveOption" value="true" /><input type="text" value="<?php echo $wpprPlugin->options["siteName"]?>" id="txtSiteName" name="txtSiteName"  /></td>
      </tr>
      <tr>
      	<td colspan="2"><?php _e("Please use your usual domain name, cause www.pastrank.com and pastrank.com is different for search engine.", "wppr")?></td>
      </tr>
      <tr>
        <td class="caption" colspan="2"><?php _e("show pastrank chart in posts", "wppr")?>&nbsp;<input type="checkbox" value="1" id="chkShowInPost" name="chkShowInPost" <?php if($wpprPlugin->options["showToPost"] == "1") echo 'checked="checked"'?>/></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
        <td><input type="button" name="button" id="button" value="<?php _e('Save&gt;&gt;') ?>" onclick="addSite($('#txtSiteName').val(), '<?php _e("Please enter a valid domain, eg. pastrank.com", "wppr") ?>')" /><img src="<?php echo $wpprPlugin->pluginPath;?>statics/103.gif" id="imgLoading" style="display: none;"  /></td>
      </tr>
      <tr>
        <td colspan="2"><a href="http://www.pastrank.com/wp-pastrank.php" target="_blank">
		<?php _e("Get more help information...", "wppr")?></a><br />
        <a href="http://www.pastrank.com/wp-pastrank.php" target="_blank">
		<?php _e("Go to pastrank's official web site", "wppr")?></a>
        </td>
      </tr>
    </table>
    </form>
    </td>
    <td class="rightDonate" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td class="caption"><?php _e("donate", "wppr")?></td>
        </tr>
      <tr>
        <td><ol class="donate">
          <li>
            <?php _e("using paypal", "wppr")?>
            <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_blank" onsubmit="$('#txtDonateAmount').val($('#wpprAmount').val());$('#txtDonateCurrency').val($('#wpprCurrency').val());">
              <table border="0" cellspacing="0" cellpadding="0">
                <tr>
                  <td width="87"><input type="hidden" name="cmd" value="_xclick" />
                    <input type="hidden" name="business" value="conis@conis.cn" />
                    <input type="hidden" name="item_name" value="Wordpress Plugin(wp-pastrank)" />
                    <input type="hidden" name="item_number" value="2010" />
                    <input type="hidden" name="amount" value="50.00" id="txtDonateAmount" />
                    <input type="hidden" name="no_shipping" value="2" />
                    <input type="hidden" name="no_note" value="1" />
                    <input type="hidden" name="currency_code" value="USD" id="txtDonateCurrency" />
                    <input type="hidden" name="tax" value="0" />
                    <?php _e("amount", "wppr")?></td>
                  <td width="271"><select name="wpprAmount" id="wpprAmount">
                    <option>1</option>
                    <option>2</option>
                    <option selected="selected">5</option>
                    <option>10</option>
                    <option>20</option>
                    <option>50</option>
                    <option>100</option>
                  </select></td>
                </tr>
                <tr>
                  <td><?php _e("currency", "wppr")?></td>
                  <td><select name="wpprCurrency" id="wpprCurrency">
                    <option value="USD">USD</option>
                    <option value="EUR">EUR</option>
                    <option value="HKD">HKD</option>
                    <option value="GBP">GBP</option>
                  </select></td>
                </tr>
                <tr>
                  <td colspan="2"><input type="image" src="<?php echo $wpprPlugin->pluginPath;?>statics/btn_donate_LG.gif" border="0" name="submit" />
                    <a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&amp;hosted_button_id=AJSNFGSM2MZQA" target="_blank">
                      <?php _e("custom amount", "wppr")?>
                    </a></td>
                  </tr>
              </table>
            </form>
          </li>
          <li>
            <?php _e("add pastrank link in my blog", "wppr")?>
          </li>
          <li>
            <?php _e("post about pastrank & share that to my friend", "wppr")?>
          </li>
        </ol></td>
        </tr>
      <tr>
        <td><?php _e("Thank you using wp-pastrank, Pastrank is totally free. what's more, API&wp-pastrank is free too. hope you enjoy using it. and for the better development of pastrank, hope you can add PastRank link to your website, or write posts to recommend Pastrank to others. if you think Pastrank is great, you can donote for our hardwork.", "wppr")?></td>
        </tr>
    </table></td>
  </tr>
</table>
</fieldset>
<?php
	$wpprPlugin->showClientInfo();
} ?>

<?php function showMyPastrankHtml(){
	GLOBAL $wpprPlugin;
?>
<script language="javascript">
var gpr;
var obj;
var wpprSite = "<?php echo $wpprPlugin->options["siteName"]?>";
function setPastRank(event)
{	
	if(event){
		obj = jQuery(event);
		curEvent = obj;
	}else 
		obj = curEvent;
	
	//clearr class
	jQuery("#wpprViewTree li").removeClass("current");
	obj.addClass("current");
	var showType = obj.attr("showType");
	var searchType = obj.attr("searchType");
	var search = wpprGetChecked("#searchMenu input:checked");
	var alexa = wpprGetChecked("#alexaMenu input:checked");
	var linkIn = wpprGetChecked("#linkInMenu input:checked");
	var curOption = "#searchMenu";
	var subcaption = search.texts;
	switch(showType)
	{
		case "linkin":
			curOption = "#linkInMenu";
			subcaption = linkIn.texts;
			break;
		case "alexa":
			curOption = "#alexaMenu";
			subcaption = alexa.texts;
			break;
	}
	$("#wpprViewContent li:lt(3)").hide();
	$(curOption).show();
	var options = 
	{
		fusionChartsJS: "<?php echo $wpprPlugin->pluginPath;?>statics/FusionCharts.js",
		lineSWF: "<?php echo $wpprPlugin->pluginPath;?>statics/MSLine.swf",
		inverseLineSWF: "<?php echo $wpprPlugin->pluginPath;?>statics/InverseMSLine.swf",
		showType: showType,
		searchType: searchType,
		search: search.values,
		alexa: alexa.values,
		linkIn: linkIn.values,
		searchText: search.texts,
		linkInText: linkIn.texts,
		alexaText: alexa.texts,
		searchColor: search.colors,
		alexaColor: alexa.colors,
		linkInColor: linkIn.colors,
		showValues: 1,
		sites: wpprSite,
		container: "wpprChart",
		dateFormat: "MM-dd",
		timeSpan: 1,
		caption:wpprSite,
		limit: 10,
		subcaption : obj.text(),
		onError: function(flag, data){
			var error;
			if(flag == PASTRANKERROR.statusError){
				error = data.info.status == "0" ? "<?php _e("We will collect data in 2-7 workdays after you have added your domain.", "wppr") ?>" : "<?php _e("Sorry, this domain is forbidden.", "wppr") ?>";
			}else
				error = data.description;
			$("#wpprChart").html('<p class="wpprError" style="padding-top: 140px;">' + error + '</p>');
		}
	};
	
	if(!gpr)
	{
		gpr = new getPastRank(options);
	}else
	{
		gpr.updateChart(options);
	}
	return gpr
}
</script>
<fieldset class="wpprFieldset">
  <legend><?php _e("My pastrank", "wppr")?></legend>
  <ul id="wpprViewTree">
    <li showType="search" searchType="c"><a><?php _e("All the indexed on search engines", "wppr")?></a></li>
    <li showType="search" searchType="ct"><a><?php _e("Past 24h on search engine", "wppr")?></a></li>
    <li showType="search" searchType="cw"><a><?php _e("Past week on search engine", "wppr")?></a></li>
    <li showType="search" searchType="cm"><a><?php _e("Past month on search engine", "wppr")?></a></li>
    <li showType="search" searchType="cy"><a><?php _e("Past year on search engine", "wppr")?></a></li>
    <li showType="alexa" class="blue"><a><?php _e("Alexa traffic rank", "wppr")?></a></li>
    <li showType="linkin" class="blue"><a><?php _e("Link in", "wppr")?></a></li>
    <li><span class="wpprEditorCaption"><?php _e("Other domain", "wppr")?></span>
    <br />
    <input type="text" id="txtOtherDomain" />
    <input type="button" value="<?php _e("View", "wppr")?>" onclick="gpr = null; wpprSite = $('#txtOtherDomain').val(); setPastRank();" />
    </li>
    <li class="wpprSetting">
    	<a href="tools.php?page=wp-pastrank"><?php _e("Setting", "wppr")?></a>&nbsp;
    	<a href="http://www.pastrank.com/" target="_blank"><?php _e("Add other domain", "wppr")?></a>
    </li>
    <li class="wpprSetting">        <a href="http://www.pastrank.com/wp-pastrank.php" target="_blank">
		<?php _e("Go to pastrank's official web site", "wppr")?></a></li>
  </ul>
  <ul id="wpprViewContent">
    <li id="searchMenu" class="wpprOption">
    <span>
      <input type="checkbox" value="google.cn" checked="checked" />
      <label color="FF00FF">Google</label>
      <input type="checkbox" value="baidu.com" checked="checked" />
      <label color="0000FF">Baidu</label>
     </span>
      <input type="button" value="<?php _e("reset", "wppr")?>" onclick="setPastRank()" />
    </li>
    <li id="linkInMenu" class="wpprOption">
    <span>
      <input type="checkbox" value="google.cn" checked="checked"/>
      <label color="FF00FF">Google</label>
      <input type="checkbox" value="baidu.com" checked="checked"/>
      <label color="0000FF">Baidu</label>
     </span>
     <input type="checkbox" value="alexa" checked="checked"/>
      <label color="00FFFF">Alexa</label>
      <input type="button" value="<?php _e("reset", "wppr")?>" onclick="setPastRank()" />
    </li>
    <li id="alexaMenu" class="wpprOption">
      <input type="checkbox" value="ta" />
      <label color="FF00FF"><?php _e("yesterday", "wppr")?></label>
       <input type="checkbox" value="rc"/>
       <label color="FF00FF"><?php _e("Traffic rank in country", "wppr") ?></label
      ><input type="checkbox" value="wa"/>
      <label color="CC00FF"><?php _e("7 day", "wppr")?></label>
      <input type="checkbox" value="ma"  checked="checked"/>
      <label color="00EEFF"><?php _e("1 month", "wppr")?></label>
      <input type="checkbox" value="ta" checked="checked"/>
      <label color="FF6600"><?php _e("3 month", "wppr")?></label>
      <input type="button" value="<?php _e("reset", "wppr")?>" onclick="setPastRank()" />
    </li>
    <li id="wpprChart">
      <p><img src="<?php echo $wpprPlugin->pluginPath;?>statics/103.gif"  /></p>
      <p><?php _e("loading chart data, please wait...", "wppr")?></p>
    </li>
  </ul>
</fieldset>

<script language="javascript">
	initMyPastrank();
</script>
<?php
$wpprPlugin->showClientInfo();
}//end mypastrank ?>