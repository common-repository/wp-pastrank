
(function() {
	tinymce.create('tinymce.plugins.mceInsertPastRank', {
		init : function(ed, url) {
			ed.addCommand('mceInsertPastRank', function() {
				//�����ť���¼�
				var popFile = url + "/pop.php?date" + new Date();
				 var template = new Array();
				 template['file'] = popFile;
				 template['width'] = 300;
				 template['height'] = 200;
				 //tinyMCE.openWindow(template, {editor_id : editor_id, some_custom_arg : "somecustomdata"});
				ed.windowManager.open({
					file : popFile, //��ʾ���ڵ��ļ�
					//title: 'Add pastrank chart to your article',
					width : 420,
					height : 260,
					inline : 1
				}, {
					plugin_url : url // ����ľ���URL.
				});
			});
			ed.addButton('wp_pastrank', {
				title : 'Add pastrank chart to your article',
				cmd : 'mceInsertPastRank',			//�¼������ƣ���ed.addCommand�У���Ҫ��Ӧ����¼�
				image : url + '/statics/chartIcon.gif'
			});
			ed.onNodeChange.add(function(ed, cm, n) {
				cm.setActive('wp_pastrank', n.nodeName == 'IMG');
			});
		},
		createControl : function(n, cm) {
			return null;
		},
		getInfo : function() {
			return {
				longname : 'wp-pastrank',
				author : 'pastrank.com',
				authorurl : 'http://www.pastrank.com',
				infourl : 'http://www.pastrank.com/plugins/wp-pastrank.html',
				version : "1.0.0"
			};
		}
	});
	tinymce.PluginManager.add('wp_pastrank', tinymce.plugins.mceInsertPastRank);
})();