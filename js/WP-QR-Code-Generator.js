(function() {
    tinymce.create('tinymce.plugins.qrcode', {
        init : function(ed, url) {
 
            ed.addButton('qrcode_submitvalue', {
                title : 'Add QR code shortcode',
                onclick : function() {
						tb_show( 'qrcodegenerator Shortcode', '#TB_inline?width=' + 500 + '&height=' + 400 + '&inlineId=qrcode-block' );
					},
					image : url + '/WP-QR-Code-Generator.jpg'

            });
        },
    });
    tinymce.PluginManager.add( 'vqr', tinymce.plugins.qrcode );
	
	jQuery(function(){
		var block = jQuery('<div id="qrcode-block">\
		<table width="100%" border="0" cellspacing="2" cellpadding="2">\
  <tr>\
    <td>\
	<label for="qrcode-msg">Enter WP QR Code Text</label><br>\
	<textarea id="qrcode-msg" name="msg" cols="50" rows="5"></textarea></td>\
  </tr>\
  <tr>\
    <td><label for="qrcode-size">Size</label><input type="text" size="10" id="qrcode-size" name="size" value="160">px<br>\
	<label for="qrcode-level">Level</label><select id="qrcode-level" name="level">\
	<option value="L">L</option>\
	<option value="M">M</option>\
	<option value="H" >H</option>\
	<option value="Q" selected="selected" >Q</option>\
	</select>\
	</td>\
  </tr>\
  <tr>\
    <td><input type="button" id="submit" name="submit" value="Insert QR Code"></td>\
  </tr>\
</table>\
</div>');
		
		var table = block.find('table');
		block.appendTo('body').hide();
		block.find('#submit').click(function(){
			var options = { 
				'msg' : '',
				'size': '160',
				'level' : 'Q'
				};
			var shortcode = '[vqr';
			
			for( var index in options) {
				var value = table.find('#qrcode-' + index).val();
				if ( value !== options[index] )
					shortcode += ' ' + index + '="' + value + '"';
			}
			
			shortcode += '/]';
			tinyMCE.activeEditor.execCommand('mceInsertContent', 0, shortcode);
			tb_remove();
		});
	});
})();