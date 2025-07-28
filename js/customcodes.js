var timeout;
 
jQuery( function( $ ) {
	$('.woocommerce').on('change', 'input.qty', function(){
 
		if ( timeout !== undefined ) {
			clearTimeout( timeout );
		}
 
		timeout = setTimeout(function() {
			$( document.body ).trigger( 'wc_fragment_refresh' );
		}, 500 ); // 1 second delay, half a second (500) seems comfortable too
 
	});
} );


(function(){
tinymce.PluginManager.requireLangPack('blist');
tinymce.create('tinymce.plugins.blist', {
init : function(ed, url){
ed.addButton('blcss', {
  type: 'menubutton',
  menu: [
          {
            text: '1',
            onclick: function() {
              ilc_sel_content = tinyMCE.activeEditor.selection.getContent();
              tinyMCE.activeEditor.selection.setContent('<span style="line-height:1;">' + ilc_sel_content + '</span>');
            }
          },
          {
            text: '1.1',
            onclick: function() {
              ilc_sel_content = tinyMCE.activeEditor.selection.getContent();
              tinyMCE.activeEditor.selection.setContent('<span style="line-height:1.1;">' + ilc_sel_content + '</span>');
            }
          },
          {
            text: '1.2',
            onclick: function() {
              ilc_sel_content = tinyMCE.activeEditor.selection.getContent();
              tinyMCE.activeEditor.selection.setContent('<span style="line-height:1.2;">' + ilc_sel_content + '</span>');
            }
          },
          {
            text: '1.3',
            onclick: function() {
              ilc_sel_content = tinyMCE.activeEditor.selection.getContent();
              tinyMCE.activeEditor.selection.setContent('<span style="line-height:1.3;">' + ilc_sel_content + '</span>');
            }
          },
          {
            text: '1.4',
            onclick: function() {
              ilc_sel_content = tinyMCE.activeEditor.selection.getContent();
              tinyMCE.activeEditor.selection.setContent('<span style="line-height:1.4;">' + ilc_sel_content + '</span>');
            }
          },
          {
            text: '1.5',
            onclick: function() {
              ilc_sel_content = tinyMCE.activeEditor.selection.getContent();
              tinyMCE.activeEditor.selection.setContent('<span style="line-height:1.5;">' + ilc_sel_content + '</span>');
            }
          },
          {
            text: '1.6',
            onclick: function() {
              ilc_sel_content = tinyMCE.activeEditor.selection.getContent();
              tinyMCE.activeEditor.selection.setContent('<span style="line-height:1.6;">' + ilc_sel_content + '</span>');
            }
          },
          {
            text: '1.7',
            onclick: function() {
              ilc_sel_content = tinyMCE.activeEditor.selection.getContent();
              tinyMCE.activeEditor.selection.setContent('<span style="line-height:1.7;">' + ilc_sel_content + '</span>');
            }
          },
          {
            text: '1.8',
            onclick: function() {
              ilc_sel_content = tinyMCE.activeEditor.selection.getContent();
              tinyMCE.activeEditor.selection.setContent('<span style="line-height:1.8;">' + ilc_sel_content + '</span>');
            }
          },
          {
            text: '1.9',
            onclick: function() {
              ilc_sel_content = tinyMCE.activeEditor.selection.getContent();
              tinyMCE.activeEditor.selection.setContent('<span style="line-height:1.9;">' + ilc_sel_content + '</span>');
            }
          },
          {
            text: '2.1',
            onclick: function() {
              ilc_sel_content = tinyMCE.activeEditor.selection.getContent();
              tinyMCE.activeEditor.selection.setContent('<span style="line-height:2.1;">' + ilc_sel_content + '</span>');
            }
          },
          {
            text: '2.2',
            onclick: function() {
              ilc_sel_content = tinyMCE.activeEditor.selection.getContent();
              tinyMCE.activeEditor.selection.setContent('<span style="line-height:2.2;">' + ilc_sel_content + '</span>');
            }
          }
        ],
title: 'CSS Wrap',
image: 'https://png.icons8.com/windows/1600/text-height.png',
});
},
createControl : function(n, cm){
return null;
},
});
tinymce.PluginManager.add('blist', tinymce.plugins.blist);
})();
