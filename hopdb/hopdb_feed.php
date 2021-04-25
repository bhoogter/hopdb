<?php
include_once("hopdb_db.php");

function hopdb_feeds()
{
    $s = "";
    $s = $s . "<h3>HOPDB Data Feeds</h3>\n";
    $s = $s . "<br/>\n";
    $s = $s . "<table style='border: solid 1px black;border-collapse:collapse;margin-left:30px;'>\n";
    $s = $s . " <tr>\n";
    $s = $s . "  <td><a href='" . hopdb_feed_url("xml") . "'>XML</a></td><td>HOPDB XML</td>\n";
    $s = $s . " </tr>\n";
    $s = $s . " <tr>\n";
    $s = $s . "  <td><a href='" . hopdb_feed_url("kml") . "'>KML</a></td><td>Google Earth Keyhole Markup (KML)</td>\n";
    $s = $s . " </tr>\n";
    $s = $s . "</table>\n";
    return $s;
}

function hopdb_do_write_feeds()
{
    $xmlFile = hopdb_feed_file("xml");
    $kmlFile = hopdb_feed_file("kml");
    $kmzFile = hopdb_feed_file("kmz");
    file_put_contents($kmlFile, utf8_encode(hopdb_kml()));
    copy($kmlFile, hopdb_feed_file("3.kml"));
    file_put_contents($xmlFile, utf8_encode(hopdb_xml()));

    if (function_exists("gzencode")) {
        file_put_contents($kmzFile, gzencode(file_get_contents($kmlFile), 9));
    }
}

function hopdb_kml_encode($s)
{
    return str_replace(array("<", ">", "&"), array("&lt;", "&gt;", "&amp;"), $s);
}

function hopdb_kml()
{
    $s = "";
    $s = $s . "<?xml version='1.0' encoding='ascii'?>\n";
    $s = $s . "<kml xmlns='http://www.opengis.net/kml/2.2' xmlns:gx='http://www.google.com/kml/ext/2.2'>\n";
    $s = $s . " <Document>\n";

    foreach (hopdb_categories() as $c) {
        $s = $s . "<Style id='$c'>\n";
        $s = $s . "      <IconStyle>\n";
        //		$s = $s . "         <color>ff00ff00</color>\n";
        $s = $s . "         <colorMode>random</colorMode>\n";
        //		$s = $s . "         <scale>1.1</scale>\n";
        $s = $s . "         <Icon><href>" . hopdb_plugin_url('/images/feed/' . hopdb_category_icon($c)) . "</href></Icon>\n";
        $s = $s . "      </IconStyle>\n";
        $s = $s . "   </Style>\n";
    }

    $r = get_hops();
    while ($row = mysqli_fetch_assoc($r)) {
        $l = $row['City'] . ", " . ($row['State'] != "" ? $row['State'] : $row['Country']);
        $a = $row['Address'] . ", " . $row['Address2'] . ", " . $l;

        $d = "";
        $d = $d . $row['Name'] . "<br/>\n";
        $d = $d . $row['Website'] . " <br/>\n";
        $d = $d . "Location: $l<br/>\n";
        if ($row['Director'] != "") $d = $d . "Director: $row[Director]<br/>\n";
        if ($row['Email'] != "") $d = $d . "Email: $row[Email]<br/>\n";
        if ($row['Phone'] != "") $d = $d . "Phone: $row[Phone]<br/>\n";
        $d = $d . "<br/>\n";
        $d = $d . $row['Description'];

        $s = $s . "  <Placemark id='${row['ID']}'>\n";
        $s = $s . "    <name>" . utf8_decode(hopdb_kml_encode($row['Name'])) . "</name>\n";
        $s = $s . "    <styleUrl>#$row[Category]</styleUrl>\n";
        if ($row['Position'] == '')
            $s = $s . "    <address>" . hopdb_kml_encode($a) . "</address>\n";
        else
            $s = $s . "    <Point>\n      <coordinates>${row['Position']}</coordinates>\n    </Point>\n";

        $s = $s . "    <description><![CDATA[" . utf8_decode($d) . "]]></description>\n";
        $s = $s . "  </Placemark>\n";
    }

    $s = $s . " </Document>\n";
    $s = $s . "</kml>\n";
    return $s;
}

function hopdb_xml()
{
    $s = "";
    $s = $s . "<?xml version='1.0' encoding='ascii'?>\n";
    $s = $s . "<hopdb>\n";

    $r = get_hops();
    while ($row = mysqli_fetch_assoc($r)) {
        $s = $s . " <hop id='$row[ID]'>\n";
        foreach (hopdb_fields() as $a) {
            if ($a == "ID" || $a == "Password") continue;
            $f = $row[$a];
            $F = utf8_decode(hopdb_kml_encode($f));
            if (trim($F) != "")
                $s = $s . "  <" . strtolower($a) . ">" . $F . "</" . strtolower($a) . ">\n";
        }
        $s = $s . " </hop>\n";
    }

    $s = $s . "</hopdb>\n";
    return $s;
}
