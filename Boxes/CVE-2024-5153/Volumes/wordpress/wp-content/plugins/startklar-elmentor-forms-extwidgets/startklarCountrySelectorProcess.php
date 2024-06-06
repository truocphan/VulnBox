<?php
namespace StartklarElmentorFormsExtWidgets;
use  TP_MaxMind\Db\Reader;

class startklarCountrySelectorProcess {
    static public function process(){
        require_once(__DIR__ . "/lib/GeoLocator/src/autoload.php");
        $ret_arr = [];

        $remote_addr = $_SERVER["REMOTE_ADDR"];

        if ($remote_addr == "::1") {
            $ch = curl_init('https://httpbin.org/ip');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            $data = json_decode($response, true);
            $remote_addr = $data['origin']; // This will give you the public IP address
        }

        if (!empty($remote_addr) && preg_match("/\d+.\d+.\d+.\d+/ism", $remote_addr, $matches)) {
            $reader = new Reader(__DIR__ . "/lib/GeoLocator/src/GeoLite2-Country/GeoLite2-Country.mmdb");
            $test = $reader->get($remote_addr);
            $country_names_en = $test["country"]["names"]["en"];

            if (!empty($country_names_en)) {
                $ret_arr = ["country" => $test["country"]["names"]["en"]];
            }
        }

        echo json_encode($ret_arr);
        exit;
    }
}