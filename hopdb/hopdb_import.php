<?php

function hopdb_import()
	{
	if ($_REQUEST['luke18']!='') return hopdb_import_luke18();
	if ($_REQUEST['burn247']!='') return hopdb_import_burn247();

	$l = $_REQUEST['urls'];

	$s = "";
	$s = $s . "<table style='border: solid 1px black;'><tr>\n";
	$s = $s . "<td align='center' style='border: solid 1px black;border-collapse:collapse'>\n";
	$s = $s . "<form action='#' method='post'>";
	$s = $s . "<input type='hidden' name='luke18' value='1' />";
	$s = $s . "http://studentrevivalmovement.com/prayer_furnaces/geocodes<br/>\n";
	$s = $s . "<input type='submit' class='button-primary' value='Import Luke18Project' />";
	$s = $s . "</form>";
	$s = $s . "<br/>";
	$s = $s . "<form action='#' method='post'>";
	$s = $s . "<input type='hidden' name='burn24' value='1' />";
	$s = $s . "http://burn24-7.com/locations/<br/>\n";
	$s = $s . "<input type='submit' class='button-primary' value='Import Burn 24-7' />";
	$s = $s . "</form>";
	$s = $s . "<br/>";
	$s = $s . "</td>\n";
	$s = $s . "<td style='border: solid 1px black;border-collapse:collapse'>\n";
	$s = $s . "<p align='center'>\n";
	$s = $s . "<h3>Upload URL List</h3>\n";
	$s = $s . "<form action='#' method='post' >\n";
	$s = $s . " <textarea name='urls' cols='80' rows='10' >$l</textarea><br/>\n";
	$s = $s . " <input type='submit' value='Import' class='button-primary' />\n";
	$s = $s . "</form> \n";
	$s = $s . "</p>\n";
	$s = $s . "</td>\n";
	$s = $s . "</tr></table>\n";

	if ($l!="") $s = $s . hopdb_urls_foundnotfound($l);

	return $s;
	}

function hopdb_urls_foundnotfound($l)
	{
	$s = "";
	$s . $s . "<style>.hopdb {margin-left:30px;border:solid 1px black;padding:3px 3px 3px 3px} .hopdb * td {border: solid 1px black;border-collapse:collapse;padding:3px 3px 3px 3px;}</style>\n";

	$s = $s . "<br/><br/><br/>\n";
	$s = $s . "<table class='hopdb' width='600px'>\n";
	foreach(split("\n", $l) as $l)
		{
		$t = $l;
		$t = str_replace("http://", "", $t);
		$t = str_replace("www.", "", $t);
		if (($u = strpos($t, "/")) !== false) $t = substr($t, 0, $u);
		$t = strtolower($t);
		$t = trim($t);

		$q = "SELECT * FROM hoplist WHERE LCase(Website) LIKE '%".$t."%'";
		$r = hopdb_query($q);
		$c = hopdb_record_count($r);
		hopdb_free_result($r);

		$k = ($c <= 0) ? "<a href='$l' target='_new'>Unknown</a>" : "Found";

		$s = $s . "<tr>\n";
		$s = $s . " <td style='margin:0px 0px 0px 0px;border-collapse:collapse;border: solid 1px black;'>" . $k  . "</td>\n";
		$s = $s . " <td style='margin:0px 0px 0px 0px;border-collapse:collapse;border: solid 1px black;'>" . $l . "</td>\n";
//		$s = $s . " <td style='margin:0px 0px 0px 0px;border-collapse:collapse;border: solid 1px black;'>" . $t . "</td>\n";
//		$s = $s . " <td style='margin:0px 0px 0px 0px;border-collapse:collapse;border: solid 1px black;'>" . $q . "</td>\n";
		$s = $s . "</tr>\n";
		}
	$s = $s . "</table>\n";

	return $s;
	}

function hopdb_import_curl($u)
	{
	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, $u);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 10);
	$st = curl_exec($ch);
//print "<br/>HTML CODE:" . curl_getinfo($ch, CURLINFO_HTTP_CODE);
//print "<br/>CONTENT TYPE:" . curl_getinfo($ch, CURLINFO_CONTENT_TYPE );
	if (curl_error($ch)!="") print "<br/>".curl_error($ch);
	curl_close($ch);
	return $st;
	}

function hopdb_check_url_by_id($id)
	{
	$r = get_hop_row($id);
	if (!r) die("!No row for ID $id.");
	if (!($u=$r[Website])) die("+No Website for ID $id.");

	$ch = curl_init();
	curl_setopt ($ch, CURLOPT_URL, $u);
	curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 10);
	$st = curl_exec($ch);
	$code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
	curl_close($ch);

//print "<br/>HTML CODE:" . curl_getinfo($ch, CURLINFO_HTTP_CODE);
//print "<br/>CONTENT TYPE:" . curl_getinfo($ch, CURLINFO_CONTENT_TYPE );
//	if (curl_error($ch)!="") print "<br/>".curl_error($ch);
	if ($code == "301") die("+URL OK - 301");
	if ($code == "404") die("-URL FAIL 404");
	if ($code == "200") die("+URL OK - 200");

	if ($st == "") die("-URL FAIL BLANK");
	die("+URL OK.");
	}


function hopdb_import_luke18()
	{
	$s = "";

$direct_import = true;

	include_once("hopdb_class_xml_file.php");
	$U = "http://studentrevivalmovement.com/prayer_furnaces/geocodes";
	$x = hopdb_import_curl($U);
//die($x);
	if (strlen($x) < 200) return "<h1>Short response from Luke18.  Check URL!</h1>\n<a href='$U'>$U</a>\n";
	$r = new xml_file();
	$r->loadXML($x);

	if ($direct_import)	hopdb_execute("DELETE FROM HOPList WHERE Category='Luke18Project'");

	$s = $s . "<h1>Luke 18 Sites</h1>";
	$s = $s . "<ul>\n";
	foreach($r->fetch_list("//markers/marker/@name") as $a)
		{
		$row = array();
		$row[Name] = $a;
		$row[City] = $r->fetch_part("//markers/marker[@name=\"$a\"]/@city");
		$row[State] = $r->fetch_part("//markers/marker[@name=\"$a\"]/@state");
		$row[Country] = "United States";
		$row[Category] = "Luke18Project";

		$d = $r->fetch_part("//markers/marker[@name=\"$a\"]/@contact_info");
		$D = str_replace(array("<br>","<br/>","<br />"), array("\n","\n","\n"), $d);
		$od = "";
		foreach(split("\n", $D) as $dd)
			{
			$dd = trim($dd);
			if (preg_match(">\b([A-Z0-9._%+-]+@[A-Z0-9.-]+\.[A-Z]{2,4})\b>i", $dd)==1) {$row[Email]=$dd;continue;}
			if (substr($dd, 0, 7) == "http://") {$row[Website]=$dd;continue;}
			if (substr($dd, 0, 4) == "www.") {$row[Website]=$dd;continue;}
			$od = $od . (strlen($od)>0?"\n":"") . $dd;
			}
		$row[Description] = $od;

		$user = "";
		if (!$direct_import) $user = "user";
		hopdb_insert($row, $user);
		$s = $s . "<li>$a</li>\n";
		}
	$s = $s . "</ul>\n";

	return $s;
	}

function hopdb_import_burn247()
	{
	$u = "http://burn24-7.com/locations/";
	}

?>