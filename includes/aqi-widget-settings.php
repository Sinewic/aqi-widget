<?php
 
/**
 * AQI widget - Admin option and settings 
 */
function aqi_widget_settings_init() {    

    // Register a new setting for "aqi_widget" page.
    register_setting( 'aqi_widget', 'aqi_widget_options' ); 
 
    // Register a new section in the "aqi_widget" page.
    add_settings_section(
        'aqi_widget_section_developers',
        __( 'Air Quality index' , 'aqi_widget' ), 
        'aqi_widget_section_developers_callback',
        'aqi_widget'
    );
 
    // Register a new field in the "aqi_widget_section_developers" section, inside the "aqi_widget" page.
    add_settings_field(
        'aqi_widget_field_city', // Use $args' label_for to populate the id inside the callback.                                
        __( 'City / District / Area', 'aqi_widget' ),
        'aqi_widget_field_city_cb',
        'aqi_widget',
        'aqi_widget_section_developers',
        array(
            'label_for'         => 'aqi_widget_field_city',
            'class'             => 'aqi_widget_row',
            'aqi_widget_custom_data' => 'custom',
        )
    );
}
 
/**
 * Developers section Callback function.
 *
 * @param array $args  The settings array, defining title, id, callback.
 */
function aqi_widget_section_developers_callback( $args ) {
    ?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>">
        <?php esc_html_e( 'The weather widget showing the weather in Thailand, Bangkok.', 'aqi_widget' ); ?>
    </p>
    <?php
}
 
/**
 * City field callback function.
 *
 * @param array $args
 * @var array $cities
 */
function aqi_widget_field_city_cb( $args ) {    

    // Get the value of the setting we've registered with register_setting()
    $options = get_option( 'aqi_widget_options' );
      
    // Get cities
    $data  = array( 
        'country'   => 'Thailand',
        'state'     => 'Bangkok',
        'city'      =>''
    );

    $cities = AQIapi::get_aqi($data);
    
    if( is_array ( $cities ) ){
    ?>
    <select
            id="<?php echo esc_attr( $args['label_for'] ); ?>"
            data-custom="<?php echo esc_attr( $args['aqi_widget_custom_data'] ); ?>"
            name="aqi_widget_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
            
        <?php foreach( $cities['data'] as $city ) : ?>   

        <option 
            value="<?php echo $city['city']; ?>" 
            <?php echo isset( $options[ $args['label_for'] ] ) ? 
            ( selected( $options[ $args['label_for'] ], $city['city'], false ) ) : ( '' ); ?>>
            <?php esc_html_e( $city['city'], 'aqi_widget' ); ?>
        </option>

        <?php endforeach; ?>

    </select>       
    <p class="description">
        <?php esc_html_e( 'Select the city that you want to display in your widget', 'aqi_widget' ); ?>
    </p> 

    <?php
    }  elseif ( is_string ( $cities ) ) { 

        esc_html( print_r($cities));
        
    }
}
 
/**
 * Add the top level menu page.
 */
function aqi_widget_options_page() {
    add_menu_page(
        'AQI Widget',
        'AQI Widget',
        'manage_options',
        'aqi_widget',
        'aqi_widget_options_page_html'
    );
} 
 
/**
 * Top level menu callback function
 */
function aqi_widget_options_page_html() {

    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }
 
    // add error/update messages 
    // check if the user have submitted the settings. WordPress will add the "settings-updated" $_GET parameter to the url
    if ( isset( $_GET['settings-updated'] ) ) {

        // add settings saved message with the class of "updated"
        add_settings_error( 'aqi_widget_messages', 
                            'aqi_widget_message', 
                            __( 'Settings Saved', 
                            'aqi_widget' ), 
                            'updated' );

    }
 
    // show error/update messages
    settings_errors( 'aqi_widget_messages' );

    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php

            // output security fields for the registered setting "aqi_widget"
            settings_fields( 'aqi_widget' );

            // output setting sections and their fields
            // (sections are registered for "aqi_widget", each field is registered to a specific section)
            do_settings_sections( 'aqi_widget' );

            // output save settings button
            submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
    <?php
}


?>
