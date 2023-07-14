<div class="htm-weather-wraper layout-4">
    <h3 class="htm-weather-title"><?php echo $settings['overridetitle'] ? $settings['overridetitle'] : $weather['name'] ; ?></h3>
    <div class="htm-current-weather">
        <?php if($hide_current_stats):?>
        <div class="htm-current-1">
            <p class="week-days"><?php echo date("l"); ?></p>
            <p class="current-date"><?php echo date("F j, Y"); ?></p>
            <p class="wind">Wind <?php echo esc_html( $weather['current']['wind_speed'] ); ?> <?php echo esc_html( $weather['current']['wind_speed_text']); ?></p>
            <span class="humidity"><i class="wi wi-raindrop"></i><span class="humidity-percent"><?php echo esc_html( $weather['current']['humidity'] ); ?>%</span></span>
        </div>
        <div class="htm-current-2">
            <?php echo $weather['current']['icon']; ?>
            <p class="temp-type"><?php echo esc_html( $weather['current']['description'] ); ?></p>
            <p class="temp-num"><?php echo esc_html( $weather['current']['temp'] ); ?><sup>&deg;</sup><?php echo esc_html($units); ?></p>
        </div>
        <?php endif; ?>
    </div>
    <?php if($hide_forcast): ?>
        <div class="htm-weather-forcast">
            <?php 
                $forcast_days = (int) $settings['forecast'];
                for($i=0; $i < $forcast_days; $i++): 
            ?>
            <div class="forcast-day">
                <?php if($i == 0): ?>
                    <p class="week-day"><?php echo __('Today','htmega-addons'); ?></p>
                    <?php echo wp_kses_post( $weather['current']['icon'] ); ?>
                    <p class="forcast-temp"><?php echo esc_html( $weather['current']['temp'] ); ?><sup>&deg;</sup><?php echo esc_html( $units ); ?></p>
                <?php else: ?>
                    <p class="week-day"><?php echo esc_html($weather['forecast'][$i]['day_of_week']); ?></p>
                    <?php echo wp_kses_post( $weather['forecast'][$i]['icon'] ); ?>
                    <p class="forcast-temp"><?php echo esc_html( $weather['forecast'][$i]['temp'] ); ?><sup>&deg;</sup><?php echo  esc_html( $units ); ?></p>
                <?php endif;?>
            </div>
            <?php 
                endfor; 
            ?>
        </div>
    <?php endif ?>
</div>