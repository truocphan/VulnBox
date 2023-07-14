<?php 

namespace Elementor\HtMega\Weather;

class weatherResource{

    /**
     * [$_instance]
     * @var null
     */
    private static $_instance = null;

    /**
     * [instance] Initializes a singleton instance
     * @return [HTMega_Addons_Elementor]
     */
    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
	 * wind direction
	 */
    private  $wind_label = array ( 'N','NNE', 'NE', 'ENE', 'E', 'ESE', 'SE', 'SSE', 'S', 'SSW', 'SW', 'WSW', 'W', 'WNW', 'NW', 'NNW');

    /**
	 * Short weekday name
	 */
    private  $week_days = array( 'Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat' );

    public function get_late_long(){

        if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
            //check ip from share internet
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
            //to check ip is pass from proxy
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }

        $country_info = wp_remote_get('https://freegeoip.app/json/'.$ip);
        if ( is_wp_error ( $country_info ) ) return " ";

        $country_info = json_decode( wp_remote_retrieve_body( $country_info ) );

        if( (isset($country_info->latitude ) && $country_info->latitude !== 0) && ( isset( $country_info->longitude) && $country_info->longitude !== 0 ) ){
        //if($country_info->latitude !== 0 && $country_info->longitude !== 0 ){
            return array(
                'lat' => $country_info->latitude,
                'lon' => $country_info->longitude,
                'city' => $country_info->country_name
            );
        }else{
            $default_country_info = wp_remote_get('http://ip-api.com/json/');
            if ( is_wp_error ( $default_country_info ) ) return " ";

            $default_country_info = json_decode( wp_remote_retrieve_body( $default_country_info ) );
            return array(
                'lat' => $default_country_info->lat,
                'lon' => $default_country_info->lon,
                'city' => $default_country_info->city
            );
        }

    }

    public function get_country_by_lat_long($lat, $long, $appid){

        $url = 'https://api.openweathermap.org/data/2.5/weather?&lat='. $lat .'&lon='. $long .'&appid='. $appid;

        $city= wp_remote_get( $url );

        if ( is_wp_error( $city ) || 200 !== (int) wp_remote_retrieve_response_code( $city ) ) {
            return 0;
        }

        $city_name = json_decode( $city['body']);

        if( isset($city_name->cod) && $city_name->cod == 404 ){
            return 0;
        }

        return $city_name->name;
    }

    public function set_weather_current_data( &$weather_data, $city_weather, $city_time_zone ){

        $time_zone = $city_time_zone;

        // set main
        $weather_data['current']['temp'] 		= round($city_weather->temp);
        $weather_data['current']['humidity'] 	= round($city_weather->humidity);
        $weather_data['current']['pressure'] 	= round($city_weather->pressure);
			

        // set sunset and sunrise
        //$weather_data['current']['sunrise'] = $city_weather->sunrise;
        $weather_data['current']['sunrise_time'] = $this->get_country_date_time($time_zone, $city_weather->sunrise);
        
        //$weather_data['current']['sunset'] = $city_weather->sunset;
        $weather_data['current']['sunset_time'] =  $this->get_country_date_time($time_zone, $city_weather->sunset);

        // set wind
        $wind_speed 		=  round($city_weather->wind_speed);
        $wind_direction 	=  fmod((($city_weather->wind_deg + 11) / 22.5),16);
        $wind_speed_text 	= __('m/s', 'htmega-addons');
        
        $weather_data['current']['wind_speed'] 					= $wind_speed;		
        $weather_data['current']['wind_direction'] 				= $this->wind_label[ $wind_direction ];
        $weather_data['current']['wind_direction_number'] 		= $wind_direction;
        $weather_data['current']['wind_speed_text'] 			= $wind_speed_text;

        // set weather
		if( isset($city_weather->weather[0]) && $city_weather->weather[0] ){
			$current_weather_details 					= $city_weather->weather[0];
			$weather_data['current']['condition_code'] 	= $current_weather_details->id;
			$weather_data['current']['icon'] 			= $this->get_regular_weather_icon($current_weather_details->id,$current_weather_details->icon);
			$weather_data['current']['icon-code'] 		= $current_weather_details->icon;
			$weather_data['current']['main']	        = $current_weather_details->main;
			$weather_data['current']['description']	    = $current_weather_details->description;
		}
    }

    public function set_weather_daily_forcast( &$forecast, $forecast_item ){
         	
        $day = array();
        $day['timestamp'] 		= $forecast_item->dt;
        $day['day_of_week'] 	= $this->week_days[ date('w', $forecast_item->dt) ];
        
        // TEMPS
        $day['high']  			= round($forecast_item->temp->max);
        $day['low']  			= round($forecast_item->temp->min);
        $day['temp'] 			= round($forecast_item->temp->day);
        
        // EXTRAS
        $day['pressure'] 		= isset($forecast_item->pressure) ? round($forecast_item->pressure) : false;
        $day['humidity'] 		= isset($forecast_item->humidity) ? round($forecast_item->humidity) : false;
        $day['wind_speed'] 		= isset($forecast_item->wind_speed) ?  $forecast_item->wind_speed : false;
        $day['wind_direction'] 	= isset($forecast_day->wind_deg) ? $this->wind_label[ fmod((($forecast_day->wind_deg + 11) / 22.5),16) ]  : false;
            
        // WEATHER DESCRIPTIONS
        if( isset($forecast_item->weather[0]) ){
            $w = $forecast_item->weather[0];
            $day['condition_code'] = $w->id;
            $day['description'] = $w->description;           
            $day['icon'] = $this->get_regular_weather_icon( $w->id , $w->icon );
            $day['icon-code'] = $w->icon ;
        }
        
        $forecast[] = $day;

    }

    private function regulare_weather_icon(){
        return [
            'thunderstorm' => '<i class="wi wi-thunderstorm"></i>',
            'drizzle' => '<i class="wi wi-cloudy"></i>',
            'rain-day' => '<i class="wi wi-day-rain"></i>',
            'rain-night' => '<i class="wi wi-night-alt-hail"></i>',
            'snow' => '<i class="wi wi-snow"></i>',
            'mist' => '<i class="wi wi-rain-mix"></i>',
            'smoke' => '<i class="wi wi-smoke"></i>',
            'haze' => '<i class="wi wi-day-haze"></i>',
            'dust' => '<i class="wi wi-dust"></i>',
            'ash' => '<i class="wi wi-volcano"></i>',
            'squalls' => '<i class="wi wi-cloudy-windy"></i>',
            'tornado' => '<i class="wi wi-tornado"></i>',
            'clear-day' => '<i class="wi wi-day-sunny"></i>',
            'clear-night' => '<i class="wi wi-night-clear"></i>',
            'few-clouds-day' => '<i class="wi wi-day-cloudy"></i>',
            'few-clouds-night' => '<i class="wi wi-night-alt-cloudy"></i>',
            'scattered-clouds' => '<i class="wi wi-cloud"></i>',
            'broken-clouds' => '<i class="wi wi-cloudy"></i>',
        ];
    }

    private function get_regular_weather_icon($condition_code,$icon){

        $regular_weather_icon = $this->regulare_weather_icon();        

        if(200 <= $condition_code && 232 >= $condition_code ) return $regular_weather_icon['thunderstorm'];
        if(300 <= $condition_code && 321 >= $condition_code ) return $regular_weather_icon['drizzle'];
        if(500 <= $condition_code && 531 >= $condition_code ) return strpos($icon,'d') ? $regular_weather_icon['rain-day'] : $regular_weather_icon['rain-night'];
        if(600 <= $condition_code && 622 >= $condition_code ) return $regular_weather_icon['snow'];

        if($condition_code == 701) return $regular_weather_icon ['mist']; 
        if($condition_code == 711) return $regular_weather_icon ['smoke'];
        if($condition_code == 721) return $regular_weather_icon ['haze'];
        if($condition_code == 731) return $regular_weather_icon ['dust'];
        if($condition_code == 741) return $regular_weather_icon ['fog'];
        if($condition_code == 751) return $regular_weather_icon ['sand'];
        if($condition_code == 761) return $regular_weather_icon ['dust'];
        if($condition_code == 762) return $regular_weather_icon ['ash'];
        if($condition_code == 771) return $regular_weather_icon ['squalls'];
        if($condition_code == 781) return $regular_weather_icon ['tornado'];

        if($condition_code == 800) return strpos($icon,'d') ? $regular_weather_icon ['clear-day'] : $regular_weather_icon ['clear-night'];
        if($condition_code == 801) return strpos($icon,'d') ? $regular_weather_icon ['few-clouds-day'] : $regular_weather_icon ['few-clouds-night'];
        if($condition_code == 802) return $regular_weather_icon ['scattered-clouds'];
        if($condition_code == 803 || $condition_code == 804) return $regular_weather_icon ['broken-clouds'];
	

    }

    private function get_country_date_time($time_zone, $suntime){
        $tz = $time_zone;
        $timestamp = $suntime;
        $dt = new \DateTime("now", new \DateTimeZone($tz)); 
        $dt->setTimestamp($timestamp); 
        return $dt->format('h:i a');
    }
}