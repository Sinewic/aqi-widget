<?php
/**
 * weather_load_widget function - Register and load the widget
 *
 * @return void
 */ 
function weather_load_widget() {
    register_widget( 'weatherWidget' );
}

/**
 * AQI widget - The widget Class
 * 
 * @class weatherWidget
 */ 
class weatherWidget extends WP_Widget {

    function __construct() {

        parent::__construct(
            // Base ID of widget
            'weather_widget',      
            __('Weather Widget', 'weather_widget_domain'),      
            array( 'description' => __( 'Show Weather Details in a Widget', 'weather_widget_domain' ), )
        );

    }

    /**
     * widget function - Creating widget front-end view.
     *
     * @param [array] $args
     * @param [array] $instance
     * @return void
     */
    public function widget( $args, $instance ) {
        $title = apply_filters( 'widget_title', $instance['title'] );

        // Before and after widget arguments are defined by themes
        $string  = $args['before_widget'];

        if ( ! empty( $title ) ) echo $args['before_title'] . $title . $args['after_title'];
       
        
        $options = get_option( 'aqi_widget_options' );
        
        $city = $options['aqi_widget_field_city']; 

        $data  = array( 
            'country'   => 'Thailand',
            'state'     => 'Bangkok',
            'city'      => $city
        );

        $aqi_datas = AQIapi::get_aqi($data); 
        
        if( is_array ( $aqi_datas ) ) {

            $city               = $aqi_datas['data']['city'];
            $weatherTime        = $aqi_datas['data']['current']['weather']['ts'];
            $weatherTemperture  = $aqi_datas['data']['current']['weather']['tp'];
            $valueOfIndex       = $aqi_datas['data']['current']['pollution']['aqius'];

            $weatherTime        = date("l, F d, Y", strtotime($weatherTime));
        
            switch( $valueOfIndex ){

                case ( $valueOfIndex >= 0 && $valueOfIndex <= 50 ) 
                    :   $aqi_color = 'green';
                        $levelsOfCencern = 'Good';
                        break;

                case ( $valueOfIndex >= 51 && $valueOfIndex <= 100 ) 
                    :   $aqi_color = 'yellow';
                        $levelsOfCencern = 'Moderate';
                        break;

                case ( $valueOfIndex >= 101 && $valueOfIndex <= 150 ) 
                    :   $aqi_color = 'orange';
                        $levelsOfCencern = '<span style="font-size:.8rem">Unhealthy for Sensitive Groups</span>';
                        break;

                case ( $valueOfIndex >= 151 && $valueOfIndex <= 200 ) 
                    :   $aqi_color = 'red';
                        $levelsOfCencern = 'Unhealthy';
                        break;
                        
                case ( $valueOfIndex >= 201 && $valueOfIndex <= 300 ) 
                    :   $aqi_color = 'purple';
                        $levelsOfCencern = 'Very Unhealthy';
                        break;
                        
                case ( $valueOfIndex >= 301 ) 
                    :   $aqi_color = 'maroon';
                        $levelsOfCencern = 'Hazardous';
                        break;                    

            }

            $imgURL =  site_url('wp-content/plugins/aqi-widget/assets/img/ic-face-'. $aqi_color . '.svg');
            $string .= '<div>
                            <div class="aqi-time">
                            ' . $weatherTime . '
                            </div>
                            <div class="aqi-city-title">
                                <div>
                                    ' . $city . '
                                </div>
                                <div class="aqi-temperture">
                                ' . $weatherTemperture . '°
                                </div>
                            </div>
                            <div class="aqi-box aqi-result-' . $aqi_color . '">
                                <div class="aqi-img">
                                    <img " src="' . $imgURL . '">
                                </div>
                                <div class="aqi-value">
                                    <div>
                                        <span class="aqi-value-index">' . $valueOfIndex . '</span>
                                        <span class="aqi-us">US AQI</span>
                                    </div>
                                    <div class="aqi-levels-concern">
                                        <span>' . $levelsOfCencern . '</span>
                                    </div>                                
                                </div>                            
                            </div>
                        </div>';

            echo $string;

        }   elseif ( is_string ( $aqi_datas ) ) { 

            esc_html( $aqi_datas );
            
        }

        echo $args['after_widget'];   
    
    } 

    /**
     * form function -  Widget Backend this controls what you see in the Widget UI.
     *                  For this example we are just allowing the widget title to be entered.
     * @param [array] $instance
     * @return void
     */
    public function form( $instance ) {

        if ( isset( $instance[ 'title' ] ) ) {
            $title = $instance[ 'title' ];
        } else {
            $title = __( 'New title', 'aqi_widget' );
        }
        // Widget admin form
        ?>

        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
        </p>

        <?php
    }
    
    /**
     * update function - Updating widget, replacing old instances with new.
     *
     * @param [array] $new_instance
     * @param [array] $old_instance
     * @return void
     */
    public function update( $new_instance, $old_instance ) {

        $instance = array();
        $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

        return $instance;

    }

} 

?>