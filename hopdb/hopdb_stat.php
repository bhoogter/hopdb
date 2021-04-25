<?php

function hopdb_shortcode_stat($atts)	
	{
        $w = "";
		$r = hopdb_query("SELECT * FROM hoplist");
		$total_count = hopdb_record_count($r);
		hopdb_free_result($r);

		$r = hopdb_query("SELECT Country, COUNT(ID) AS Cnt FROM hoplist GROUP BY Country");
        $total_other = 0;
		while($row = hopdb_fetch_assoc($r))
			{
//print "<br/>$row[Country]=$row[Cnt]\n";
			if ($row['Country']=="United States") 
				{
				$total_usa = $row['Cnt'];
				continue;
				}
			if ($row['Country']=="Canada") {$total_canada = $row['Cnt'];continue;}
			$total_other = $total_other + $row['Cnt'];
			}
		hopdb_free_result($r);

		$most = 0; $mostnm = "";
		$least = 100000; $leastnm = "";
		$r = hopdb_query("SELECT State, COUNT(ID) AS Cnt FROM hoplist GROUP BY State Order By Cnt DESC");
		$states = array();
		while($row = hopdb_fetch_assoc($r))
			{
//print "<br/>$row[State]=$row[Cnt]\n";
			if ($row['Cnt'] > $most) {$most = $row['Cnt']; $mostnm = $row['State'];}
			if ($row['Cnt'] < $least) {$least = $row['Cnt']; $leastnm = $row['State'];}
			if ($row['State'] <> "") $states[$row['State']] = $row['Cnt'];
			}
//print "<br/>st: ";print_r($states);
		hopdb_free_result($r);


        $instance = [];
		$title = apply_filters('widget_title', $instance['title'] );
		$sCnt =  isset( $instance['countries'] ) ? true : false;
		$sLMS =  isset( $instance['leastmost'] ) ? true : false;


///////   Our Widget Display code
		$w = $w . "<b>Total HoPs:</b> $total_count<br/>\n";
		if ($sCnt)
			{
			$w = $w . "<span style='margin-left:10px;'><b>USA:</b> $total_usa</span><br/>\n";
			if ($total_canada>0) $w = $w . "<span style='margin-left:10px;'><b>Canada:</b> $total_canada</span><br/>\n";
			if ($total_other>0) $w = $w . "<span style='margin-left:10px;'><b>Other:</b> $total_other</span><br/>\n";
			$w = $w . "<br/>\n";
			}
		if ($sLMS)
			{
			$w = $w . "<span style='margin-left:10px;'><b>Most HoP'd State:</b> $mostnm ($most)</span><br/>\n";
			$w = $w . "<span style='margin-left:10px;'><b>Least HoP'd State:</b> $leastnm ($least)</span><br/>\n";
			$w = $w . "<br/>\n";
			}

	$w = $w . "<table align='center' width='300'>\n";
	foreach($states as $s => $k)
		{
		$w = $w . "<tr><td><a href='/?state=$s'>$s</a></td><td>$k</td></tr>\n";
		}
	$w = $w . "</table>\n";

	print $w;
	}

