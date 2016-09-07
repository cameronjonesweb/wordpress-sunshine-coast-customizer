<?php
/** --------------------------------------------------------
 *        __ __  __
 *      /  /   /   /     __/__/__
 *      \ /   /   /  __   /  /  __  (/__
 *       /   /   / /  /  /  /  /__) /  /
 *      /   /   / (__/__/_ /__/____/  /_/
 *              \
 *                SOLUTIONS
 *
 ---------------------------------------------------------- */

class mttr_css_colour_settings {

	public $setting;

	function __construct() {

		$this->setting = 'mttr_link_hover_colour';

	}


	function get_settings() {

		return $this->setting;

	}

}

class mttr_css_colour_init {

	public $setting;

	function __construct() {

		$settings = new mttr_css_colour_settings;

		$this->setting = $settings->get_settings();

		add_action( 'wp_head', array( $this, 'mttr_customize_styles' ) );

		add_action( 'customize_register', array( $this, 'mttr_css_colour_customize_register' ) );

	}

	function mttr_css_colour_customize_register( WP_Customize_Manager $wp_customize ) {
		
		$wp_customize->add_setting( $this->setting, array(

			'type' => 'option',
			'default' => '', 
			'transport' => 'postMessage',
			'sanitize_callback' => 'sanitize_hex_color',

		) );
		
		$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $this->setting, array(

			'label' => '[Custom] Link Hover Colour',
			'section' => 'colors',
			'priority' => '11'

		) ) );
		
		if ( isset( $wp_customize->selective_refresh ) ) {

			$wp_customize->selective_refresh->add_partial( $this->setting, array(

		        //'selector' => '#mttr_customize_styles',
		        'render_callback' => function() {

		            $this->mttr_customize_style_output();

		        },

		    ) );

		} else {

			// Enqueue JavaScript here that will update the preview in case users are running < 4.5

		}
		
	}

	function mttr_customize_styles() {
	
		echo '<style id="mttr_customize_styles">';

			$this->mttr_customize_style_output();

		echo '</style>';
		
	}

	function mttr_customize_style_output() {
		
		echo 'a:hover{color:' . get_option( $this->setting, '' ) . ' !important;}'; 

	}

}