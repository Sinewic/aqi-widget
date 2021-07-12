<?php

defined( 'ABSPATH' ) || exit;

/**
 * AQIapi Class.
 *
 * @class AQIapi
 */
class AQIapi {
    
    private const URL = "http://api.airvisual.com/v2/";
    private const API_KEY = "b6922cd9-2e07-4808-8f0d-da9f3d81ff93";

    /** 
     * @param [array]     $data which the request is sent.
     * 
     * @return [string]   If error.
     * @return [array]    $details.
     */
    public static function get_aqi( $data ) {                    

        $country    = $data['country'];  
        $state      = $data['state'];   
        $city       = $data['city'];     
        $city       = str_replace(' ', '%20', $city);           

        if( !empty($country) &&  !empty($state) &&  empty($city) ){

            $url    = self::URL."cities?state=".$state."&country=".$country."&key=".self::API_KEY;             
            //$url    = AQI_PLUGIN_DIR."includes/test-data/cities.json"; //test php

        }elseif( !empty($country) &&  !empty($state) &&  !empty($city) ) {
            
            $url    = self::URL."city?city=" .$city. "&state=" .$state. "&country=" .$country. "&key=".self::API_KEY;            
            //$url    = AQI_PLUGIN_DIR."includes/test-data/city-data.json";  // test php

        }
        
        $method = 'GET';
        
        $request = wp_remote_get( $url, $method );

        if ( is_wp_error( $request ) ) {

            $error_message = $request->get_error_message();

            return "Something went wrong :<br> $error_message, Please try again!";

        } else {

            $data = wp_remote_retrieve_body( $request );            
            $details = json_decode( $data, true );

            return $details ;            
        }       

    }    

}