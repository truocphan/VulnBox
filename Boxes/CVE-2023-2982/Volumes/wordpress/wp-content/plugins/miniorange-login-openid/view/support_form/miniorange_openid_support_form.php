<?php
require_once ABSPATH . 'wp-includes/pluggable.php';

echo '
<style>
/* The Modal (background) */
.modal_support_form {
	display: none; /* Hidden by default */
	position: fixed; /* Stay in place */
	z-index: 1; /* Sit on top */
	padding-top: 3%; /* Location of the box */
	left: 0;
	top: 0;
	width: 100%; /* Full width */
	height: 100%; /* Full height */
	overflow: auto; /* Enable scroll if needed */
	background-color: rgb(0,0,0); /* Fallback color */
	background-color: rgba(0,0,0,0.8); /* Black w/ opacity */
}
/* Modal Content */
.modal-content_support_form {
	background-color: #fefefe;
	padding: 35px;
	border: 3px solid black;
	width: 30%;
	height: auto;
	float: right;
	margin-right: 1%;
}

/* The Close Button */
.mo_support_close {
	color: #aaaaaa;
	float: right;
	font-size: 28px;
	font-weight: bold;
	
}

.mo_support_close:hover,
.mo_support_close:focus {
	color: #000;
	text-decoration: none;
	cursor: pointer;
}

