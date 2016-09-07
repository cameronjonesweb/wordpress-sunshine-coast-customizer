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

class mttr_phone_number_settings {

	public $setting, $section;

	function __construct() {

		$this->setting = 'mttr_phone_number';
		$this->section = 'mttr-client-details';

	}


	function get_settings() {

		return $this->setting;

	}


	function get_section() {

		return $this->section;

	}

}


class mttr_phone_number_output {

	public static function render_phone_number() {

		$settings = new mttr_phone_number_settings;
		$setting = $settings->get_settings();

		$number = esc_attr( get_option( $setting ) );

		if( !empty( $number ) ) {

			$output = '';

			$output .= '<div class="' . $setting . '">';

				$output .= '<a href="tel:' . str_replace( ' ', '', $number ) . '">';

					$output .= $number;

				$output .= '</a>';

			$output .= '</div>';

			return $output;

		}

	}

}


class mttr_phone_number_init extends mttr_phone_number_settings {

	public $setting, $section;

	function __construct() {

		$settings = new mttr_phone_number_settings;

		$this->setting = $settings->get_settings();

		$this->section = $settings->get_section();

		add_action( 'twentysixteen_credits', function(){

			echo mttr_phone_number_output::render_phone_number();

		} );

		add_action( 'widgets_init', array( $this, 'mttr_register_widgets' ) );

		add_action( 'customize_register', array( $this, 'mttr_phone_number_customize_register' ) );

		add_action( 'admin_enqueue_scripts', array( $this, 'mttr_enqueue_resources' ) );

		add_shortcode( 'mttr_phone', array( 'mttr_phone_number_output', 'render_phone_number' ) );

	}


	function mttr_phone_number_customize_register( WP_Customize_Manager $wp_customize ) {

		$wp_customize->add_section( 			// Add new customizer section

			$this->section, 					// Slug for our section
			array(								// Arguments for the section

				'title' => 'Client Details',	// Name of the section
				'priority' => 0,				// Smaller the number the higher it displays

			)

		);

		$wp_customize->add_setting( 				// Add new customizer setting

			$this->setting, 						// Slug for our setting
			array(									// Arguments for the setting

				'type' => 'option',					// Type of setting ( option for plugins, theme_mod for themes )
				'transport' => 'postMessage',		// postMessage lets us live preview. It's not default :(
				'sanitize_callback' => 'esc_attr'	// Function to sanitze it

			)

		);

		$wp_customize->add_control( 			// Add a new customizer control

			$this->setting, 					// Slug for our setting
			array(								// Arguments for the control

				'label' => 'Phone Number',		// Name for the control
				'section' => $this->section,	// Section for the control

			)

		);

		if ( isset( $wp_customize->selective_refresh ) ) {						// Check if we're using a WordPress version that supports selective refresh

			$wp_customize->selective_refresh->add_partial( 						// Add a new selective refresh partial

				$this->setting, 												// Slug for the setting
				array(															// Arguments for the partial

		        	'selector' => '.' . $this->setting,							// CSS selector to target
		        	'container_inclusive' => true,								// Replace the entire wrapper
		        	'render_callback' => function() {							// Function to actually output the update
		            
		        		echo mttr_phone_number_output::render_phone_number();	// Output our phone number

		        	},

		    	)

			);

		} else {	// If there is no selective refresh

			// Enqueue JavaScript here that will update the preview in case users are running < 4.5

		}

	}


	function mttr_enqueue_resources() {

		if( is_customize_preview() ) {	// As the style only applys to the customizer only enqueue it there

			wp_enqueue_style( 

				'mttr_phone_number', 
				plugins_url( 

					'../assets/css/mttr-phone-number-customizer.css', 
					__FILE__

				) 

			);

		}

	}


	function mttr_register_widgets() {

		register_widget( 'mttr_phone_number_widget' );

	}

}

class mttr_phone_number_widget extends WP_Widget {

	function __construct() {

		// Instantiate the parent object
		parent::__construct( 

			'mttr_phone_number_widget', 				// Slug for our widget
			'Phone Number', 							// Name of the widget
			array(										// Extra settings

				'customize_selective_refresh' => true,	// Support selective refresh. If false (default) the whole page will reload when updating the widget

			)

		);

	}


	function widget( $args, $instance ) {

		// Widget output

		// !important. Selective refresh will not work without the before_widget and after_widget args. The customizer adds a bunch of data attributes neccesary to target the widget to update it.
		echo $args['before_widget'];

			// Output widget title
			echo !empty( $instance['title'] ) ? $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . $args['after_title'] : '';
			
			// Output phone number
			echo mttr_phone_number_output::render_phone_number();

		// !important. Selective refresh will not work without the before_widget and after_widget args. The customizer adds a bunch of data attributes neccesary to target the widget to update it.
		echo $args['after_widget'];

	}


	function update( $new_instance, $old_instance ) {

		// Save widget options
		$instance = array();

		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

		return $instance;

	}


	function form( $instance ) {

		if ( isset( $instance[ 'title' ] ) ) {

			$title = $instance[ 'title' ];

		} else {

			$title = 'New title';

		}

		// Widget admin form
		?>
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
			<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<?php
		// Output admin widget options form
		echo '<p>You can set the phone number in the <a href="' . admin_url() . 'customizer.php">Customizer</a></p>';

	}

}