<?php

function hopdb_date_format()	{return "Y-m-d";}		// in case your mysql is somehow different...?

function hopdb()
	{
	global $db_link;
	$db_user = get_option("HOPDB_user");
	$db_pass = get_option("HOPDB_pass");
	$db_host = get_option("HOPDB_host");
	$db_database = get_option("HOPDB_name");

//	$db_user = "churchu8_wp";
//	$db_pass = "Wp123#";
//	$db_host = "localhost";
//	$db_database = "churchu8_ihop";

	if ($db_link != 0) return $db_link;
//print "<br/>user=$db_user, pass=$db_pass, host=$db_host, db=$db_database";
    
    $db_link = mysqli_connect($db_host, $db_user, $db_pass);

	if (mysqli_connect_errno()) 
		{
		print "Problem connecting to database [$db_user:***@$db_host]: " . mysqli_connect_error();
		return;
		}
	//select the right database
	if (!($db_selected = mysqli_select_db($db_link, $db_database))) 
		{
		print 'Problem selecting database: ' . mysqli_error($db_link);
		return;
		}
	return $db_link;
	}

function hopdb_category_config($user='')
	{
	$a = array();
	$a['Prayer House'] 		= array("ihop.png");
	$a['Boiler Room'] 		= array("br.png");
	$a['Furnace']	 		= array("furnace.png");
	if ($user=='')
	$a['Luke18Project']		= array("l18.png");
	$a['Bound4Life'] 		= array("b4l.png");
	$a['Healing Room'] 		= array("hr.png");
	$a['Soaking Center'] 	= array("soak.png");
	$a['Burn247']			= array("b247.png");
	if ($user=='') 
	$a['JHOP']			= array("jhop.png");
	$a['Prayer Network'] 	= array("net.png");
	$a['Other'] 			= array("other.png");
	return $a;
	}

function hopdb_categories($user='')	{	return array_keys(hopdb_category_config($user));	}
function hopdb_category_icon($i)
	{
	$x = hopdb_category_config();
	$y = $x[$i];
	return !$y ? $x['Other'][0] : $y[0];
	}

function hopdb_fields($user='')
	{
	return $user=='' ? 
		array("ID", "Name", "Address", "Address2", "City", "State", "Zip", "Country", "Category", "Description", "Graphic", "Email", "Website", "Phone", "Director", "Password", "Position") :
		array("ID", "Name", "Address", "Address2", "City", "State", "Zip", "Country", "Category", "Description", "Graphic", "Email", "Website", "Phone", "Director");
	}

function hopdb_field_limits($user='')
	{
	return array( "ID"=>-1, "Name"=>255, "Address"=>255, "Address2"=>255, "City"=>50, "State"=>10, "Zip"=>20, "Country"=>50, "Category"=>20, "Description"=>-1,
			"Graphic"=>255, "Email"=>100, "Website"=>100, "Phone"=>50, "Director"=>50, "Password"=>24, "Position"=>30 );
	}


function hopdb_query($q)
	{
	if (!($link = hopdb())) return;
	if (!($r = mysqli_query($link, $q))) wp_die("Error in SQL [$q]: " . mysqli_error($link));
	return $r;
	}

function hopdb_record_count($r)	{	return @mysqli_num_rows($r);	}
function hopdb_fetch_assoc($r)	{	return mysqli_fetch_assoc($r);	}
function hopdb_free_result($r)	{	@mysqli_free_result($r); return true;	}

function hopdb_execute($q)
	{
	if (!($link = hopdb())) return;
	if (!($r = @mysqli_query($link, $q))) wp_die("Error in SQL: $q");
//	mysqli_free_result($r);
	return true;
	}

function hopdb_query_row($q)
	{
	if (!($link = hopdb())) return;
	if (!($r = mysqli_query($link, $q))) die("Error in SQL: $q");
	$row = mysqli_fetch_assoc($r);
	return $row;
	}

function get_hop_row($id, $user='')	{	return hopdb_query_row("SELECT * FROM ".$user."hoplist WHERE ID=$id");	}
function get_hops($st="", $user='')	{	return hopdb_query("SELECT * FROM ".$user."hoplist".($st==""?"":" WHERE State='$st'")." ORDER BY State, Country, Name"); }
function get_user_hop_row($id)		{	return get_hop_row($id,"user");	}
function get_user_hops() 			{	return get_hops("", "user");	}
function get_hop_count($st="", $user='')	{	$r = get_hops('', $user); $x = mysqli_num_rows($r); mysqli_free_result($r); return $x;	}

function hopdb_insert($row, $user='')
	{
	$lim = hopdb_field_limits($user);
	$fields = hopdb_fields($user);
	$row = hopdb_cleanrow($row);
	$valuelist = "";
	$fieldlist = "";
	foreach ($fields as $f) 
		{
		if ($f == "ID") continue;
		$fieldlist = $fieldlist . ($f=="Name"?"":", ") . $f;
		$v = $row[$f];
		if ($lim[$f]>0) $v = substr($v, 0, $lim[$f]);
		
		if ($f!="ID") $valuelist = $valuelist . ($f=="Name"?"":", ")."'" . str_replace("'","",$v) . "'";
		}
	if ($user=='')
		{
		$fieldlist .= ",SubmissionDate,UpdateDate";
		$valuelist .= ",'".date(hopdb_date_format())."','".date(hopdb_date_format())."'";
		}
	$query = "INSERT INTO ".$user."hoplist (".$fieldlist.") VALUES ($valuelist);";

	$x =  hopdb_execute($query);
	hopdb_write_feeds();
	return $x;
	}

