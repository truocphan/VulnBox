<div class="htm-weather-wraper" style="background:transparent url('<?php echo esc_url(HTMEGA_ADDONS_PL_URL . 'assets/images/weather/weather-background.jpg');?>') no-repeat center center/cover;">
    <h3 class="htm-weather-title"><?php echo $settings['overridetitle'] ? esc_html($settings['overridetitle']) : esc_html($weather['name']) ; ?></h3>
    <div class="htm-current-weather">
        <div class="htm-current-1">
            <p class="week-days"><?php echo date("l"); ?></p>
            <p class="current-date"><?php echo date("F j, Y"); ?></p>
            <p class="wind">Wind <?php echo esc_html( $weather['current']['wind_speed'] ); ?> <?php echo esc_html($weather['current']['wind_speed_text']); ?></p>
            <span class="humidity"><i class="wi wi-raindrop"></i><span class="humidity-percent"><?php echo esc_html($weather['current']['humidity']); ?>%</span></span>
        </div>
        <div class="htm-current-2">
            <?php echo wp_kses_post( $weather['current']['icon'] ); ?>
            <p class="temp-type"><?php echo esc_html( $weather['current']['description'] ); ?></p>
            <p class="temp-num"><?php echo esc_html( $weather['current']['temp'] ); ?><sup>&deg;</sup><?php echo esc_html( $units ); ?></p>
        </div>
    </div>
</div>