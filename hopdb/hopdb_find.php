<?php

// [hopdb-find]
function hopdb_shortcode_find( $atts )
	{
	extract( shortcode_atts( array('foo' => 'something','bar' => 'something else',), $atts ) );

	if (($x=$_REQUEST["state"]) == "") return hopdb_statemap();
	
	return hopdb_state($x);
	}

// [hopdb-list]
function hopdb_shortcode_list( $atts )
	{
	extract( shortcode_atts( array('foo' => 'something','bar' => 'something else',), $atts ) );

	return hopdb_user_list();
	}


function hopdb_statemap()
	{
	$imagesDir = hopdb_plugin_dir() . "/images/backgrounds/";
//print "<br/>imagesDir=$imagesDir";
	$images = glob($imagesDir . '*.{jpg,jpeg,png,gif}', GLOB_BRACE);
	$randomImage = hopdb_plugin_url() . "/images/backgrounds/" . basename($images[array_rand($images)]);
//print "<br/>randomImage=$randomImage ";

	$s = '';
	$s = $s . "<div class='state-select-wide'>\n";
	$s = $s . "<div style='position:relative;width:600px;height:383px;background-image:url($randomImage);'>\n";
	$s = $s . '<img src="'.hopdb_plugin_url("/images/usmap-90.png").'" alt="US Map" width="600" height="383" usemap="#USA" style="">' . "\n";
//	$s = $s . '<img src="'.hopdb_plugin_url("/images/backgrounds/wheat-1.png").'" alt="Background" width="600" height="383" style="position:absolute;top:0px;left:0px;" >' . "\n";
	$s = $s . "</div>\n";
	$s = $s . '<map name="USA">' . "\n";
	$s = $s . '<area shape="poly" coords="44,7,56,14,62,5,114,16,106,56,55,50,42,34" href="?state=wa" alt="Washington">' . "\n";
	$s = $s . '<area shape="poly" coords="22,89,43,41,51,51,109,59,96,109" href="?state=or" alt="Oregon">' . "\n";
	$s = $s . '<area shape="poly" coords="24,92" href="?#" alt="Oregon">' . "\n";
	$s = $s . '<area shape="poly" coords="24,93,17,118,28,174,34,200,50,214,67,240,95,241,100,208,56,146,65,104" href="?state=ca" alt="California">' . "\n";
	$s = $s . '<area shape="poly" coords="67,102,55,143,101,206,127,118" href="?state=nv" alt="Nevada">' . "\n";
	$s = $s . '<area shape="poly" coords="116,16,97,107,154,122,158,86,142,83,130,68,132,52,122,37,123,19" href="?state=id" alt="Idaho">' . "\n";
	$s = $s . '<area shape="poly" coords="126,117,113,185,168,193,174,139,154,139,153,122" href="?state=ut" alt="Utah">' . "\n";
	$s = $s . '<area shape="poly" coords="114,187,110,201,105,195,101,208,106,220,99,235,94,247,134,269,155,271,165,195" href="?state=az" alt="Arizona">' . "\n";
	$s = $s . '<area shape="poly" coords="124,16,123,37,135,54,130,68,136,67,142,82,160,84,161,80,232,85,234,32,181,30" href="?state=mt" alt="Montana">' . "\n";
	$s = $s . '<area shape="poly" coords="163,81,154,135,226,143,231,88" href="?state=wy" alt="Wyoming">' . "\n";
	$s = $s . '<area shape="poly" coords="175,139,247,145,246,201,234,201,168,193" href="?state=co" alt="Colorado">' . "\n";
	$s = $s . '<area shape="poly" coords="167,193,158,274,167,274,168,268,187,268,187,263,228,268,232,199" href="?state=nm" alt="New Mexico">' . "\n";
	$s = $s . '<area shape="poly" coords="234,33,233,74,304,78,298,35" href="?state=nd" alt="North Dakota">' . "\n";
	$s = $s . '<area shape="poly" coords="233,76,229,115,285,115,287,121,295,118,304,121,302,77" href="?state=sd" alt="South Dakota">' . "\n";
	$s = $s . '<area shape="poly" coords="229,117,227,144,248,145,248,157,315,158,305,123,288,120,284,114" href="?state=ne" alt="Nebraska">' . "\n";
	$s = $s . '<area shape="poly" coords="245,160,246,199,324,201,324,172,320,168,321,162,315,160" href="?state=ks" alt="Kansas">' . "\n";
	$s = $s . '<area shape="poly" coords="233,201,233,208,266,208,266,238,286,242,300,245,320,244,327,248,327,220,324,202" href="?state=ok" alt="Oklahoma">' . "\n";
	$s = $s . '<area shape="poly" coords="233,208,231,270,186,265,193,275,205,285,210,302,217,307,224,312,231,302,238,300,246,302,254,310,259,322,268,332,271,347,290,354,297,355,294,341,294,331,306,318,324,311,324,304,336,302,337,290,342,285,332,272,333,252,320,246,297,248,268,238,267,209" href="?state=tx" alt="Texas">' . "\n";
	$s = $s . '<area shape="poly" coords="299,35,304,82,305,110,357,110,358,103,341,93,337,77,346,65,363,48,324,41,318,32" href="?state=mn" alt="Minnesota">' . "\n";
	$s = $s . '<area shape="poly" coords="303,110,304,123,311,151,353,150,356,151,362,143,359,138,371,134,356,110" href="?state=ia" alt="Iowa">' . "\n";
	$s = $s . '<area shape="poly" coords="313,152,317,160,322,160,320,166,324,172,326,207,375,206,375,213,378,216,384,201,381,188,372,183,373,173,363,166,356,157,356,151" href="?state=mo" alt="Missouri">' . "\n";
	$s = $s . '<area shape="poly" coords="324,210,328,220,329,250,334,251,334,260,368,257,365,248,379,220,373,215,374,205" href="?state=ar" alt="Arkansas">' . "\n";
	$s = $s . '<area shape="poly" coords="334,260,334,272,341,285,338,303,357,305,360,300,373,308,395,309,384,289,384,283,363,283,369,267,365,256" href="?state=la" alt="Louisiana">' . "\n";
	$s = $s . '<area shape="poly" coords="345,67,339,79,342,93,358,101,358,112,366,123,392,120,391,98,395,84,384,73,367,70,364,65,356,62" href="?state=wi" alt="Wisconsin">' . "\n";
	$s = $s . '<area shape="poly" coords="367,123,372,133,362,139,357,155,369,167,372,173,372,181,381,188,382,195,390,196,397,178,400,175,398,168,396,130,391,120" href="?state=il" alt="Illionois">' . "\n";
	$s = $s . '<area shape="poly" coords="392,80,384,72,368,70,365,65,385,50,399,66,411,58,425,62,426,67,412,68,396,75" href="?state=mi" alt="Michigan">' . "\n";
	$s = $s . '<area shape="poly" coords="417,71" href="?#" alt="Michigan">' . "\n";
	$s = $s . '<area shape="poly" coords="417,72,406,88,403,99,405,108,409,111,409,121,406,128,440,125,446,105,432,77" href="?state=mi" alt="Michigan">' . "\n";
	$s = $s . '<area shape="poly" coords="405,129,398,133,401,172,396,185,406,185,414,179,419,181,419,174,423,170,430,164,426,128" href="?state=in" alt="Indiana">' . "\n";
	$s = $s . '<area shape="poly" coords="427,127,430,163,435,168,450,167,454,171,457,160,468,152,470,140,466,118,452,129,440,125" href="?state=oh" alt="Ohio">' . "\n";
	$s = $s . '<area shape="poly" coords="377,226,366,246,368,256,369,265,364,280,385,281,386,290,389,294,399,289,400,273,398,224" href="?state=ms" alt="Mississippi">' . "\n";
	$s = $s . '<area shape="poly" coords="400,225,400,288,413,290,410,278,438,276,438,266,440,259,437,254,427,221" href="?state=al" alt="Alabama">' . "\n";
	$s = $s . '<area shape="poly" coords="383,206,379,225,440,220,445,211,450,211,469,196,398,202,398,205" href="?state=tn" alt="Tennessee">' . "\n";
	$s = $s . '<area shape="poly" coords="385,196,385,205,399,205,399,200,447,196,460,183,449,167,436,168,431,165,418,180,407,185,397,185" href="?state=ky" alt="Kentucky">' . "\n";
	$s = $s . '<area shape="poly" coords="428,222,440,260,441,277,474,279,477,274,481,275,486,255,455,224,455,219" href="?state=ga" alt="Georgia">' . "\n";
	$s = $s . '<area shape="poly" coords="410,280,414,289,427,286,437,290,442,297,451,288,458,288,467,298,473,302,475,322,503,350,510,349,511,328,499,303,481,274,476,281" href="?state=fl" alt="Florida">' . "\n";
	$s = $s . '<area shape="poly" coords="443,279" href="?#" alt="Florida">' . "\n";
	$s = $s . '<area shape="poly" coords="487,255,454,219,464,213,479,213,481,217,495,216,508,225,503,239" href="?state=sc" alt="South Carolina">' . "\n";
	$s = $s . '<area shape="poly" coords="442,220,468,195,530,184,528,191,535,191,531,206,517,225,508,226,495,216,481,217,479,213,462,214" href="?state=nc" alt="North Carolina">' . "\n";
	$s = $s . '<area shape="poly" coords="448,196,529,183,522,171,511,161,501,162,501,151,489,164,481,179,465,186,461,183" href="?state=va" alt="Virginia">' . "\n";
	$s = $s . '<area shape="poly" coords="552,175,552,184,537,184,536,178,511,160,502,160,501,151,508,146,514,151,514,156,534,174" href="?state=dc" alt="Washington, DC">' . "\n";
	$s = $s . '<area shape="poly" coords="470,144,458,162,457,175,466,185,480,179,501,149,484,156,483,147,472,149" href="?state=wv" alt="West Virginia">' . "\n";
	$s = $s . '<area shape="poly" coords="484,147,484,154,507,147,515,150,516,157,530,167,532,158,525,156,522,139" href="?state=md" alt="Maryland">' . "\n";
	$s = $s . '<area shape="poly" coords="548,158,527,157,525,141,534,143,549,145" href="?state=de" alt="Delaware">' . "\n";
	$s = $s . '<area shape="poly" coords="473,148,467,118,474,114,475,117,520,107,528,114,524,128,530,130,525,139" href="?state=pa" alt="Pennsylvania">' . "\n";
	$s = $s . '<area shape="poly" coords="526,140,533,142,551,141,551,130,539,129,534,124,536,118,527,115,525,126,531,129" href="?state=nj" alt="New Jersey">' . "\n";
	$s = $s . '<area shape="poly" coords="475,114,475,117,519,107,535,117,537,123,557,114,538,117,534,83,529,63,512,65,505,80,505,88,499,94,480,97,481,104" href="?state=ny" alt="New York">' . "\n";
	$s = $s . '<area shape="poly" coords="538,103,540,114,556,108,552,99" href="?state=ct" alt="Connecticut">' . "\n";
	$s = $s . '<area shape="poly" coords="553,100,558,108,561,114,571,115,570,105,562,105,559,98" href="?state=ri" alt="Rhode Island">' . "\n";
	$s = $s . '<area shape="poly" coords="539,101,557,97,563,101,568,97,561,89,560,85,537,91" href="?state=ma" alt="Massachusetts">' . "\n";
	$s = $s . '<area shape="poly" coords="530,63,537,89,544,89,543,78,542,69,547,64,545,58" href="?state=vt" alt="Vermont">' . "\n";
	$s = $s . '<area shape="poly" coords="545,88,560,85,560,79,548,52,545,59,548,64,546,69" href="?state=nh" alt="New Hampshire">' . "\n";
	$s = $s . '<area shape="poly" coords="549,53,560,79,564,69,572,65,572,58,577,58,590,48,581,39,569,16,558,20,556,28,555,44" href="?state=me" alt="Maine">' . "\n";
	$s = $s . '<area shape="poly" coords="117,376,6,377,6,284,115,283,117,339,118,379" href="?state=ak" alt="Alaska">' . "\n";
	$s = $s . '<area shape="poly" coords="168,338,169,378,118,378,117,339" href="?state=hi" alt="Hawaii">' . "\n";
	$s = $s . '<area shape="poly" coords="44,5,56,13,62,3,181,30,300,34,318,30,365,45,411,57,427,64,446,104,503,80,512,65,546,53,557,21,569,16,570,0,42,0" href="?state=cn" alt="Canada">' . "\n";
	$s = $s . '</map>' . "\n";
	$s = $s . '<hr>' . "\n";
	$s = $s . 'Click <a href="?state=xx">here</a> for international locations.';
	$s = $s . '<hr>' . "\n";
	$s = $s . "</div>\n";
	$s = $s . "<div class='state-select-narrow'>\n";
	$s = $s . "<select name=state onChange=\"window.location.href='?state='+this.value.substring(0,2)\">\n";
	$s = $s . " <option>Select Location...</option>\n";
	$s = $s . " <option>XX - International</option>\n";
	$s = $s . " <option>CN - Canada</option>\n";
	$s = $s . '<option>AL - Alabama</option>';
	$s = $s . '<option>AK - Alaska</option>';
	$s = $s . '<option>AZ - Arizona</option>';
	$s = $s . '<option>AR - Arkansas</option>';
	$s = $s . '<option>CA - California</option>';
	$s = $s . '<option>CO - Colorado</option>';
	$s = $s . '<option>CT - Connecticut</option>';
	$s = $s . '<option>DE - Delaware</option>';
	$s = $s . '<option>FL - Florida</option>';
	$s = $s . '<option>GA - Georgia</option>';
	$s = $s . '<option>HI - Hawaii</option>';
	$s = $s . '<option>ID - Idaho</option>';
	$s = $s . '<option>IL - Illinois</option>';
	$s = $s . '<option>IN - Indiana</option>';
	$s = $s . '<option>IA - Iowa</option>';
	$s = $s . '<option>KS - Kansas</option>';
	$s = $s . '<option>KY - Kentucky</option>';
	$s = $s . '<option>LA - Louisiana</option>';
	$s = $s . '<option>ME - Maine</option>';
	$s = $s . '<option>MD - Maryland</option>';
	$s = $s . '<option>MA - Massachusetts</option>';
	$s = $s . '<option>MI - Michigan</option>';
	$s = $s . '<option>MN - Minnesota</option>';
	$s = $s . '<option>MS - Mississippi</option>';
	$s = $s . '<option>MO - Missouri</option>';
	$s = $s . '<option>MT - Montana</option>';
	$s = $s . '<option>NE - Nebraska</option>';
	$s = $s . '<option>NV - Nevada</option>';
	$s = $s . '<option>NH - New Hampshire</option>';
	$s = $s . '<option>NJ - New Jersey</option>';
	$s = $s . '<option>NM - New Mexico</option>';
	$s = $s . '<option>NY - New York</option>';
	$s = $s . '<option>NC - North Carolina</option>';
	$s = $s . '<option>ND - North Dakota</option>';
	$s = $s . '<option>OH - Ohio</option>';
	$s = $s . '<option>OK - Oklahoma</option>';
	$s = $s . '<option>OR - Oregon</option>';
	$s = $s . '<option>PA - Pennsylvania</option>';
	$s = $s . '<option>RI - Rhode Island</option>';
	$s = $s . '<option>SC - South Carolina</option>';
	$s = $s . '<option>SD - South Dakota</option>';
	$s = $s . '<option>TN - Tennessee</option>';
	$s = $s . '<option>TX - Texas</option>';
	$s = $s . '<option>UT - Utah</option>';
	$s = $s . '<option>VT - Vermont</option>';
	$s = $s . '<option>VA - Virginia</option>';
	$s = $s . '<option>WA - Washington</option>';
	$s = $s . '<option>WV - West Virginia</option>';
	$s = $s . '<option>WI - Wisconsin</option>';
	$s = $s . '<option>WY - Wyoming</option>';
	$s = $s . "</select>\n";
	$s = $s . "</div>\n";
	return $s;
	}

