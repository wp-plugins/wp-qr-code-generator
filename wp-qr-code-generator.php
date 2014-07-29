<?
/*
Plugin Name: WP QR Code Generator
Plugin URI: http://www.vivacityinfotech.net
Description: An easy way to add your QR Code widget in your sidebars and add in your page .
Version: 1.2
Author URI: http://www.vivacityinfotech.net
Requires at least: 3.8
Text Domain: WP-QR-Code-Generator
License: vivacityinfotech
*/
add_filter('plugin_row_meta', 'RegisterPluginLinks_qr',10, 2);
function RegisterPluginLinks_qr($links, $file) {
	if ( strpos( $file, 'wp-qr-code-generator.php' ) !== false ) {
		$links[] = '<a href="https://wordpress.org/plugins/wp-qr-code-generator/faq/">FAQ</a>';
		$links[] = '<a href="mailto:support@vivacityinfotech.com">Support</a>';
		$links[] = '<a href="http://bit.ly/1icl56K">Donate</a>';
	}
	return $links;
}



add_action('admin_enqueue_scripts','admin_jquery_link');
add_action('wp_enqueue_scripts','front_jquery_link');
add_shortcode('vqr','qr_shortcode');

function admin_jquery_link($hook_suffix){
 wp_enqueue_style( 'wp-color-picker' );
 wp_enqueue_script( 'admin_script', plugins_url('/js/admin_script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}
 
function front_jquery_link(){
wp_enqueue_script( 'WP-QR-Code-Generator-js', plugins_url( 'qrcode.js' , __FILE__ ));
}

function qr_shortcode($attr,$cont=null){
extract(shortcode_atts( array(
		'msg' => " WP QR Code Generator",
		'size' => 160,
                'color_Light' => "'.$color_Light.'",
		'level' => "Q"
	 	), $attr ));
		$r=rand(0,9999);
return '<div id="vqr'.$r.'"></div>
<script type="text/javascript">
new QRCode(document.getElementById("vqr'.$r.'"), {
	text: "'.$msg.'",
	width: '.$size.',
	height: '.$size.',
	colorDark : "#222222",
	colorLight : "'.$color_Light.'",
	correctLevel : QRCode.CorrectLevel.'.$level.'
});
</script>';
}
add_action( 'init', 'qrcode_submit' );
function qrcode_submit() {
    add_filter( "mce_external_plugins", "qr_getvalue" );
    add_filter( 'mce_buttons', 'registration_qr' );
}
function qr_getvalue( $plugin_array ) {
    $plugin_array['vqr'] = plugins_url( 'js/WP-QR-Code-Generator.js' , __FILE__ );
    return $plugin_array;
}
function registration_qr( $buttons ) {
    array_push( $buttons,'qrcode_submitvalue' );
    return $buttons;
}

class qrcode_Widget extends WP_Widget {
	
	public function __construct() {
		parent::__construct(
	 		'qrcode_Widget', 
			'WP QRcode Widget', 
			array( 'description' =>  ' QRcode Generator widget' )
		);
	}

	public function widget( $args, $input_value ) {
		extract( $args );
		$title = apply_filters( 'widget_title', $input_value['title'] );
		$text = $input_value[ 'text' ];
		$size = $input_value[ 'size' ];
                $color_Light = $input_value[ 'color_Light' ];
		$level = $input_value[ 'level' ];
		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		echo '<div id="getvalue"></div>
<script type="text/javascript">
new QRCode(document.getElementById("getvalue"), {
	text: "'.$text.'",
	width: '.$size.',
	height: '.$size.',
	colorDark : "#222222",
	colorLight : "'.$color_Light.'",
	correctLevel : QRCode.CorrectLevel.'.$level.'
});


</script>';

		echo $after_widget;
	}

 	public function form( $input_value ) {
		$text = isset($input_value[ 'text' ])?$input_value[ 'text' ]:"Enter TEXT";
		$size = isset($input_value[ 'size' ])?$input_value[ 'size' ]:"150";
		$title = isset($input_value[ 'title' ])?$input_value[ 'title' ]:" QR Code Generator";
                $color_Light = isset($input_value[ 'color_Light' ])?$input_value[ 'color_Light' ]:"#c1c1c1";
		$level = $input_value[ 'level' ];
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">TITLE:</label> <br/>
		<input class="block_text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /><br/>
		<label for="<?php echo $this->get_field_id( 'text' ); ?>">TEXT:</label> <br/>
		<textarea class="block_text" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>" ><?php echo esc_attr( $text ); ?></textarea><br/>
		<label for="<?php echo $this->get_field_id( 'size' ); ?>">SIZE:</label> <br/>
		<input class="block_text" id="<?php echo $this->get_field_id( 'size' ); ?>" name="<?php echo $this->get_field_name( 'size' ); ?>" type="text" value="<?php echo esc_attr( $size ); ?>" /><br/>
                <label for="<?php echo $this->get_field_id( 'color_Light' ); ?>">BACKGROUND COLOR:</label> <br/>
		<input class="color_Light intentColor" id="<?php echo $this->get_field_id( 'color_Light' ); ?>" name="<?php echo $this->get_field_name( 'color_Light' ); ?>" type="text" value="<?php echo esc_attr( $color_Light ); ?>" data-default-color="<?php echo esc_attr( $color_Light ); ?>"/><br/>
		<label for="<?php echo $this->get_field_id( 'level' ); ?>">LEVEL:</label> <br/>
		<select class="block_text" id="<?php echo $this->get_field_id( 'level' ); ?>" name="<?php echo $this->get_field_name( 'level' ); ?>">
	<option value="L" <?php if($level=='L' ) echo 'selected'; ?> >L</option>
	<option value="M" <?php if($level=='M') echo 'selected'; ?>>M</option>
	<option value="H" <?php if($level=='H' ) echo 'selected'; ?>>H</option>
	<option value="Q" <?php if($level=='Q' || $level == ''  ) echo 'selected'; ?>>Q</option>
	
	</select>
		</p>
		<?php 
	}

	public function update( $new_inputvalue, $old_inputvalue ) {
		$input_value = array();
		$input_value['title'] = strip_tags( $new_inputvalue['title'] );
		$input_value['text'] = strip_tags( $new_inputvalue['text'] );
		$input_value['size'] = strip_tags( $new_inputvalue['size'] );
                $input_value['color_Light'] = strip_tags( $new_inputvalue['color_Light'] );
		$input_value['level'] = strip_tags( $new_inputvalue['level'] );
		return $input_value;
	}

}
add_action( 'widgets_init', create_function( '', 'register_widget( "qrcode_Widget" );' ) );

?>