function hopdb_update($row, $user='')
	{
//print_r($row);
	$lim = hopdb_field_limits($user);
	$row = hopdb_cleanrow($row);
	$s = "UPDATE ".$user."hoplist SET ";
	foreach(hopdb_fields($user) as $a) 
		{
		if ($a == "ID") continue;
		$v = str_replace("'", "", $row[$a]);
		$v = str_replace("]]>", "]]", $v);
		if ($lim[$a] > 0) $v = substr($v, 0, $lim[$a]);

		$s = $s . ($a=="Name"?"":", ") . $a . " = " . "'" . $v . "'";
		}
	if ($user=='')
		$s .= ", UpdateDate='".date(hopdb_date_format())."'";
	
	$s = $s . " WHERE ID=$row[ID]";
//wp_die($s);
	$x =  hopdb_execute($s);
	hopdb_write_feeds();
	return $x;
	}

function hopdb_getpost()
	{
	$r = array();
	foreach(hopdb_fields() as $a) $r[$a] = $_REQUEST[$a];
	return $r;
	}

function hopdb_rowvalid($row, &$msg="")
	{
	if ($row['Name']=="") {$msg = "You must enter a name."; return false;}
	if ($row['Country']=="") {$msg = "Country is a required field."; return false;}
	if ($row['Country']=="United States" && $row['State']=="") {$msg = "Please enter a US State."; return false;}
	if ($row['Website']=="" && $row['Phone']=="" && $row['Email']=="")
		 {$msg = "You must enter either a website, phone number, or email address."; return false;}
	return true;
	}

function hopdb_cleanrow($r)
	{
//print "<br/>pwd=".hopdb_sitepassword($r[ID]);
	foreach($r as $a=>&$b)
		{
		$b = trim($b);
		if ($b == "") continue;
		$b = utf8_decode($b);
		switch(strtolower($a))
			{
			case "graphic":
				if (substr($b, 0, 7) != "http://" && substr($b, 0, 8) != "https://") break;

				$ch = curl_init();
				curl_setopt ($ch, CURLOPT_URL, $b);
				curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, 0);
				$image_string = curl_exec($ch);
				curl_close($ch);

				$newImg = imagecreatefromstring($image_string);
				$p = hopdb_plugin_dir("/links/");

				$ext = strtolower(substr($b,strlen($b)-3));
				$b = $r['State']."-".$r['City']."-".uniqid();
				switch($ext)
					{
					case "jpg": case "peg": 	
						imagejpeg($newImg, "$p$b.jpg",100);$b = $b.".jpg";break;
					case "bmp":
						imagewbmp($newImg, "$p$b.bmp");$b = $b.".bmp";break;
					case "png":
						imagesavealpha ( $newImg , true );
						imagepng($newImg, "$p$b.png");$b = $b.".png";break;
					case "gif":			
						imagegif($newImg, "$p$b.gif");$b = $b.".gif";break;
					default:			
						imagepng($newImg, "$p$b.jpg");$b = $b.".jpg";break;
					}
				break;
			case "country":
				switch(trim(strtolower($b)))
					{
					case "us": case "usa": case "united states":	$b = "United States"; break;
					case "uk": case "england":				$b = "England"; break;
					}
				break;
			case "state":		if (!hopdb_is_state($b)) $b="";break;
			case "email":		if (false === strstr($b, "@")) $b = "";break;
			case "website":	
						if (substr($b, 0, 7)!="http://" && substr($b, 0, 8)!="https://") $b = "http://$b";
						if (substr($b, strlen($b) - 4) == ".com") $b = $b . "/";
						if (substr($b, strlen($b) - 4) == ".net") $b = $b . "/";
						if (substr($b, strlen($b) - 4) == ".org") $b = $b . "/";
						break;
//			case "password":	if (hopdb_sitepassword($r[ID])=="") $b = hopdb_new_password();break;
			}
		}

	if (''.$r['Position'] == '' || true)  // always refresh location for now....
		{
		$ta = $r['Address'] . ',' . $r['Address2'] . ',' . $r['City'] . ',' . $r['State'] . ' ' . $r['Zip'] . ',' . $r['Country'];
		$ta = str_replace(array(',,,,',',,,',',,'), array(',',',',','), $ta);

		include_once("hopdb_geocoding.php");
		$r['Position'] = hopdb_address_lookup($ta);
		}

	if (''.$r['Password'] == '') $r['Password'] = hopdb_new_password();
	if (''.$r['Password'] == hopdb_masterpass()) $r['Password'] = hopdb_sitepassword($r['ID']);
//print_r($r);wp_die();
	return $r;
	}

function hopdb_new_password()	{	return "".rand(1000000, 9999999);	}

function hopdb_sitepassword($id)
	{
//print "<br/>hopdb_sitepassword($id)";
	if ($id=="") return "";
//print "<br/>hopdb_sitepassword($id)";
	$row = hopdb_query_row("SELECT * FROM hoplist WHERE ID=$id");
//print_r($row);
	return $row['Password'];
	}

function hopdb_integrate($row)
	{
	hopdb_update($row, 'user');
	hopdb_insert(get_user_hop_row($row['ID']));
	hopdb_query("DELETE FROM userhoplist WHERE ID=${row['ID']}");
	return true;
	}

