<div class="htm-weather-wraper layout-2">
    <div class="htm-current-weather">
        <div class="htm-current-1">
            <h3 class="htm-weather-title"><?php echo $settings['overridetitle'] ? esc_html($settings['overridetitle']): esc_html($weather['name']) ; ?></h3>
        </div>
    </div>
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
                <p class="week-day"><?php echo esc_html($weather['forecast'][$i]['day_of_week'])?></p>
                <?php echo wp_kses_post( $weather['forecast'][$i]['icon'] ); ?>
                <p class="forcast-temp"><?php echo esc_html($weather['forecast'][$i]['temp']); ?><sup>&deg;</sup><?php echo esc_html( $units); ?></p>
            <?php endif;?>
        </div>
        <?php 
            endfor; 
        ?>
    </div>
</div>