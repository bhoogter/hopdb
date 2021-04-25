<?php
//geocoding

//http://maps.google.com/maps/api/geocode/format?address=some_address&sensor=true_or_false
//http://maps.google.com/maps/api/geocode/xml?address=22%20Cortlandt%20Street,%20New%20York,%20NY&sensor=false


function hopdb_address_lookup($s)
{
    $apiKey = "AIzaSyBssW3WZZck5Wr65GC_GHbTOuVSMYndv2E";
    $address = urlencode($s);

    $b = "https://maps.googleapis.com/maps/api/geocode/json?address=$address&key=$apiKey";
// wp_die($b);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $b);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
    $st = curl_exec($ch);
    curl_close($ch);
// wp_die($st);

    $result = json_decode($st, true);
// print_r($result);wp_die("...");
// wp_die(print_r($result['results'][0]['geometry']['location'], true));
// print_r($result['results'][0]['location']);wp_die("...");
    if (@$result['status'] != "OK") return '';
// wp_die(print_r($result['results'][0]['geometry']['location'], true));
    if (
        !@$result['results'] ||
        !@$result['results'][0] ||
        !@$result['results'][0]['geometry'] ||
        !@$result['results'][0]['geometry']['location']
    ) return '';

// wp_die('vound');
    $location = "" . @$result['results'][0]['geometry']['location']['lat'] . "," . @$result['results'][0]['geometry']['location']['lng'];
// wp_die($location);
    return $location;
}