function hopdb_state($st)
	{
	$state = hopdb_state_name($st);
	if ($st=="xx") $state = "International";
	if ($st=="cn") $state = "Canada";

	switch(strtolower($st))
		{
		case "cn": $sql = "SELECT * FROM hoplist WHERE Country = 'Canada' ORDER BY Name;";break;
		case "xx": $sql = "SELECT * FROM hoplist WHERE NOT Country IN ('Canada','United States') ORDER BY Country, Name;";break;
		default:   $sql = "SELECT * FROM hoplist WHERE State = '$st' ORDER BY Name;";break;
		}
	$result = hopdb_query($sql);
	if (mysqli_num_rows($result) == 0) return "Sorry, there are no Houses of Prayer in the state of $state that we know of. <br /> If you know of any, please email me".(hopdb_contact()==""?"":" at ".hopdb_contact()).".";

	$s = "";

	$s = $s . "<p><a href=\"http://www.ihopnetwork.com\">Back to the United State Map</a></p><br />\n";
	$s = $s . "<p><font size=\"5\">".$state."</font></p><br />\n";
	$s = $s . "<table>\n";
	$s = $s . "	<tr>\n";
	$s = $s . "		<td>\n";
	$s = $s . "			Below is a listing of Houses of Prayer in $state.\n";
	$s = $s . "			If you know of any Houses of Prayer that need to be added, \n";
	$s = $s . "			please contact us by e-mail <span class='email'>".(hopdb_contact()==""?"":" at ".hopdb_contact()). "</span> or through the Contact Us page. \n";
	$s = $s . "			We are always looking for more Houses of Prayer to list.\n";
	$s = $s . "		</td>\n";
	$s = $s . "	</tr>\n";

	// table headers
	while ($row = mysqli_fetch_assoc($result)) 
		{
		$s = $s . "	<tr><td><br /><hr /><br /></td></tr>\n";
		$s = $s . "	<tr>\n";
		$s = $s . "		<td>\n";
		$s = $s . "			<table width=\"100%\" >\n";
		$s = $s . "				<tr>\n";
		$s = $s . "					<td itemscope='1' itemtype='http://schema.org/Organization'>\n";
		$s = $s . "						<a id='L".$row['ID']."'></a>\n";
		$s = $s . "						<a title=\"".$row['Name']."\" target=\"_blank\" href=\"".$row['Website']."\">\n";

		if ($row['Graphic'] != "")
			{ 
			$t = $row['Graphic'];
			$w = 0;
//print "<br/>".hopdb_plugin_dir("/links/".$t);
			if (strstr($t, "/")===false) 
				{
				if ($size = @getimagesize(hopdb_plugin_dir("/links/".$t))) $w = $size[0];
				$t = hopdb_plugin_url("/links/$t");
				}
//			$t = hopdb_plugin_url("/timthumb.php?w=200&zc=1&src=$t");
			$s = $s . "						<img itemprop='image' src=\"$t\" alt=\"".$row['Name']."\" title=\"".$row['Name']."\"".($w>=550?" width=\"550\"":"")."/><br/>\n";
			}

		$s = $s . "						<font size=\"4\"><strong><span itemprop='name'>".$row['Name']."</span>\n";
		if ($row['City'] != "")
		$s = $s . "					 - ".$row['City'].", ".($st=="xx"||$st=="cn"?$row['Country']:$row['State'])."\n";
		$s = $s . "						</strong></font></a><br />\n";
					

		$s = $s . "<img align='right' width='16' height='16' title='Category: ".$row['Category']."' alt='Category: ".$row['Category']."' src='".hopdb_plugin_url("/images/feed/".hopdb_category_icon($row['Category']))."' />\n";
		if ($row['Website'] != "")
			$s = $s . "					<a title=\"".$row['Name']."\" target=\"_blank\" href=\"$row[Website]\"><span class='url' itemprop='url'>$row[Website]</span></a><br />\n";
		if ($row['Director'] != "")
			$s = $s . "					Director: <span itemprop='founder'>$row[Director]</span><br />\n";
		if ($row['Email'] != "")
			$s = $s . "					Email: <a href=\"mailto:".$row['Email']."\"><span class='email' itemprop='email'>".$row['Email']."</span></a><br />\n"; 
		if ($row['Phone'] != "")
			$s = $s . "					Phone: <span itemprop='telephone'>$row[Phone]</span><br />\n";
		       $s = $s . "					<span itemprop='address' itemscope='1' itemtype='http://schema.org/PostalAddress' itemref='_addressRegion4'>\n";
		if ($row['Address'] != "") 
			$s = $s . "					<br /><span itemprop='streetaddress'>$row[Address]</span><br />\n";

		if ($row['Address2'] != "")
			$s = $s . "$row[Address2]<br />";

		$s = $s . "				<span itemprop='addressLocality'>".$row['City']."</span>, ".($st=="xx"||$st=="cn"?("<span itemprop='localityCountry'>".$row['Country']."</span>"):("<span itemprop='addressRegion'>".$row['State']."</span>"))." <span itemprop='postalCode'>".$row['Zip']."</span><br />";
	       $s = $s . "					</span>\n";

		if ($row['Description'] != "")
			$s = $s . "					<br /><div class='description' ><span itemprop='description'>".str_replace("\n","<br/>\n",$row['Description'])."</span></div>\n";

		$s = $s . "						<a href='/index.php/home/edit?id=".$row['ID']."'><img style='float:right;' src='".hopdb_plugin_url('/images/edit.png')."' width='16' height='16' alt='Edit this Entry' title='Edit this Entry'/></a>\n";
		$s = $s . "					</td>\n";
		$s = $s . "				</tr>\n";
		$s = $s . "			</table>\n";
		}

	$s = $s . "		</td>\n";
	$s = $s . "	</tr>\n";
	$s = $s . "</table>\n";
	return $s;
	}

function hopdb_user_list()
	{
	$r = get_hops();
	$s = "";
	$s = $s . "<ul>\n";
	$s = $s . "<li><hr/><h3>International</h3></li>\n";
	while ($row = mysqli_fetch_assoc($r)) 
		{
		$c = $row['Country'];
		$inter = $c!="United States";
		$cc = strtolower(hopdb_country_code($c));
		if ($st!=$row['State'])
			{
			$st = $row['State'];
			$s = $s . "<li><hr/><h3>".hopdb_state_name($st)."</h3></li>\n";
			}
		$s = $s . "  <li>";
		if ($row['Website']!="") $s = $s . "<a href='" . $row['Website'] . "'>";
		if ($inter && $cc != "") $s = $s . "<img width='16' height='11' src='".hopdb_plugin_url("/images/countries/$cc.png")."' alt='$c' title='$c' />\n";
		$s = $s . "$row[Name] - $row[City], ".($inter ? $row['Country'] : $row['State']);
		if ($row['Website']!="") $s = $s . "</a>";
		$s = $s . "</li>\n";
		}
	$s = $s . "</ul>\n";
	return $s;
	}


?>