</style>
    <div id="myModal" class="modal_support_form">
        <div id="mo_support_form" class="modal-content_support_form" >
            <span style="margin-top: -14px;padding-left: 2%;" class="mo_support_close">&times;</span>
            <h1>SUPPORT</h1>
            <p style="padding-left: 4px;">Need any help? <br>
            Just send us a query so that we can help you.</p>
            <form id="myForm" method="post" action="">
                <input type="hidden" name="option" value="mo_openid_contact_us_query_option" />
                <input type="hidden" name="mo_openid_contact_us_nonce" value="' . esc_attr( wp_create_nonce( 'mo-openid-contact-us-nonce' ) ) . '"/>
                <table class="mo_openid_settings_table ">
                    <tr style="width: 50%;float: left;">
                        <td >
                            <input style="padding:2%;border:none;box-shadow:none;border-bottom-style: solid;border-bottom-color: cornflowerblue;" type="email"  required placeholder="Enter your Email" name="mo_openid_contact_us_email" value="' . esc_attr( get_option( 'mo_openid_admin_email' ) ) . '"></td>
                    </tr>
                    <tr style="width: 50%;float: left;">
                        <td><input style="height: 39px;border:none;box-shadow:none;border-bottom-style: solid;border-bottom-color: cornflowerblue;" type="tel" id="contact_us_phone" placeholder="Enter your phone number with country code (+1)" class="mo_openid_table_contact" name="mo_openid_contact_us_phone" value="' . esc_attr( get_option( 'mo_openid_admin_phone' ) ) . '"></td>
                    </tr>
                    
                    <tr>
                        <td><textarea style="width:100%;padding:2%;border:none;box-shadow:none;border-bottom-style: solid;border-bottom-color: cornflowerblue;" class="mo_openid_table_contact " onkeypress="mo_openid_valid_query(this)" onkeyup="mo_openid_valid_query(this)" placeholder="Write your query here" onblur="mo_openid_valid_query(this)" required name="mo_openid_contact_us_query" rows="4" style="resize: vertical;" id="mo_openid_support_msg"></textarea></td>
                    </tr>
                     <tr>
                        <td><textarea hidden name="mo_openid_feature_plan" id = "feature_plan"></textarea></td>
                    </tr>
                </table>
                <br>
                If you are looking for custom features in the plugin, just drop us an email at <a style="padding-left: 4px;" href="mailto:info@xecurify.com">info@xecurify.com</a>.
                <br>
                <div class="call-setup-div">
                    <h3 class="call-setup-heading">Setup a Call / Screen-share session with miniOrange Technical Team</h3>
                    <label class="switch" style="margin-left: 8px;">
                        <input type="checkbox" style="background: #DCDAD1;" id="mo_sl_setup_call" name="mo_openid_setup_call" value="1">
                        <span class="slider round"></span>
                    </label>
                    <span class="call-setup-label">
                        <b><label for="mo_sl_setup_call"></label>Enable this option to setup a call</b><br><br>
                    </span>

                    <div id="call_setup_dets" class="call-setup-details" style="">
                        <div>
                            <div style="width: 21%; float:left;"><strong>TimeZone<font color="#FF0000">*</font>:</strong></div>
                            <div style="width: 79% !important;">
                                <select id="js-timezone" name="mo_openid_call_timezone" tabindex="-1" class="select2-hidden-accessible" aria-hidden="true" >
                                                                    <option value="" selected="" disabled="">---------Select your timezone--------</option>                                             <option value="Pacific/Niue">(GMT-11:00) Niue Time</option>
                                                                                        <option value="Pacific/Pago_Pago">(GMT-11:00) Samoa Standard Time</option>
                                                                                        <option value="Pacific/Rarotonga">(GMT-10:00) Cook Islands Standard Time</option>
                                                                                        <option value="Pacific/Honolulu">(GMT-10:00) Hawaii-Aleutian Standard Time</option>
                                                                                        <option value="Pacific/Tahiti">(GMT-10:00) Tahiti Time</option>
                                                                                        <option value="Pacific/Marquesas">(GMT-09:30) Marquesas Time</option>
                                                                                        <option value="Pacific/Gambier">(GMT-09:00) Gambier Time</option>
                                                                                        <option value="America/Adak">(GMT-09:00) Hawaii-Aleutian Time (Adak)</option>
                                                                                        <option value="America/Anchorage">(GMT-08:00) Alaska Time - Anchorage</option>
                                                                                        <option value="America/Juneau">(GMT-08:00) Alaska Time - Juneau</option>
                                                                                        <option value="America/Metlakatla">(GMT-08:00) Alaska Time - Metlakatla</option>
                                                                                        <option value="America/Nome">(GMT-08:00) Alaska Time - Nome</option>
                                                                                        <option value="America/Sitka">(GMT-08:00) Alaska Time - Sitka</option>
                                                                                        <option value="America/Yakutat">(GMT-08:00) Alaska Time - Yakutat</option>
                                                                                        <option value="Pacific/Pitcairn">(GMT-08:00) Pitcairn Time</option>
                                                                                        <option value="America/Hermosillo">(GMT-07:00) Mexican Pacific Standard Time</option>
                                                                                        <option value="America/Creston">(GMT-07:00) Mountain Standard Time - Creston</option>
                                                                                        <option value="America/Dawson">(GMT-07:00) Mountain Standard Time - Dawson</option>
                                                                                        <option value="America/Dawson_Creek">(GMT-07:00) Mountain Standard Time - Dawson Creek</option>
                                                                                        <option value="America/Fort_Nelson">(GMT-07:00) Mountain Standard Time - Fort Nelson</option>
                                                                                        <option value="America/Phoenix">(GMT-07:00) Mountain Standard Time - Phoenix</option>
                                                                                        <option value="America/Whitehorse">(GMT-07:00) Mountain Standard Time - Whitehorse</option>
                                                                                        <option value="America/Los_Angeles">(GMT-07:00) Pacific Time - Los Angeles</option>
                                                                                        <option value="America/Tijuana">(GMT-07:00) Pacific Time - Tijuana</option>
                                                                                        <option value="America/Vancouver">(GMT-07:00) Pacific Time - Vancouver</option>
                                                                                        <option value="America/Belize">(GMT-06:00) Central Standard Time - Belize</option>
                                                                                        <option value="America/Costa_Rica">(GMT-06:00) Central Standard Time - Costa Rica</option>
                                                                                        <option value="America/El_Salvador">(GMT-06:00) Central Standard Time - El Salvador</option>
                                                                                        <option value="America/Guatemala">(GMT-06:00) Central Standard Time - Guatemala</option>
                                                                                        <option value="America/Managua">(GMT-06:00) Central Standard Time - Managua</option>
                                                                                        <option value="America/Regina">(GMT-06:00) Central Standard Time - Regina</option>
                                                                                        <option value="America/Swift_Current">(GMT-06:00) Central Standard Time - Swift Current</option>
                                                                                        <option value="America/Tegucigalpa">(GMT-06:00) Central Standard Time - Tegucigalpa</option>
                                                                                        <option value="Pacific/Easter">(GMT-06:00) Easter Island Time</option>
                                                                                        <option value="Pacific/Galapagos">(GMT-06:00) Galapagos Time</option>
                                                                                        <option value="America/Chihuahua">(GMT-06:00) Mexican Pacific Time - Chihuahua</option>
                                                                                        <option value="America/Mazatlan">(GMT-06:00) Mexican Pacific Time - Mazatlan</option>
                                                                                        <option value="America/Boise">(GMT-06:00) Mountain Time - Boise</option>
                                                                                        <option value="America/Cambridge_Bay">(GMT-06:00) Mountain Time - Cambridge Bay</option>
                                                                                        <option value="America/Denver">(GMT-06:00) Mountain Time - Denver</option>
                                                                                        <option value="America/Edmonton">(GMT-06:00) Mountain Time - Edmonton</option>
                                                                                        <option value="America/Inuvik">(GMT-06:00) Mountain Time - Inuvik</option>
                                                                                        <option value="America/Ojinaga">(GMT-06:00) Mountain Time - Ojinaga</option>
                                                                                        <option value="America/Yellowknife">(GMT-06:00) Mountain Time - Yellowknife</option>
                                                                                        <option value="America/Eirunepe">(GMT-05:00) Acre Standard Time - Eirunepe</option>
                                                                                        <option value="America/Rio_Branco">(GMT-05:00) Acre Standard Time - Rio Branco</option>
                                                                                        <option value="America/Bahia_Banderas">(GMT-05:00) Central Time - Bahia Banderas</option>
                                                                                        <option value="America/North_Dakota/Beulah">(GMT-05:00) Central Time - Beulah, North Dakota</option>
                                                                                        <option value="America/North_Dakota/Center">(GMT-05:00) Central Time - Center, North Dakota</option>
                                                                                        <option value="America/Chicago">(GMT-05:00) Central Time - Chicago</option>
                                                                                        <option value="America/Indiana/Knox">(GMT-05:00) Central Time - Knox, Indiana</option>
                                                                                        <option value="America/Matamoros">(GMT-05:00) Central Time - Matamoros</option>
                                                                                        <option value="America/Menominee">(GMT-05:00) Central Time - Menominee</option>
                                                                                        <option value="America/Merida">(GMT-05:00) Central Time - Merida</option>
                                                                                        <option value="America/Mexico_City">(GMT-05:00) Central Time - Mexico City</option>
                                                                                        <option value="America/Monterrey">(GMT-05:00) Central Time - Monterrey</option>
                                                                                        <option value="America/North_Dakota/New_Salem">(GMT-05:00) Central Time - New Salem, North Dakota</option>
                                                                                        <option value="America/Rainy_River">(GMT-05:00) Central Time - Rainy River</option>
                                                                                        <option value="America/Rankin_Inlet">(GMT-05:00) Central Time - Rankin Inlet</option>
                                                                                        <option value="America/Resolute">(GMT-05:00) Central Time - Resolute</option>
                                                                                        <option value="America/Indiana/Tell_City">(GMT-05:00) Central Time - Tell City, Indiana</option>
                                                                                        <option value="America/Winnipeg">(GMT-05:00) Central Time - Winnipeg</option>
                                                                                        <option value="America/Bogota">(GMT-05:00) Colombia Standard Time</option>
                                                                                        <option value="America/Atikokan">(GMT-05:00) Eastern Standard Time - Atikokan</option>
                                                                                        <option value="America/Cancun">(GMT-05:00) Eastern Standard Time - Cancun</option>
                                                                                        <option value="America/Jamaica">(GMT-05:00) Eastern Standard Time - Jamaica</option>
                                                                                        <option value="America/Panama">(GMT-05:00) Eastern Standard Time - Panama</option>
                                                                                        <option value="America/Guayaquil">(GMT-05:00) Ecuador Time</option>
                                                                                        <option value="America/Lima">(GMT-05:00) Peru Standard Time</option>
                                                                                        <option value="America/Boa_Vista">(GMT-04:00) Amazon Standard Time - Boa Vista</option>
                                                                                        <option value="America/Campo_Grande">(GMT-04:00) Amazon Standard Time - Campo Grande</option>
                                                                                        <option value="America/Cuiaba">(GMT-04:00) Amazon Standard Time - Cuiaba</option>
                                                                                        <option value="America/Manaus">(GMT-04:00) Amazon Standard Time - Manaus</option>
                                                                                        <option value="America/Porto_Velho">(GMT-04:00) Amazon Standard Time - Porto Velho</option>
                                                                                        <option value="America/Barbados">(GMT-04:00) Atlantic Standard Time - Barbados</option>
                                                                                        <option value="America/Blanc-Sablon">(GMT-04:00) Atlantic Standard Time - Blanc-Sablon</option>
                                                                                        <option value="America/Curacao">(GMT-04:00) Atlantic Standard Time - Curaçao</option>
                                                                                        <option value="America/Martinique">(GMT-04:00) Atlantic Standard Time - Martinique</option>
                                                                                        <option value="America/Port_of_Spain">(GMT-04:00) Atlantic Standard Time - Port of Spain</option>
                                                                                        <option value="America/Puerto_Rico">(GMT-04:00) Atlantic Standard Time - Puerto Rico</option>
                                                                                        <option value="America/Santo_Domingo">(GMT-04:00) Atlantic Standard Time - Santo Domingo</option>
                                                                                        <option value="America/La_Paz">(GMT-04:00) Bolivia Time</option>
                                                                                        <option value="America/Santiago">(GMT-04:00) Chile Time</option>
                                                                                        <option value="America/Havana">(GMT-04:00) Cuba Time</option>
                                                                                        <option value="America/Detroit">(GMT-04:00) Eastern Time - Detroit</option>
                                                                                        <option value="America/Grand_Turk">(GMT-04:00) Eastern Time - Grand Turk</option>
                                                                                        <option value="America/Indiana/Indianapolis">(GMT-04:00) Eastern Time - Indianapolis</option>
                                                                                        <option value="America/Iqaluit">(GMT-04:00) Eastern Time - Iqaluit</option>
                                                                                        <option value="America/Kentucky/Louisville">(GMT-04:00) Eastern Time - Louisville</option>
                                                                                        <option value="America/Indiana/Marengo">(GMT-04:00) Eastern Time - Marengo, Indiana</option>
                                                                                        <option value="America/Kentucky/Monticello">(GMT-04:00) Eastern Time - Monticello, Kentucky</option>
                                                                                        <option value="America/Nassau">(GMT-04:00) Eastern Time - Nassau</option>
                                                                                        <option value="America/New_York">(GMT-04:00) Eastern Time - New York</option>
                                                                                        <option value="America/Nipigon">(GMT-04:00) Eastern Time - Nipigon</option>
                                                                                        <option value="America/Pangnirtung">(GMT-04:00) Eastern Time - Pangnirtung</option>
                                                                                        <option value="America/Indiana/Petersburg">(GMT-04:00) Eastern Time - Petersburg, Indiana</option>
                                                                                        <option value="America/Port-au-Prince">(GMT-04:00) Eastern Time - Port-au-Prince</option>
                                                                                        <option value="America/Thunder_Bay">(GMT-04:00) Eastern Time - Thunder Bay</option>
                                                                                        <option value="America/Toronto">(GMT-04:00) Eastern Time - Toronto</option>
                                                                                        <option value="America/Indiana/Vevay">(GMT-04:00) Eastern Time - Vevay, Indiana</option>
                                                                                        <option value="America/Indiana/Vincennes">(GMT-04:00) Eastern Time - Vincennes, Indiana</option>
                                                                                        <option value="America/Indiana/Winamac">(GMT-04:00) Eastern Time - Winamac, Indiana</option>
                                                                                        <option value="America/Guyana">(GMT-04:00) Guyana Time</option>
                                                                                        <option value="America/Asuncion">(GMT-04:00) Paraguay Time</option>
                                                                                        <option value="America/Caracas">(GMT-04:00) Venezuela Time</option>
                                                                                        <option value="America/Argentina/Buenos_Aires">(GMT-03:00) Argentina Standard Time - Buenos Aires</option>
                                                                                        <option value="America/Argentina/Catamarca">(GMT-03:00) Argentina Standard Time - Catamarca</option>
                                                                                        <option value="America/Argentina/Cordoba">(GMT-03:00) Argentina Standard Time - Cordoba</option>
                                                                                        <option value="America/Argentina/Jujuy">(GMT-03:00) Argentina Standard Time - Jujuy</option>
                                                                                        <option value="America/Argentina/La_Rioja">(GMT-03:00) Argentina Standard Time - La Rioja</option>
                                                                                        <option value="America/Argentina/Mendoza">(GMT-03:00) Argentina Standard Time - Mendoza</option>
                                                                                        <option value="America/Argentina/Rio_Gallegos">(GMT-03:00) Argentina Standard Time - Rio Gallegos</option>
                                                                                        <option value="America/Argentina/Salta">(GMT-03:00) Argentina Standard Time - Salta</option>
                                                                                        <option value="America/Argentina/San_Juan">(GMT-03:00) Argentina Standard Time - San Juan</option>
                                                                                        <option value="America/Argentina/San_Luis">(GMT-03:00) Argentina Standard Time - San Luis</option>
                                                                                        <option value="America/Argentina/Tucuman">(GMT-03:00) Argentina Standard Time - Tucuman</option>
                                                                                        <option value="America/Argentina/Ushuaia">(GMT-03:00) Argentina Standard Time - Ushuaia</option>
                                                                                        <option value="Atlantic/Bermuda">(GMT-03:00) Atlantic Time - Bermuda</option>
                                                                                        <option value="America/Glace_Bay">(GMT-03:00) Atlantic Time - Glace Bay</option>
                                                                                        <option value="America/Goose_Bay">(GMT-03:00) Atlantic Time - Goose Bay</option>
                                                                                        <option value="America/Halifax">(GMT-03:00) Atlantic Time - Halifax</option>
                                                                                        <option value="America/Moncton">(GMT-03:00) Atlantic Time - Moncton</option>
                                                                                        <option value="America/Thule">(GMT-03:00) Atlantic Time - Thule</option>
                                                                                        <option value="America/Araguaina">(GMT-03:00) Brasilia Standard Time - Araguaina</option>
                                                                                        <option value="America/Bahia">(GMT-03:00) Brasilia Standard Time - Bahia</option>
                                                                                        <option value="America/Belem">(GMT-03:00) Brasilia Standard Time - Belem</option>
                                                                                        <option value="America/Fortaleza">(GMT-03:00) Brasilia Standard Time - Fortaleza</option>
                                                                                        <option value="America/Maceio">(GMT-03:00) Brasilia Standard Time - Maceio</option>
                                                                                        <option value="America/Recife">(GMT-03:00) Brasilia Standard Time - Recife</option>
                                                                                        <option value="America/Santarem">(GMT-03:00) Brasilia Standard Time - Santarem</option>
                                                                                        <option value="America/Sao_Paulo">(GMT-03:00) Brasilia Standard Time - Sao Paulo</option>
                                                                                        <option value="America/Santiago">(GMT-03:00) Chile Time</option>
                                                                                        <option value="Atlantic/Stanley">(GMT-03:00) Falkland Islands Standard Time</option>
                                                                                        <option value="America/Cayenne">(GMT-03:00) French Guiana Time</option>
                                                                                        <option value="Antarctica/Palmer">(GMT-03:00) Palmer Time</option>
                                                                                        <option value="America/Punta_Arenas">(GMT-03:00) Punta Arenas Time</option>
                                                                                        <option value="Antarctica/Rothera">(GMT-03:00) Rothera Time</option>
                                                                                        <option value="America/Paramaribo">(GMT-03:00) Suriname Time</option>
                                                                                        <option value="America/Montevideo">(GMT-03:00) Uruguay Standard Time</option>
                                                                                        <option value="America/St_Johns">(GMT-02:30) Newfoundland Time</option>
                                                                                        <option value="America/Noronha">(GMT-02:00) Fernando de Noronha Standard Time</option>
                                                                                        <option value="Atlantic/South_Georgia">(GMT-02:00) South Georgia Time</option>
                                                                                        <option value="America/Miquelon">(GMT-02:00) St. Pierre &amp; Miquelon Time</option>
                                                                                        <option value="America/Nuuk">(GMT-02:00) West Greenland Time</option>
                                                                                        <option value="Atlantic/Cape_Verde">(GMT-01:00) Cape Verde Standard Time</option>
                                                                                        <option value="Atlantic/Azores">(GMT+00:00) Azores Time</option>
                                                                                        <option value="UTC">(GMT+00:00) Coordinated Universal Time</option>
                                                                                        <option value="America/Scoresbysund">(GMT+00:00) East Greenland Time</option>
                                                                                        <option value="Etc/GMT" selected="">(GMT+00:00) Greenwich Mean Time</option>
                                                                                    <option value="Africa/Abidjan">(GMT+00:00) Greenwich Mean Time - Abidjan</option>
                                                                                        <option value="Africa/Accra">(GMT+00:00) Greenwich Mean Time - Accra</option>
                                                                                        <option value="Africa/Bissau">(GMT+00:00) Greenwich Mean Time - Bissau</option>
                                                                                        <option value="America/Danmarkshavn">(GMT+00:00) Greenwich Mean Time - Danmarkshavn</option>
                                                                                        <option value="Africa/Monrovia">(GMT+00:00) Greenwich Mean Time - Monrovia</option>
                                                                                        <option value="Atlantic/Reykjavik">(GMT+00:00) Greenwich Mean Time - Reykjavik</option>
                                                                                        <option value="Africa/Sao_Tome">(GMT+00:00) Greenwich Mean Time - São Tomé</option>
                                                                                        <option value="Africa/Algiers">(GMT+01:00) Central European Standard Time - Algiers</option>
                                                                                        <option value="Africa/Tunis">(GMT+01:00) Central European Standard Time - Tunis</option>
                                                                                        <option value="Europe/Dublin">(GMT+01:00) Ireland Time</option>
                                                                                        <option value="Africa/Casablanca">(GMT+01:00) Morocco Time</option>
                                                                                        <option value="Europe/London">(GMT+01:00) United Kingdom Time</option>
                                                                                        <option value="Africa/Lagos">(GMT+01:00) West Africa Standard Time - Lagos</option>
                                                                                        <option value="Africa/Ndjamena">(GMT+01:00) West Africa Standard Time - Ndjamena</option>
                                                                                        <option value="Atlantic/Canary">(GMT+01:00) Western European Time - Canary</option>
                                                                                        <option value="Atlantic/Faroe">(GMT+01:00) Western European Time - Faroe</option>
                                                                                        <option value="Europe/Lisbon">(GMT+01:00) Western European Time - Lisbon</option>
                                                                                        <option value="Atlantic/Madeira">(GMT+01:00) Western European Time - Madeira</option>
                                                                                        <option value="Africa/El_Aaiun">(GMT+01:00) Western Sahara Time</option>
                                                                                        <option value="Africa/Khartoum">(GMT+02:00) Central Africa Time - Khartoum</option>
                                                                                        <option value="Africa/Maputo">(GMT+02:00) Central Africa Time - Maputo</option>
                                                                                        <option value="Africa/Windhoek">(GMT+02:00) Central Africa Time - Windhoek</option>
                                                                                        <option value="Europe/Amsterdam">(GMT+02:00) Central European Time - Amsterdam</option>
                                                                                        <option value="Europe/Andorra">(GMT+02:00) Central European Time - Andorra</option>
                                                                                        <option value="Europe/Belgrade">(GMT+02:00) Central European Time - Belgrade</option>
                                                                                        <option value="Europe/Berlin">(GMT+02:00) Central European Time - Berlin</option>
                                                                                        <option value="Europe/Brussels">(GMT+02:00) Central European Time - Brussels</option>
                                                                                        <option value="Europe/Budapest">(GMT+02:00) Central European Time - Budapest</option>
                                                                                        <option value="Africa/Ceuta">(GMT+02:00) Central European Time - Ceuta</option>
                                                                                        <option value="Europe/Copenhagen">(GMT+02:00) Central European Time - Copenhagen</option>
                                                                                        <option value="Europe/Gibraltar">(GMT+02:00) Central European Time - Gibraltar</option>
                                                                                        <option value="Europe/Luxembourg">(GMT+02:00) Central European Time - Luxembourg</option>
                                                                                        <option value="Europe/Madrid">(GMT+02:00) Central European Time - Madrid</option>
                                                                                        <option value="Europe/Malta">(GMT+02:00) Central European Time - Malta</option>
                                                                                        <option value="Europe/Monaco">(GMT+02:00) Central European Time - Monaco</option>
                                                                                        <option value="Europe/Oslo">(GMT+02:00) Central European Time - Oslo</option>
                                                                                        <option value="Europe/Paris">(GMT+02:00) Central European Time - Paris</option>
                                                                                        <option value="Europe/Prague">(GMT+02:00) Central European Time - Prague</option>
                                                                                        <option value="Europe/Rome">(GMT+02:00) Central European Time - Rome</option>
                                                                                        <option value="Europe/Stockholm">(GMT+02:00) Central European Time - Stockholm</option>
                                                                                        <option value="Europe/Tirane">(GMT+02:00) Central European Time - Tirane</option>
                                                                                        <option value="Europe/Vienna">(GMT+02:00) Central European Time - Vienna</option>
                                                                                        <option value="Europe/Warsaw">(GMT+02:00) Central European Time - Warsaw</option>
                                                                                        <option value="Europe/Zurich">(GMT+02:00) Central European Time - Zurich</option>
                                                                                        <option value="Africa/Cairo">(GMT+02:00) Eastern European Standard Time - Cairo</option>
                                                                                        <option value="Europe/Kaliningrad">(GMT+02:00) Eastern European Standard Time - Kaliningrad</option>
                                                                                        <option value="Africa/Tripoli">(GMT+02:00) Eastern European Standard Time - Tripoli</option>
                                                                                        <option value="Africa/Johannesburg">(GMT+02:00) South Africa Standard Time</option>
                                                                                        <option value="Antarctica/Troll">(GMT+02:00) Troll Time</option>
                                                                                        <option value="Asia/Baghdad">(GMT+03:00) Arabian Standard Time - Baghdad</option>
                                                                                        <option value="Asia/Qatar">(GMT+03:00) Arabian Standard Time - Qatar</option>
                                                                                        <option value="Asia/Riyadh">(GMT+03:00) Arabian Standard Time - Riyadh</option>
                                                                                        <option value="Africa/Juba">(GMT+03:00) East Africa Time - Juba</option>
                                                                                        <option value="Africa/Nairobi">(GMT+03:00) East Africa Time - Nairobi</option>
                                                                                        <option value="Asia/Amman">(GMT+03:00) Eastern European Time - Amman</option>
                                                                                        <option value="Europe/Athens">(GMT+03:00) Eastern European Time - Athens</option>
                                                                                        <option value="Asia/Beirut">(GMT+03:00) Eastern European Time - Beirut</option>
                                                                                        <option value="Europe/Bucharest">(GMT+03:00) Eastern European Time - Bucharest</option>
                                                                                        <option value="Europe/Chisinau">(GMT+03:00) Eastern European Time - Chisinau</option>
                                                                                        <option value="Asia/Damascus">(GMT+03:00) Eastern European Time - Damascus</option>
                                                                                        <option value="Asia/Gaza">(GMT+03:00) Eastern European Time - Gaza</option>
                                                                                        <option value="Asia/Hebron">(GMT+03:00) Eastern European Time - Hebron</option>
                                                                                        <option value="Europe/Helsinki">(GMT+03:00) Eastern European Time - Helsinki</option>
                                                                                        <option value="Europe/Kiev">(GMT+03:00) Eastern European Time - Kiev</option>
                                                                                        <option value="Asia/Nicosia">(GMT+03:00) Eastern European Time - Nicosia</option>
                                                                                        <option value="Europe/Riga">(GMT+03:00) Eastern European Time - Riga</option>
                                                                                        <option value="Europe/Sofia">(GMT+03:00) Eastern European Time - Sofia</option>
                                                                                        <option value="Europe/Tallinn">(GMT+03:00) Eastern European Time - Tallinn</option>
                                                                                        <option value="Europe/Uzhgorod">(GMT+03:00) Eastern European Time - Uzhhorod</option>
                                                                                        <option value="Europe/Vilnius">(GMT+03:00) Eastern European Time - Vilnius</option>
                                                                                        <option value="Europe/Zaporozhye">(GMT+03:00) Eastern European Time - Zaporozhye</option>
                                                                                        <option value="Asia/Famagusta">(GMT+03:00) Famagusta Time</option>
                                                                                        <option value="Asia/Jerusalem">(GMT+03:00) Israel Time</option>
                                                                                        <option value="Europe/Kirov">(GMT+03:00) Kirov Time</option>
                                                                                        <option value="Europe/Minsk">(GMT+03:00) Moscow Standard Time - Minsk</option>
                                                                                        <option value="Europe/Moscow">(GMT+03:00) Moscow Standard Time - Moscow</option>
                                                                                        <option value="Europe/Simferopol">(GMT+03:00) Moscow Standard Time - Simferopol</option>
                                                                                        <option value="Antarctica/Syowa">(GMT+03:00) Syowa Time</option>
                                                                                        <option value="Europe/Istanbul">(GMT+03:00) Turkey Time</option>
                                                                                        <option value="Asia/Yerevan">(GMT+04:00) Armenia Standard Time</option>
                                                                                        <option value="Europe/Astrakhan">(GMT+04:00) Astrakhan Time</option>
                                                                                        <option value="Asia/Baku">(GMT+04:00) Azerbaijan Standard Time</option>
                                                                                        <option value="Asia/Tbilisi">(GMT+04:00) Georgia Standard Time</option>
                                                                                        <option value="Asia/Dubai">(GMT+04:00) Gulf Standard Time</option>
                                                                                        <option value="Indian/Mauritius">(GMT+04:00) Mauritius Standard Time</option>
                                                                                        <option value="Indian/Reunion">(GMT+04:00) Réunion Time</option>
                                                                                        <option value="Europe/Samara">(GMT+04:00) Samara Standard Time</option>
                                                                                        <option value="Europe/Saratov">(GMT+04:00) Saratov Time</option>
                                                                                        <option value="Indian/Mahe">(GMT+04:00) Seychelles Time</option>
                                                                                        <option value="Europe/Ulyanovsk">(GMT+04:00) Ulyanovsk Time</option>
                                                                                        <option value="Europe/Volgograd">(GMT+04:00) Volgograd Standard Time</option>
                                                                                        <option value="Asia/Kabul">(GMT+04:30) Afghanistan Time</option>
                                                                                        <option value="Asia/Tehran">(GMT+04:30) Iran Time</option>
                                                                                        <option value="Indian/Kerguelen">(GMT+05:00) French Southern &amp; Antarctic Time</option>
                                                                                        <option value="Indian/Maldives">(GMT+05:00) Maldives Time</option>
                                                                                        <option value="Antarctica/Mawson">(GMT+05:00) Mawson Time</option>
                                                                                        <option value="Asia/Karachi">(GMT+05:00) Pakistan Standard Time</option>
                                                                                        <option value="Asia/Dushanbe">(GMT+05:00) Tajikistan Time</option>
                                                                                        <option value="Asia/Ashgabat">(GMT+05:00) Turkmenistan Standard Time</option>
                                                                                        <option value="Asia/Samarkand">(GMT+05:00) Uzbekistan Standard Time - Samarkand</option>
                                                                                        <option value="Asia/Tashkent">(GMT+05:00) Uzbekistan Standard Time - Tashkent</option>
                                                                                        <option value="Asia/Aqtau">(GMT+05:00) West Kazakhstan Time - Aqtau</option>
                                                                                        <option value="Asia/Aqtobe">(GMT+05:00) West Kazakhstan Time - Aqtobe</option>
                                                                                        <option value="Asia/Atyrau">(GMT+05:00) West Kazakhstan Time - Atyrau</option>
                                                                                        <option value="Asia/Oral">(GMT+05:00) West Kazakhstan Time - Oral</option>
                                                                                        <option value="Asia/Qyzylorda">(GMT+05:00) West Kazakhstan Time - Qyzylorda</option>
                                                                                        <option value="Asia/Yekaterinburg">(GMT+05:00) Yekaterinburg Standard Time</option>
                                                                                        <option value="Asia/Colombo">(GMT+05:30) Indian Standard Time - Colombo</option>
                                                                                        <option value="Asia/Kolkata">(GMT+05:30) Indian Standard Time - Kolkata</option>
                                                                                        <option value="Asia/Kathmandu">(GMT+05:45) Nepal Time</option>
                                                                                        <option value="Asia/Dhaka">(GMT+06:00) Bangladesh Standard Time</option>
                                                                                        <option value="Asia/Thimphu">(GMT+06:00) Bhutan Time</option>
                                                                                        <option value="Asia/Almaty">(GMT+06:00) East Kazakhstan Time - Almaty</option>
                                                                                        <option value="Asia/Qostanay">(GMT+06:00) East Kazakhstan Time - Kostanay</option>
                                                                                        <option value="Indian/Chagos">(GMT+06:00) Indian Ocean Time</option>
                                                                                        <option value="Asia/Bishkek">(GMT+06:00) Kyrgyzstan Time</option>
                                                                                        <option value="Asia/Omsk">(GMT+06:00) Omsk Standard Time</option>
                                                                                        <option value="Asia/Urumqi">(GMT+06:00) Urumqi Time</option>
                                                                                        <option value="Antarctica/Vostok">(GMT+06:00) Vostok Time</option>
                                                                                        <option value="Indian/Cocos">(GMT+06:30) Cocos Islands Time</option>
                                                                                        <option value="Asia/Yangon">(GMT+06:30) Myanmar Time</option>
                                                                                        <option value="Asia/Barnaul">(GMT+07:00) Barnaul Time</option>
                                                                                        <option value="Indian/Christmas">(GMT+07:00) Christmas Island Time</option>
                                                                                        <option value="Antarctica/Davis">(GMT+07:00) Davis Time</option>
                                                                                        <option value="Asia/Hovd">(GMT+07:00) Hovd Standard Time</option>
                                                                                        <option value="Asia/Bangkok">(GMT+07:00) Indochina Time - Bangkok</option>
                                                                                        <option value="Asia/Ho_Chi_Minh">(GMT+07:00) Indochina Time - Ho Chi Minh City</option>
                                                                                        <option value="Asia/Krasnoyarsk">(GMT+07:00) Krasnoyarsk Standard Time - Krasnoyarsk</option>
                                                                                        <option value="Asia/Novokuznetsk">(GMT+07:00) Krasnoyarsk Standard Time - Novokuznetsk</option>
                                                                                        <option value="Asia/Novosibirsk">(GMT+07:00) Novosibirsk Standard Time</option>
                                                                                        <option value="Asia/Tomsk">(GMT+07:00) Tomsk Time</option>
                                                                                        <option value="Asia/Jakarta">(GMT+07:00) Western Indonesia Time - Jakarta</option>
                                                                                        <option value="Asia/Pontianak">(GMT+07:00) Western Indonesia Time - Pontianak</option>
                                                                                        <option value="Antarctica/Casey">(GMT+08:00) Australian Western Standard Time - Casey</option>
                                                                                        <option value="Australia/Perth">(GMT+08:00) Australian Western Standard Time - Perth</option>
                                                                                        <option value="Asia/Brunei">(GMT+08:00) Brunei Darussalam Time</option>
                                                                                        <option value="Asia/Makassar">(GMT+08:00) Central Indonesia Time</option>
                                                                                        <option value="Asia/Macau">(GMT+08:00) China Standard Time - Macao</option>
                                                                                        <option value="Asia/Shanghai">(GMT+08:00) China Standard Time - Shanghai</option>
                                                                                        <option value="Asia/Hong_Kong">(GMT+08:00) Hong Kong Standard Time</option>
                                                                                        <option value="Asia/Irkutsk">(GMT+08:00) Irkutsk Standard Time</option>
                                                                                        <option value="Asia/Kuala_Lumpur">(GMT+08:00) Malaysia Time - Kuala Lumpur</option>
                                                                                        <option value="Asia/Kuching">(GMT+08:00) Malaysia Time - Kuching</option>
                                                                                        <option value="Asia/Manila">(GMT+08:00) Philippine Standard Time</option>
                                                                                        <option value="Asia/Singapore">(GMT+08:00) Singapore Standard Time</option>
                                                                                        <option value="Asia/Taipei">(GMT+08:00) Taipei Standard Time</option>
                                                                                        <option value="Asia/Choibalsan">(GMT+08:00) Ulaanbaatar Standard Time - Choibalsan</option>
                                                                                        <option value="Asia/Ulaanbaatar">(GMT+08:00) Ulaanbaatar Standard Time - Ulaanbaatar</option>
                                                                                        <option value="Australia/Eucla">(GMT+08:45) Australian Central Western Standard Time</option>
                                                                                        <option value="Asia/Dili">(GMT+09:00) East Timor Time</option>
                                                                                        <option value="Asia/Jayapura">(GMT+09:00) Eastern Indonesia Time</option>
                                                                                        <option value="Asia/Tokyo">(GMT+09:00) Japan Standard Time</option>
                                                                                        <option value="Asia/Pyongyang">(GMT+09:00) Korean Standard Time - Pyongyang</option>
                                                                                        <option value="Asia/Seoul">(GMT+09:00) Korean Standard Time - Seoul</option>
                                                                                        <option value="Pacific/Palau">(GMT+09:00) Palau Time</option>
                                                                                        <option value="Asia/Chita">(GMT+09:00) Yakutsk Standard Time - Chita</option>
                                                                                        <option value="Asia/Khandyga">(GMT+09:00) Yakutsk Standard Time - Khandyga</option>
                                                                                        <option value="Asia/Yakutsk">(GMT+09:00) Yakutsk Standard Time - Yakutsk</option>
                                                                                        <option value="Australia/Darwin">(GMT+09:30) Australian Central Standard Time</option>
                                                                                        <option value="Australia/Adelaide">(GMT+09:30) Central Australia Time - Adelaide</option>
                                                                                        <option value="Australia/Broken_Hill">(GMT+09:30) Central Australia Time - Broken Hill</option>
                                                                                        <option value="Australia/Brisbane">(GMT+10:00) Australian Eastern Standard Time - Brisbane</option>
                                                                                        <option value="Australia/Lindeman">(GMT+10:00) Australian Eastern Standard Time - Lindeman</option>
                                                                                        <option value="Pacific/Guam">(GMT+10:00) Chamorro Standard Time</option>
                                                                                        <option value="Pacific/Chuuk">(GMT+10:00) Chuuk Time</option>
                                                                                        <option value="Antarctica/DumontDUrville">(GMT+10:00) Dumont-d’Urville Time</option>
                                                                                        <option value="Australia/Currie">(GMT+10:00) Eastern Australia Time - Currie</option>
                                                                                        <option value="Australia/Hobart">(GMT+10:00) Eastern Australia Time - Hobart</option>
                                                                                        <option value="Australia/Melbourne">(GMT+10:00) Eastern Australia Time - Melbourne</option>
                                                                                        <option value="Australia/Sydney">(GMT+10:00) Eastern Australia Time - Sydney</option>
                                                                                        <option value="Pacific/Port_Moresby">(GMT+10:00) Papua New Guinea Time</option>
                                                                                        <option value="Asia/Ust-Nera">(GMT+10:00) Vladivostok Standard Time - Ust-Nera</option>
                                                                                        <option value="Asia/Vladivostok">(GMT+10:00) Vladivostok Standard Time - Vladivostok</option>
                                                                                        <option value="Australia/Lord_Howe">(GMT+10:30) Lord Howe Time</option>
                                                                                        <option value="Pacific/Bougainville">(GMT+11:00) Bougainville Time</option>
                                                                                        <option value="Pacific/Kosrae">(GMT+11:00) Kosrae Time</option>
                                                                                        <option value="Antarctica/Macquarie">(GMT+11:00) Macquarie Island Time</option>
                                                                                        <option value="Asia/Magadan">(GMT+11:00) Magadan Standard Time</option>
                                                                                        <option value="Pacific/Noumea">(GMT+11:00) New Caledonia Standard Time</option>
                                                                                        <option value="Pacific/Norfolk">(GMT+11:00) Norfolk Island Time</option>
                                                                                        <option value="Pacific/Pohnpei">(GMT+11:00) Ponape Time</option>
                                                                                        <option value="Asia/Sakhalin">(GMT+11:00) Sakhalin Standard Time</option>
                                                                                        <option value="Pacific/Guadalcanal">(GMT+11:00) Solomon Islands Time</option>
                                                                                        <option value="Asia/Srednekolymsk">(GMT+11:00) Srednekolymsk Time</option>
                                                                                        <option value="Pacific/Efate">(GMT+11:00) Vanuatu Standard Time</option>
                                                                                        <option value="Asia/Anadyr">(GMT+12:00) Anadyr Standard Time</option>
                                                                                        <option value="Pacific/Fiji">(GMT+12:00) Fiji Time</option>
                                                                                        <option value="Pacific/Tarawa">(GMT+12:00) Gilbert Islands Time</option>
                                                                                        <option value="Pacific/Kwajalein">(GMT+12:00) Marshall Islands Time - Kwajalein</option>
                                                                                        <option value="Pacific/Majuro">(GMT+12:00) Marshall Islands Time - Majuro</option>
                                                                                        <option value="Pacific/Nauru">(GMT+12:00) Nauru Time</option>
                                                                                        <option value="Pacific/Auckland">(GMT+12:00) New Zealand Time</option>
                                                                                        <option value="Asia/Kamchatka">(GMT+12:00) Petropavlovsk-Kamchatski Standard Time</option>
                                                                                        <option value="Pacific/Funafuti">(GMT+12:00) Tuvalu Time</option>
                                                                                        <option value="Pacific/Wake">(GMT+12:00) Wake Island Time</option>
                                                                                        <option value="Pacific/Wallis">(GMT+12:00) Wallis &amp; Futuna Time</option>
                                                                                        <option value="Pacific/Chatham">(GMT+12:45) Chatham Time</option>
                                                                                        <option value="Pacific/Apia">(GMT+13:00) Apia Time</option>
                                                                                        <option value="Pacific/Enderbury">(GMT+13:00) Phoenix Islands Time</option>
                                                                                        <option value="Pacific/Fakaofo">(GMT+13:00) Tokelau Time</option>
                                                                                        <option value="Pacific/Tongatapu">(GMT+13:00) Tonga Standard Time</option>
                                                                                        <option value="Pacific/Kiritimati">(GMT+14:00) Line Islands Time</option>
                                                                            </select></span>
                            </div>
                        </div>
                        <br>

                        <div class="call-setup-datetime">
                            <strong> Date<font color="#FF0000">*</font>:</strong><br>
                            <input type="date" id="datepicker" placeholder="Select Meeting Date" autocomplete="off" name="mo_openid_setup_call_date" >
                        </div>
                        <div class="call-setup-datetime">
                            <strong> Time (24-hour)<font color="#FF0000">*</font>:</strong><br>
                            <input type="time" id="timepicker" placeholder="Select Meeting Time"  autocomplete="off" name="mo_openid_setup_call_time" >
                        </div> <br><br><br>
                        <div>
                    
                        </div>
                    </div>
                </div>
                <br><br>
                <center><input type="submit" name="submit" value="Submit Query" style="width:110px;" class="button button-primary button-large" /></center>
                <h2 style="text-align: center;">OR</h2>
                <center><button type="button" class="button button-primary button-large" onclick="wordpress_support();"> WordPress Support Forum</button></center>
                
            </form>
        </div>
    </div>
    
';
?>
<script>
	jQuery("#contact_us_phone").intlTelInput();
	jQuery("#phone_contact").intlTelInput();

	jQuery( function() {

		jQuery("#call_setup_dets").hide();

		jQuery("#mo_sl_setup_call").click(function() {
			if(jQuery(this).is(":checked")) {
				jQuery("#call_setup_dets").show();
				var dtToday = new Date();
				var tomorrow = new Date(dtToday.getTime() + (24 * 60 * 60 * 1000));

				var month = tomorrow.getMonth() + 1;
				var day = tomorrow.getDate();
				var year = tomorrow.getFullYear();
				if(month < 10)
					month = '0' + month.toString();
				if(day < 10)
					day = '0' + day.toString();

				var maxDate = year + '-' + month + '-' + day;
				jQuery('#datepicker').attr('min', maxDate);
			} else {
				jQuery("#call_setup_dets").hide();
			}
		});
	});


</script>


