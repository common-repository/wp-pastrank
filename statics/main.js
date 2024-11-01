// JavaScript Document
var WPPRAPI = "http://www.pastrank.com/api/api.php";
var WPPRGETCHART = "http://www.pastrank.com/api/getchart.php";;
var $ = jQuery;

wpprGetLast = function(domain, o, imgPath){
	function wpprGetDate(date){
		return date;
		return date.substr(date.indexOf("-") + 1, date.length);
	}
	var url = WPPRAPI + "?limit=1&op=jsonp&getMinMax=0&timeSpan=0&action=rank&sites=" + domain;
	wpprRequestData(url, function(data){
		var html = "";
		if(!data) return;
		if(data.command == "false")
			html = data.description;
		else{
			data = data[0];
			html += '<li class="wpprUrl"><h5>' + data.info.url + '</h5></li>';
			//get indexed on search engines
			if(data.search)
				for(var i = 0; i < data.search.length; i++){
					var item = data.search[i];
					var tmpLink;
					switch(item.t)
					{
						case "baidu.com":
							tmpLink = "/s?wd=";
							break;
						default: 
							tmpLink = "/search?q=";
							break;
					}
					tmpLink = '<a href="http://www.' + item.t + tmpLink + 'site%3A' + data.info.url +
						'" target="_blank"><img src="' + imgPath + item.t + '.gif" /></a>';
					html += '<li><span class="wpprType" title="' + item.t + '">' + tmpLink + '</span><span class="wpprDate">' +
					wpprGetDate(item.d) + '</span><span class="wpprCount">' + item.c + '</span></li>';
				}
			//get alexa traffic rank
			if(data.alexa){
				var item = data.alexa[0];
				html += '<li><span class="wpprType" title="alexa"><a href="http://www.alexa.com/siteinfo/' + data.info.url + '" target="_blank"><img src="' + imgPath + 'alexa.gif" /></a></span><span class="wpprDate">' + wpprGetDate(item.d) +
					'</span><span class="wpprCount">' + item.ta + '</span></li>';
			}
		}
		
		html = '<ul class="wpprWidget">' + html + '</ul>';
		if(o)
			$(o).html(html);
		else
			document.writeln(html);
	});
}

//get json datas from remote
wpprRequestData = function(url, callback)
{
	GLOBALWPPRFUNC = function(data) {
		if(callback) callback(data);
		//delete script object
		var head = document.getElementsByTagName("head")[0];
		head.removeChild(document.getElementById(url));
	}
	url += "&callback=GLOBALWPPRFUNC";
	wpprCreateScript(url);
}


wpprCreateScript = function(url)
{
	var script = document.getElementById(url);
    var head = document.getElementsByTagName("head")[0];
    //如果已经在，则删除
    if (script) {
        head.removeChild(script);
    }
    script = document.createElement("script");
    script.type = "text/javascript";
    script.id = url;
    script.src = url;
    head.appendChild(script);
}