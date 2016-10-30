<?php
/*
Plugin Name: Dogatsu Colours
Plugin URI: http://laurencelord.co.uk/share/plugins/like/dogatsu-colours/
Description: A special plugin of Dogatsu. Pick colours in the customizer and get CSS added to your `wp_head§
Author: Laurence Lord
Version: 1.1
Author URI: http://laurencelord.co.uk/
*/

// Add Colour Options to Customizer
add_action( 'customize_register', 'dogatsu_colours_customize_register' );
function dogatsu_colours_customize_register($wp_customize) {

	$wp_customize->add_setting( 'dogatsu_colours_setting[text_colour]', array(
	    'default'        => '#404040',
	    'type'           => 'option',
	    'capability'     => 'edit_theme_options',
	) );

	$wp_customize->add_setting( 'dogatsu_colours_setting[link_colour]', array(
	    'default'        => '#f5467a',
	    'type'           => 'option',
	    'capability'     => 'edit_theme_options',
	) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'text_colour', array(
	    'label'   => __( 'Text Colour', 'dogatsu' ),
	    'section' => 'colors',
	    'priority'=> 102,
	    'settings'=> 'dogatsu_colours_setting[text_colour]',
	) ) );

	$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, 'link_colour', array(
	    'label'   => __( 'Link Colour', 'dogatsu' ),
	    'section' => 'colors',
	    'priority'=> 101,
	    'settings'=> 'dogatsu_colours_setting[link_colour]',
	) ) );
}


// We need some CSS to apply the colour to classes
add_action( 'admin_head', 'dogatsu_colours_css' );
add_action( 'wp_head', 'dogatsu_colours_css' );
function dogatsu_colours_css() {
	// This gets the values back out of the array.
	$options = get_option('dogatsu_colours_setting');

	if( $options === false ) {
		return;
	}

	// and this gets ready to count through them.
	$i 		= 1;

	// This starts to build the CSS that will be echoed
	$echo  	= "<style type='text/css'>\n\r	";

	// This adds a class for each hexidecimal value to the CSS
	foreach ($options as $key => $value) {

		// Check if it's a link_colour option
		$tc = strrpos($key, "text_colour");
		$lc = strrpos($key, "link_colour");
		if ( $lc === false && $tc === false )
			continue;

		switch($key) {
			case 'text_colour' :
				$echo	.= "body,\n\tbutton,\n\tinput,\n\tselect,\n\ttextarea {\n\t\tcolor: $value;\n\t}\n\r 	";
			break;
			case 'link_colour' :
				$echo	.= "#primary a:hover,\n\t#secondary a:hover {\n\t\tcolor: $value;\n\t}\n\r 	";
			break;
		}
		
	}

	// Now to finish building the CSS
	$echo 	.= "</style>\n\r";

	// ...And echo it;
	echo $echo;
}


/*
TODO: Add code for live preview in Customizer screen
function dogatsu_customize_preview() {
    ?>
    <script type="text/javascript">
    ( function( $ ){
    wp.customize('text_colour',function( value ) {
    	console.log(value)
        value.bind(function(to) {
            $('body, button, input, select, textarea').css('background-color', value );
        });
    });
    } )( jQuery )
    </script>
    <?php
} 
*/


// Use Colour Picker Script
add_action( 'admin_enqueue_scripts', 'dogatsu_colours_scripts' );
function dogatsu_colours_scripts( $hook_suffix ) {
    // first check that $hook_suffix is appropriate for your admin page
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'dogatsu_colours_script', plugins_url('dogatsu-colours-script.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}

?>