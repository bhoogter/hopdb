<?php

function hopdb_integrity_place($l)
	{
	return "$l[ID]: $l[Name] - $l[City], ".($l[State]!=""?$l[State]:$l[Country]);
	}
function hopdb_integrity()
	{
	$s = "";
	$s = $s . "<table style='border: solid 1px black;border-collapse:collapse;'>\n";

	$r = get_hops();
	while($o = hopdb_fetch_assoc($r))
		{
		
		$matches = array();
		$q = hopdb_query("SELECT hoplist.*, soundex(city) as cex FROM hoplist WHERE ID<>$o[ID] AND State='$o[State]' AND (City='$o[City]' AND Country='$o[Country]' OR SoundEx(City)=Soundex('$o[City]')) ORDER BY ID");
		while ($p = hopdb_fetch_assoc($q))
			{
			if ($p[City]==$o[City])	$matches[] = array("lightblue", "Same City", hopdb_integrity_place($p));
			else if ($p[cex]==$o[cex])	$matches[] = array("blue", "Similar City", hopdb_integrity_place($p));
			}
		hopdb_free_result($q);

		if ($o[Position]=="") $matches[] = array("orange", "No Position", );
		if ($o[City]=="") $matches[] = array("lightred", "No City", );
		if ($o[Website]=="") $matches[] = array("yellow", "No Website", "");
		
		if (count($matches)>0)
			{
			$s = $s . " <tr style='border: solid 1px black;border-collapse:collapse;'>\n";
			$s = $s . "  <td style='border: solid 1px black;border-collapse:collapse;'>".hopdb_integrity_place($o)." - <a href='/wp-admin/admin.php?page=edithop&id=$o[ID]'>Edit</a> - <a href='$o[Website]'>Website</as></td>\n";
			$s = $s . "  <td style='border: solid 1px black;border-collapse:collapse;'>\n";
			foreach($matches as $a)
				$s = $s . "<span style='background-color:$a[0]'>". $a[1] . ($a[2]!=""?": ":"") . "</span>" . $a[2] . "<br/>\n";
			$s = $s . "  </td>\n";
			$s = $s . " </tr>\n";
			}
		}
	$s = $s . "</table>\n";
	return $s;
	}

?>