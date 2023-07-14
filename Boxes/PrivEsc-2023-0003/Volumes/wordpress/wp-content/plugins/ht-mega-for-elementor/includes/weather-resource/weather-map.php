<?php

namespace Elementor\HtMega\Weather;

class WeatherMap{

    /**
	 * API info URL
	 */
    private static $open_wateher_url = 'https://api.openweathermap.org/data/2.5/';

    /**
     * Send Api request
     * @return [Weather data]
     */
    public static function get_wather_data($weather_attr){

        if( !is_array($weather_attr) ) return;
        if(!isset($weather_attr['api_key']) && empty($weather_attr['api_key'])) return;


        $weather_manage    = weatherResource::instance();
        $appid             = $weather_attr['api_key'];
        $locale            = $weather_attr['language'];
        $units             = strtolower($weather_attr['units']) == 'c' ? 'metric' : 'imperial';
        $forcast_day       = $weather_attr['forecast_days'];
        $data_transiant    = 'weather-data-'.$appid;
        $pre_units         = get_transient( 'weather-unit' ) ? get_transient( 'weather-unit' ) : 'metric';
        $pre_giolocation   = get_transient( 'gio-location' ) ? get_transient( 'gio-location' ) : '';

        //delete cash if units changes
        if($units != $pre_units || ( is_array($pre_giolocation) && $weather_attr['custom_long'] != $pre_giolocation['lon'] && $weather_attr['custom_lat'] != $pre_giolocation['lat'] ) ){
            delete_transient($data_transiant);
        }

        //Get data form cash
        if( get_transient( $data_transiant ) ) return get_transient( $data_transiant );

        //Get country gio location
        if(empty($weather_attr['custom_lat']) && empty($weather_attr['custom_long'])){
            $gio_location = $weather_manage->get_late_long();
        }else{
            $country_name = $weather_manage->get_country_by_lat_long($weather_attr['custom_lat'], $weather_attr['custom_long'], $appid);
            if($country_name){
                $gio_location = [
                    'lat' => $weather_attr['custom_lat'],
                    'lon' => $weather_attr['custom_long'],
                    'city' => $country_name
                ];
            }else{
                return "Invalid latitude or longitude";
            }

        }

        if( empty($gio_location['lat']) && empty($gio_location['lon']) ) return;

        //Generate Api url
        $url = self::$open_wateher_url . 'onecall?&lat='. $gio_location['lat'] .'&lon='. $gio_location['lon'] .'&lang=' . $locale . '&units=' . $units . '&appid='. $appid;
        
        $city_weather_request = wp_remote_get( $url );

        if ( is_wp_error( $city_weather_request ) || 200 !== (int) wp_remote_retrieve_response_code( $city_weather_request ) ) {
            return "Invalid Api key.Please enter a valid Api key.";
        }

        $city_weather = json_decode( $city_weather_request['body']);

        if( isset($city_weather->cod) && $city_weather->cod == 404 ){
            return $city_weather->message ; 
        }

        $weather_data['current'] = array();

        // set citey name
        if( !empty($gio_location['city']) ) $weather_data['name'] = $gio_location['city'];

        //get current data
        if(isset($city_weather->current)){
            $weather_manage->set_weather_current_data( $weather_data, $city_weather->current,  $city_weather->timezone);
        }

        if( $forcast_day > 0 ){
            if( isset($city_weather->daily) ){
                $forecast = array();
                $forecast_items = (array) $city_weather->daily;
                foreach($forecast_items as $forecast_item){
                    $weather_manage->set_weather_daily_forcast($forecast, $forecast_item );
                }
                $weather_data['forecast'] = $forecast;
            }
        }
        //Set The Transient, cash for one hour
        set_transient( $data_transiant, $weather_data, 1800 ); 
        set_transient( 'weather-unit', $units); 
        set_transient( 'gio-location', $gio_location); 
        return $weather_data;
    }
}
