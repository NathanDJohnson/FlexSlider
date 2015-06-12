<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

class FlexSliderSettingsPage
{
    /**
     * Holds the values to be used in the fields callbacks
     */
    private $options;

    /**
     * Start up
     */
    public function __construct()
    {
        add_action( 'admin_menu', array( $this, 'add_plugin_page' ) );
        add_action( 'admin_init', array( $this, 'page_init' ) );
    }

    /**
     * Add options page
     */
    public function add_plugin_page()
    {
        // This page will be under "Settings"
        add_options_page(
            'Settings Admin', 
            'Flexslider Settings', 
            'manage_options', 
            'flexslider-admin', 
            array( $this, 'create_admin_page' )
        );
    }

    /**
     * Options page callback
     */
    public function create_admin_page()
    {
        // Set class property
        $this->options = get_option( 'flexslider_option_name' );
        ?>
        <div class="wrap">
            <h2>Flexslider Settings</h2>           
            <form method="post" action="options.php">
            <?php
                // This prints out all hidden setting fields
                settings_fields( 'flexslider_option_group' );   
                do_settings_sections( 'flexslider-setting-admin' );
                submit_button(); 
            ?>
            </form>
        </div>
        <?php
    }

    /**
     * Register and add settings
     */
    public function page_init()
    {        
        register_setting(
            'flexslider_option_group', // Option group
            'flexslider_option_name', // Option name
            array( $this, 'sanitize' ) // Sanitize
        );

        add_settings_section(
            'setting_section_id', // ID
            'Flexslider Custom Settings', // Title
            array( $this, 'print_section_info' ), // Callback
            'flexslider-setting-admin' // Page
        );  

        add_settings_field(
            'id_number', // ID
            'ID Number', // Title 
            array( $this, 'id_number_callback' ), // Callback
            'flexslider-setting-admin', // Page
            'setting_section_id' // Section           
        );      

        add_settings_field(
            'background-color', 
            'Background Color', 
            array( $this, 'background_color_callback' ), 
            'flexslider-setting-admin', 
            'setting_section_id'
        );      

        add_settings_field(
            'tag-color', 
            'Tag Color', 
            array( $this, 'tag_color_callback' ), 
            'flexslider-setting-admin', 
            'setting_section_id'
        );    

        add_settings_field(
            'header-color', 
            'Header Color', 
            array( $this, 'header_color_callback' ), 
            'flexslider-setting-admin', 
            'setting_section_id'
        );              

        add_settings_field(
            'text-color', 
            'Text Color', 
            array( $this, 'text_color_callback' ), 
            'flexslider-setting-admin', 
            'setting_section_id'
        ); 
    }
    
    /**
     * Sanitize each setting field as needed
     *
     * @param array $input Contains all settings fields as array keys
     */
    public function sanitize( $input )
    {
        $new_input = array();
        if( isset( $input['id_number'] ) )
            $new_input['id_number'] = absint( $input['id_number'] );

        if( isset( $input['background-color'] ) )
            $new_input['background-color'] = sanitize_text_field( $input['background-color'] );

        if( isset( $input['tag-color'] ) )
            $new_input['tag-color'] = sanitize_text_field( $input['tag-color'] );

        if( isset( $input['header-color'] ) )
            $new_input['header-color'] = sanitize_text_field( $input['header-color'] );

        if( isset( $input['text-color'] ) )
            $new_input['text-color'] = sanitize_text_field( $input['text-color'] );
            
        return $new_input;
    }

    /** 
     * Print the Section text
     */
    public function print_section_info()
    {
        print 'Enter your settings below:';
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function id_number_callback()
    {
        printf(
            '<input type="text" id="id_number" name="flexslider_option_name[id_number]" value="%s" />',
            isset( $this->options['id_number'] ) ? esc_attr( $this->options['id_number']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function background_color_callback()
    {
        printf(
            '<input type="text" id="background-color" name="flexslider_option_name[background-color]" value="%s" class="cpa-color-picker"/>',
            isset( $this->options['background-color'] ) ? esc_attr( $this->options['background-color']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function tag_color_callback()
    {
        printf(
            '<input type="text" id="tag-color" name="flexslider_option_name[tag-color]" value="%s" class="cpa-color-picker"/>',
            isset( $this->options['tag-color'] ) ? esc_attr( $this->options['tag-color']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function header_color_callback()
    {
        printf(
            '<input type="text" id="header-color" name="flexslider_option_name[header-color]" value="%s" class="cpa-color-picker"/>',
            isset( $this->options['header-color'] ) ? esc_attr( $this->options['header-color']) : ''
        );
    }

    /** 
     * Get the settings option array and print one of its values
     */
    public function text_color_callback()
    {
        printf(
            '<input type="text" id="text-color" name="flexslider_option_name[text-color]" value="%s" class="cpa-color-picker"/>',
            isset( $this->options['text-color'] ) ? esc_attr( $this->options['text-color']) : ''
        );
    }
}

if( is_admin() )
    $flexslider_settings_page = new FlexSliderSettingsPage();

/**
 * Enqueue color picker on admin screen
 */
add_action( 'admin_enqueue_scripts', 'flexslider_add_color_picker' );
function flexslider_add_color_picker( $hook ) {
 
    if( is_admin() ) { 
     
        // Add the color picker css file       
        wp_enqueue_style( 'wp-color-picker' ); 
         
        // Include our custom jQuery file with WordPress Color Picker dependency
        wp_enqueue_script( 'custom-script-handle', plugins_url( 'jquery.custom.js', __FILE__ ), array( 'wp-color-picker' ), false, true ); 
    }
}

/**
 * Callback function to display options
 */
function flexslider_option_callback( $option = '' ) {
		if(!isset($option)){ 
			return; 
		}
		$flexslider_options = get_option( 'flexslider_option_name', '' ) ;
      return esc_attr( $flexslider_options[ $option ] );
  }
?>

<!--
-->

<?php if(flexslider_option_callback( 'background-color' )) {?>
.flexslider {
  background-color: <?php echo flexslider_option_callback( 'background-color' );?>;
} 
<?php } ?>

<?php if(flexslider_option_callback( 'header-color' )) {?>
.listing-header {
  color: <?php echo flexslider_option_callback( 'header-color' );?>;
} 
<?php } ?>

<?php if(flexslider_option_callback( 'text-color' )) {?>
.tag {
  color: <?php echo flexslider_option_callback( 'text-color' );?>;
} 
.tag:after {
  background-color: <?php echo flexslider_option_callback( 'text-color' );?>;
}
<?php } ?>

<?php if(flexslider_option_callback( 'tag-color' )) {?>
.tag {
  background-color:  <?php echo flexslider_option_callback( 'tag-color' );?>;
  border-left: 1px solid  <?php echo flexslider_option_callback( 'tag-color' );?>;
}
.tag:before {
  	 border-right: 19px solid  <?php echo flexslider_option_callback( 'tag-color' );?>;
}
<?php } ?>

