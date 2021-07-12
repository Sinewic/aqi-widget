<?php
/**
 * Plugin Name:       AQI Widget
 * Description:       Air quality index. Show weather details in a widget. In the Weather Menu, you can select the cities that you want to display in your widget. The weather data will be provided by a 3rd-party API endpoint.
 * Version:           1.0
 * Requires at least: 5.7
 * Requires PHP:      7.4
 * Author:            Saikaew W
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       aqi_widget  
 */

defined( 'ABSPATH' ) || exit;

new AQIWidget;

/**
 * Main AQIWidget Class.
 *
 * @class AQIWidget
 */
class AQIWidget {
    /**
     * AQIWidget Constructor.
     */
	public function __construct() {
		$this->define_constants();
        $this->includes();
		$this->init_hooks();
	}
    
    /**
     * Define AQIWidget Constants.
     *
     * @return void
     */
    public function define_constants() {
        define( 'AQI_MINIMUM_WP_VERSION', '5.7' );
        define( 'AQI_MINIMUM_PHP_VERSION', '7.4' );
        define( 'AQI_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );
        define( 'AQI_PLUGIN_URL', plugin_dir_url( __FILE__ ) );
        define( 'AQI_PLUGIN_FILE', __FILE__ );
    }

    /**
     * Includes AQIWidget files.
     *
     * @return void
     */
    public function includes() {
        require_once dirname( AQI_PLUGIN_FILE ) . '/aqi-widget.php';
        require_once dirname( AQI_PLUGIN_FILE ) . '/includes/class-aqi-widget.php';
        require_once dirname( AQI_PLUGIN_FILE ) . '/includes/class-aqi-widget-api.php';        
        require_once dirname( AQI_PLUGIN_FILE ) . '/includes/aqi-widget-settings.php';
        require_once dirname( AQI_PLUGIN_FILE ) . '/includes/class-aqi-check-version.php';        
    }

    /**
     * init Hooks.
     *
     * @return void
     */
    public function init_hooks() {
        register_activation_hook( __FILE__, array( $this, 'activate' ) );
		register_deactivation_hook( __FILE__, array( $this, 'deactivate' ) );
        add_action( 'widgets_init', 'weather_load_widget' );    //includes/class-aqi-widget-2.php
        add_action( 'admin_init', 'aqi_widget_settings_init' ); //includes/class-aqi-widget-settings.php
        add_action( 'admin_menu', 'aqi_widget_options_page' );  //includes/class-aqi-widget-settings.php
        add_action( 'wp_enqueue_scripts', 'plugin_source' );

        /**
         * CSS and JavaScript.
         *
         * @return void
         */
        function plugin_source() {
            //CSS
            wp_register_style( 'aqi', AQI_PLUGIN_URL.'includes/css/style.css', array(), '1.0.0' );
            wp_enqueue_style( 'aqi' );  
            
        }

        
    }    
    

}


?>
