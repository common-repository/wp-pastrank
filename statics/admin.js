wpprGetSearchEnginer = function(sites, o){
	var url = WPPRAPI + "?action=search&sites=" + sites + "&op=jsonp";
	wpprRequestData(url, function(data){
		if(typeof(o) == "function")
			o(data);
		else{
			var html = "";
			for(var i = 0; i < data.length; i ++){
				var item = data[i];
				html += '<input type="checkbox" value="' + item.n + '" checked="checked" />' + 
					'<label color="' + item.c + '">' + item.n + '</label>';
			};
			$(o).append(html);
		}
	});
}

wpprGetChecked = function(expr){
	var values = [];
	var texts = [];
	var colors = [];
	$(expr).each(function(){
		var obj = $(this);
		var lbl = $("+label",obj);
		texts[texts.length] = lbl.text();
		colors[colors.length] = lbl.attr("color");
		values[values.length] = obj.val();
	});	
	return {
		values: values,
		texts: texts,
		colors: colors
	};	
}

wpprGetConfig = function (){
	var data = {
		sites: $("#txtSites").val(),
		startDate: $("#txtStartDate").val(),
		endDate : $("#txtToDate").val(),
		chartType : $("#selChartType").val(),
		showType : $("#wpprShowType").val(),
		element: $("#wpprSearchOption").val(),
		getMinMax: ($("#chkGetMinMax:checked").length == 1) ? 1 : 0
	};
	var texts = "";
	data.sites = data.sites || "www.pastrank.com";
	width = $("#txtWidth").val();
	height = $("#txtHeight").val();
	if(width.indexOf("px") < 0 && width.indexOf("%") < 0) width = width + "px";
	if(height.indexOf("px") < 0 && height.indexOf("%") < 0) height = height + "px";
	
	var style = "height: " + height + ";width:" + width + ";";
	var tmpChecked;
	switch(data.showType)
	{
		case "search":
			tmpChecked = wpprGetChecked("#wpprSearchList input:checked");
			break;
		case "alexa":
			tmpChecked = wpprGetChecked("#wpprAlexaList input:checked");
			break;
		default:
			tmpChecked = wpprGetChecked("#wpprSearchList input:checked, #wpprLinkinAlexa input:checked");
			break;
	}
	
	//data.values = values.join(",").replace(/\d+\|(.+?)/ig, "$1") ;
	data.values = tmpChecked.values.join(",");
	data.texts = tmpChecked.texts.join(",");
	data.colors = tmpChecked.colors.join(",");
	var eqID = "pastrankChart_" + Math.floor(Math.random() * 100000);
	var result = WPPRGETCHART + "?" + $.param(data);
	return '[pastrank url="' + result + '" id="'+ eqID + '" style="' + style + '"]';
}

function addSite(site, err){
	//alert((/^[a-z0-9]([a-z0-9-]+\.){1,2}[a-z]{2,4}$/gi.test("www.conis.cn")));
	var pattern = /^[a-z0-9]([a-z0-9-]+\.){1,2}[a-z]{2,4}$/gi;
	var reg = new RegExp(pattern);
	if(site == "" || !reg.test(site))
	{
		alert(err);
		return;
	}
	var imgObj = $("#imgLoading");
	imgObj.show();
	var url = WPPRAPI + "?action=addSite&agent=wp-pastrank&op=jsonp&site=" + site;
	wpprRequestData(url, function(data){
		imgObj.hide();
		if(data.command == "true")
			$("#wpprAddSite").submit();
		else
			alert(data.description);
	});
}

function initMyPastrank(){
	$("#wpprViewTree li:lt(7)").bind("click", function(){setPastRank(this)});
		$("#wpprViewContent li input[type=checkbox]").bind("click", function(){
			var parent = jQuery(this).parent();
			var btn = $("input[type=button]", parent);
			if($("input[type=checkbox]:checked", parent).length == 0)
				btn.attr("disabled", "disabled");
			else
				btn.removeAttr("disabled");
		});
		$("#wpprViewTree li[showType='alexa']").click();	
}

function insertPastrank()
{
	var result = wpprGetConfig();
	if(window.tinyMCE) {
		window.tinyMCE.execInstanceCommand('content', 'mceInsertContent', false, result);
		//Peforms a clean up of the current editor HTML.
		//tinyMCEPopup.editor.execCommand('mceCleanup');
		//Repaints the editor. Sometimes the browser has graphic glitches.
		tinyMCEPopup.editor.execCommand('mceRepaint');
		tinyMCEPopup.close();
	}	
}