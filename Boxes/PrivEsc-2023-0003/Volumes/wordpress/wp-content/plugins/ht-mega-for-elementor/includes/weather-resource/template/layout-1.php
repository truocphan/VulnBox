<div class="htm-weather-wraper">
    <h3 class="htm-weather-title"><?php echo $settings['overridetitle'] ? esc_html($settings['overridetitle']) : esc_html($weather['name']) ; ?></h3>
    <div class="htm-current-weather">
        <?php if($hide_current_stats):?>
        <div class="htm-current-1">
            <p class="week-days"><?php echo date("l"); ?></p>
            <p class="current-date"><?php echo date("F j, Y"); ?></p>
            <p class="wind">Wind <?php echo esc_html($weather['current']['wind_speed']); ?> <?php echo esc_html($weather['current']['wind_speed_text']); ?></p>
            <span class="humidity"><i class="wi wi-raindrop"></i><span class="humidity-percent"><?php echo esc_html($weather['current']['humidity']); ?>%</span></span>
        </div>
        <div class="htm-current-2">
            <?php echo wp_kses_post($weather['current']['icon']); ?>
            <p class="temp-type"><?php echo esc_html($weather['current']['description']); ?></p>
            <p class="temp-num"><?php echo esc_html($weather['current']['temp']); ?><sup>&deg;</sup><?php echo esc_html($units); ?></p>
        </div>
        <?php endif; ?>
    </div>
    <?php if($hide_sun_stats): ?>
    <div class="htm-weather-sun">
        <div class="htm-weather-sun-wraper">
            <div class="sun-forcast sun-rise">
                <span><i class="wi wi-sunrise"></i> Sunrise <?php echo esc_html($weather['current']['sunrise_time']); ?></span>
                <span>Max: <?php echo esc_html( $current_temp_max ); ?><sup>&deg;</sup><?php echo $units; ?></span>
            </div>
            <div class="sun-forcast sun-set">
                <span><i class="wi wi-sunset"></i> Sunset <?php echo esc_html($weather['current']['sunset_time']); ?></span>
                <span>Min: <?php echo esc_html( $current_temp_min ); ?><sup>&deg;</sup><?php echo esc_html( $units ); ?></span>
            </div>
        </div>
    </div>
    <?php endif; ?>
    <?php if($hide_forcast): ?>
        <div class="htm-weather-forcast">
            <?php 
                $forcast_days = (int) $settings['forecast'];
                for($i=0; $i < $forcast_days; $i++): 
            ?>
            <div class="forcast-day">
                <?php if($i == 0): ?>
                    <p class="week-day"><?php echo esc_html__('Today','htmega-addons'); ?></p>
                    <?php echo wp_kses_post( $weather['current']['icon'] ); ?>
                    <p class="forcast-temp"><?php echo esc_html($weather['current']['temp']); ?><sup>&deg;</sup><?php echo esc_html( $units); ?></p>
                <?php else: ?>
                    <p class="week-day"><?php echo esc_html( $weather['forecast'][$i]['day_of_week'] ); ?></p>
                    <?php echo wp_kses_post( $weather['forecast'][$i]['icon'] ); ?>
                    <p class="forcast-temp"><?php echo esc_html($weather['forecast'][$i]['temp']); ?><sup>&deg;</sup><?php echo esc_html($units); ?></p>
                <?php endif;?>
            </div>
            <?php 
                endfor; 
            ?>
        </div>
    <?php endif ?>
</div>