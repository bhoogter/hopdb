<?php
//geocoding

//http://maps.google.com/maps/api/geocode/format?address=some_address&sensor=true_or_false
//http://maps.google.com/maps/api/geocode/xml?address=22%20Cortlandt%20Street,%20New%20York,%20NY&sensor=false


function hopdb_address_lookup($s)
	{
	$b = "http://maps.google.com/maps/api/geocode/xml?address=" . urlencode($s) . "&sensor=false";
//wp_die($b);
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, $b);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 10);
	$st = curl_exec($ch);
	curl_close($ch);

//wp_die($st);
	if (strstr($st, 'REQUEST_DENIED') !== false) return '';
	if (strstr($st, 'ZERO_RESULTS') !== false) return '';
	if (strstr($st, 'INVALID_REQUEST') !== false) return '';

	$x = strpos($st, "<location>") + 10;
	$y = strpos($st, "</location>");
	$z = substr($st, $x, $y - $x);
//wp_die($z);

	$x = strpos($z, "<lat>") + 5;
	$y = strpos($z, "</lat>");
	$A = substr($z, $x, $y - $x);

	$x = strpos($z, "<lng>") + 5;
	$y = strpos($z, "</lng>");
	$O = substr($z, $x, $y - $x);

	$res = trim(trim($O) . "," . trim($A));
//wp_die($res);
	return $res;
	}

?>