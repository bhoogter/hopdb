<?php

function hopdb_protect_sql($s)	{	return str_replace("'", "", $s);	}

function hopdb_edit_id()		{	return (''.intval(@$_REQUEST['id']));	}
function hopdb_edit_action()	{	return hopdb_protect_sql(@$_REQUEST['action']);	}
function hopdb_edit_state()		{	return hopdb_protect_sql(substr(get_query_var('state') /*$_REQUEST[state]*/,2));	}


function hopdb_shortcode_submit($atts)	{	return hopdb_entry_form(null, "usersubmission");	}
function hopdb_shortcode_useredit($atts)	{	return hopdb_entry_form(get_hop_row(hopdb_edit_id()), "useredit");	}

function hopdb_hop_add()	{      return hopdb_entry_form(null, "add");	}
function hopdb_hop_review()
	{
	if (hopdb_edit_id() == 0) return hopdb_edit_list(get_hops("", "user"), true);
	return hopdb_entry_form(get_hop_row(hopdb_edit_id(), "user"),"review");
	}
function hopdb_hop_edit()
	{
//print "<br/>edit_state=" . hopdb_edit_state();
	if (hopdb_edit_state()=="" && hopdb_edit_id()=="") return hopdb_choosestate();
	if (hopdb_edit_id()=="") return hopdb_edit_list(get_hops(hopdb_edit_state()));
	return hopdb_entry_form(get_hop_row(hopdb_edit_id()), "edit");
	}

function hopdb_hop_list()
	{
	$r = get_hops();

	$st = "";
	$s = $s . "<h3>Listing of HoPs</h3>";
	$s = $s . "<ul>\n";
	$s = $s . "<li><hr/><h3>International</h3></li>\n";
	while ($row = mysqli_fetch_assoc($r)) 
		{
		if ($st!=$row['State'])
			{
			$st = $row['State'];
			$s = $s . "<li><hr/><h3>".hopdb_state_name($st)."</h3></li>\n";
			}
		$k = $row['Name']." - ".$row['City'].", ".$row['State'];
		$k = $k . ($row['Country']=="United States"?"":$row['Country']);
		$l = "<img src='" . hopdb_plugin_url("/images/feed/".hopdb_category_icon($row['Category'])) . "' width=8 height=8 />";
		$s = $s . " <li><a href='?id=${row['ID']}&page=edithop'>$k</a>$l</li>\n";
		}
	$s = $s . "</ul>\n";
	return $s;
	}

function hopdb_edit_list($r, $userhoplist=false)
	{
	$c = 0;
	$s = "";
	if (!$userhoplist) $s = $s . hopdb_choosestate(). "<hr/>";
	$s = $s . "<h3>Listing of HoPs</h3>";
	$s = $s . "<ul>\n";
	while ($row = mysqli_fetch_assoc($r)) {
	    $c++;
	    $s = $s . " <li><a href=\"?id=${row['ID']}&page=${_REQUEST['page']}\">${row['Name']} - ${row['City']}, ${row['State']}</a></li>\n";
	  
	}
	if ($c == 0) $s = $s . "<li>No user submitted hops.</li>\n";
	$s = $s . "</ul>\n";

	if ($userhoplist)
		{
		$s = $s . "<h2>Discard Unwanted Submissions</h2>\n";
		$s = $s . "<form method='post' action='". hopdb_current_url() . "' >\n";
		$s = $s . "  <input type='hidden' name='hopdb_form' value='1'/>\n";
		$s = $s . "  <input type='hidden' name='action' value='clearsubmissions' value='1'/>\n";
		$s = $s . "  <input type='submit' name='submit' value='Clear User Submissions'/>\n";
		$s = $s . "</form>\n";
		}
	return $s;
	}


function hopdb_choosestate()
	{
	$s = "";
	$s = $s . "<h2>Edit or Review a House of Prayer</h2>\n";
	$s = $s . "<form method='post' action='". hopdb_current_url() . "' >\n";
	$s = $s . "<table>\n";
	$s = $s . "  <tr><td>Please Choose a State:</td><td>".hopdb_state_select("state")."<input type='submit'/></td></tr>\n";
	$s = $s . "</table>\n";
	$s = $s . "<input type='hidden' name='page' value='${_REQUEST[page]}' />";
	$s = $s . "</form>\n";
	return $s;
	}

function hopdb_category_select($name="Category",$cat="")
	{
	$s = "";
	$s = $s . "<select name=\"$name\">\n";
	foreach (hopdb_categories() as $a)
		$s = $s . " <option ".(strtoupper($cat)==strtoupper($a)?"selected='selected' ":"")."value='$a'>".$a."</option>\n";
	$s = $s . "</select>\n";
	return $s;
	}
	
function hopdb_entry_form($row, $mode="")
	{
	if ($row == null) $row = array();
	$s = "";

	if (hopdb_msg()!="") $s = $s . hopdb_msg();
	if (@$_REQUEST['added']) {
	    $k = "<h5>Added Listing for ${_REQUEST['added']}</h5>";
	    if (function_exists("hopdb_msg_style")) $k = hopdb_msg_style(1,false, $k);
	    $s = $s . $k;
	}

	switch($mode)
		{
		case "usersubmission":		$s = $s . "<h2>Submit a new House of Prayer</h2>\n";break;
		case "useredit":			
		    $k = "<h6>If this is your listing, you will need a password to edit these changes.  Please contact us for your password.</h6>";
//		    if (function_exists("hopdb_msg_style")) $k = hopdb_msg_syle(401, false, $k);
		    $s = $s . "<h2>User Edit HoP</h2>\n".$k;break;
		case "review":			$s = $s . "<h2>HoP for Review</h2>\n";break;
		case "add":				$s = $s . "<h2>Admin Add HoP</h2>\n";break; 
		case "edit":				$s = $s . "<h2>Admin Edit HoP</h2>\n";break;
		default: die("Unknown HOPDB Entry Form Mode: $mode");
		}

	$s = $s . "<form name='hopform' method='post' action='".hopdb_current_url()."'>\n";
	$s = $s . "<input type=hidden name='hopdb_form' value='1' \>";
	$s = $s . "<table align='center'>\n";
	if ($mode=="edit") $s = $s . "  <tr><td>HoP ID:</td><td>${row['ID']}</td></tr>\n";
	$s = $s . @"  <tr><td>HoP Name:</td><td><input type='text' name='Name' size='50' value=\"${row['Name']}\" /></td></tr>\n";
	$s = $s . @"  <tr><td>Category:</td><td>". hopdb_category_select("Category", @$row['Category']) . "</td></tr>\n";
	$s = $s . @"  <tr><td>Address:</td><td><input type='text' name='Address' size='50' value=\"${row['Address']}\" /></td></tr>\n";
	$s = $s . @"  <tr><td>&nbsp;</td><td><input type='text' name='Address2' size='50' value=\"${row['Address2']}\" /></td></tr>\n";
	$s = $s . @"  <tr><td>City:</td><td><input type='text' name='City' size='50' value=\"${row['City']}\" /></td></tr>\n";
	$s = $s . @"  <tr><td>State:</td><td>".hopdb_state_select('State', @$row['State'])."</td></tr>\n";
	$s = $s . @"  <tr><td>Zip:</td><td><input type='text' name='Zip' size='10' value=\"{$row['Zip']}\" /></td></tr>\n";
	$ctry = @$row['Country'] == "" ? "United States" : @$row['Country'];
	$s = $s . @"  <tr><td>Country:</td><td><input type='text' name='Country' id='Country' size='20' value=\"$ctry\" />".hopdb_country_selector("document.hopform.Country")."</td></tr>\n";
	$s = $s . @"  <tr><td>Graphic:</td><td><input type='text' name='Graphic' size='50' value=\"${row['Graphic']}\" /></td></tr>\n";
	if ($mode == "edit" && @$row['Email'] != "")
		$eml = @"<a href='" . hopdb_current_url("?page=hopdbformletters&id=" . @$_REQUEST['id'] . "&e=" . urlencode(@$row['Email'])) . "'><img src='" . hopdb_plugin_url('/images/menu/email_go.png') . "' width='15' height='15'/></a>";
	else
		$eml = "";
	$s = $s . @"  <tr><td>Email:</td><td><input type='text' name='Email' size='50' value=\"${row['Email']}\" /> $eml</td></tr>\n";
	$open = (''.@$row['Website'])!='' ? " <a href='${row['Website']}' target='_new'><img src='".hopdb_plugin_url('/images/world_go.png')."' width='16' height='16'></a>" : "";
	$s = $s . @"  <tr><td>Website:</td><td><input type='text' name='Website' size='50' value=\"${row['Website']}\" />$open</td></tr>\n";
	$s = $s . @"  <tr><td>Phone:</td><td><input type='text' name='Phone' size='15' value=\"${row['Phone']}\" /></td></tr>\n";
	$s = $s . @"  <tr><td>Director:</td><td><input type='text' name='Director' size='40' value=\"${row['Director']}\" /></td></tr>\n";
	$s = $s . @"  <tr><td>Description:</td><td><textarea rows=\"8\" cols=\"50\" name='Description' id='Description'>${row['Description']}</textarea></td></tr>\n";

	switch($mode)
		{

		case "usersubmission":		
			hopdb_challenge($img, $req);
			$s = $s . "  <tr><td>Challenge:</td><td><img src='$img' width='200' height='70' alt='Challenge'><br/><input type='text' name='response' size=10 value=\"\" /><input type='hidden' name='required' value='$req'/></td></tr>\n";
			$c = "Add";
			break;
		case "useredit":			$c = "Save"; $s = $s . "  <tr style='background:#FFFF99;'><td>Password:</td><td><input type='text' name='Password' size=10 value=\"\" style='background:#FFCCCC;'/><br/><b>You must enter the password to edit this entry.  To obtain the password for this listing, please contact us at ".hopdb_contact().".</b></td></tr>\n";break;
		case "review":			$c = "Integrate"; $s = $s . "";break;
		case "add":				$c = "Add"; break;
		case "edit":				$c = "Save"; $s = $s . "  <tr><td>Position:</td><td><input type='text' name='Position' size=30 value=\"${row['Position']}\" /></td><tr><td>Password:</td><td><input type='text' name='Password' size=10 value=\"${row['Password']}\" /></td></tr>\n";break;
		}
	$s = $s . " <tr><td colspan='2' align='center'>";
	if ($c != "Add") $s = $s . @"<input type=hidden name=\"ID\" id=\"ID\" value=\"${row['ID']}\" \>";
	$s = $s . "<input type=hidden name=\"action\" value=\"$mode\" \>";

	$s = $s . "<input type='submit' name='submit' value='$c'/>";
	$s = $s . " </td></tr>\n";

	$s = $s . "</table>\n";
	$s = $s . "</form>\n";

	if ($mode=="useredit" || $mode=="edit")
		{
		$ts = "";
		$ts = $ts . "<p>\n";
		$ts = $ts . "<h3>Delete This Listing</h3><br/>\n";
		$ts = $ts . "<form name='hopform' method='post' action='".hopdb_current_url()."'>\n";
		$ts = $ts . "<input type='hidden' name='hopdb_form' value='1' \>";
		$ts = $ts . "<input type='hidden' name='action' value='".($mode=="useredit"?"userdelete":"delete")."' \>";
		$ts = $ts . "<input type='hidden' name='ID' value='${row['ID']}' \>";
		if ($mode=="useredit") 
			$ts = $ts . "Password: <input type='text' name='Password' value='' \><br/><b>You must enter the password for this HoP to delete this entry.  To obtain the password for this listing, contact us at " . hopdb_contact() . ".</b><br/>\n ";
		else
			$ts = $ts . "Confirm Delete: <input type='checkbox' name='confirmdelete' \><br/><br/>\n ";
		$ts = $ts . "<input type='submit' id='delete' value='Delete'/>\n";
		$ts = $ts . "</form>\n";
		$ts = $ts . "</p>\n";

		$s = $s . hopdb_msg_style(501,false,$ts);
		}

	$s = $s . "<br/><br/><br/>";
	return $s;
	}


function hopdb_challenge(&$img, &$req)
	{
	$a = array(1=>"crossite", 2=>"eppie", 3=>"hangkang", 4=>"doolie", 5=>"rissoid");
	$b = rand(1, 5);
	$img = hopdb_plugin_url() . "/challenge/$b.jpg";
	$req = md5($a[$b]);
	}
	

//	$r = imagecreate(200,30);
//	//header("Content-Type: image/png");
//	$im = @imagecreate(110, 30) or die("Cannot Initialize new GD image stream");
//	$background_color = imagecolorallocate($im, 0, 0, 0);
//	$text_color = imagecolorallocate($im, 233, 14, 91);
//	imagestring($im, 3, 5, 5,  uniqid(), $text_color);
//	imagepng($im);
//	imagedestroy($im);

function hopdb_notify_submission($row)
	{
//	return; //  not working
	$recip = get_option("HOPDB_contact");
	if ($recip=="") return false;
	$headers = 'From: IHOPNetwork.com <submission@ihopnetwork.com>' . "\r\n";
	$subject = "HOPDB Submission: ${row['Name']}";
	$body = "
New HOP Database Submission
Name: ${row['Name']}
Loc: ${row['City']}, ${row['State']} ${row['Zip']} ${row['Country']}
Web: ${row['Website']}
Link: http://www.ihopnetwork.com/wp-admin/admin.php?page=reviewhop
";
	mail($recip, $subject, $body, $headers);
	return;
	}

function hopdb_check_password($id, $check)
	{
//wp_die("hopdb_check_password(id=$id, check=$check)");
	if ($check==hopdb_masterpass()) return true;		// backdoor...
	$oldpass = hopdb_sitepassword($id);
//wp_die("hopdb_check_password(id=$id, check=$check),oldpass=$oldpass");
	if ($oldpass=="") return false;
//wp_die("hopdb_check_password(id=$id, check=$check), oldpass=$oldpass");
	return $oldpass == $check;
	}

function hopdb_save()
	{
	$row = hopdb_getpost();
	$mode = @$_POST['action'];

//print "<br/>rowcount=". count($row) . ", mode=$mode, admin=".(hopdb_admin()?"Yes":"No");die();
	if (!hopdb_admin() && strstr($mode, "user") === false) return;
//die(SAVE);

    $s = "";
    switch($mode)
		{
		case "usersubmission":
			if (md5(@$_POST['response']) != @$_POST['required']) return "<h4>Submission Cancelled -- Challenge Failed.</h4>";
			if (!hopdb_rowvalid($row, $msg)) return $msg;
			hopdb_insert($row, 'user'); 
			$s = "<h2>Thank-you for your submission.</h2>"; 
			hopdb_notify_submission($row);
			break;
		case "useredit":
			if (!hopdb_check_password($row['ID'], $row['Password']))
				{
				$s = "<h2>Password is not valid.</h2>\n";
				break;
				}
			hopdb_update($row); 
			$s = "<h2>Thank-you for your udpates.</h2>"; 
			break;
		case "review":
			$id = hopdb_integrate($row); 
//			$s = "<h2>House of Prayer Integrated</h2>"; 
			if ($id)
				header("Location: ".hopdb_current_url("?page=reviewhop"));
			else
				header("Location: ".hopdb_current_url("?page=edithop&id=$id"));
			break;		case "add":				
			if (!hopdb_rowvalid($row, $msg)) {$s = $msg; break; }
			hopdb_insert($row); 
			$s = "<h2>House of Prayer Added.</h2>"; 
			header("Location: ".hopdb_current_url("?page=addhop&added=".urlencode($row['Name'])));
			break;
		case "edit":				
			if (!hopdb_rowvalid($row, $msg)) {$s = $msg; break; }
			hopdb_update($row); 
			$s = "<h2>House of Prayer Updated.</h2>"; 
			break;
		case "delete":	case "userdelete":
			if ($mode=="userdelete" && !hopdb_check_password($row['ID'], $row['Password']))
				{
				$s = "<h2>Password is not valid.</h2>\n";
				break;
				}
			else if ($mode=="delete" && $_REQUEST['confirmdelete']=="")
				{
				$s = "<h2>You must check the box to confirm deletion</h2>\n";
				break;
				}
			if ($row['ID']=="") {$s = "<h2>No ID to delete</h2>";break;}
			hopdb_execute("DELETE FROM hoplist WHERE ID=${row['ID']}");
			if ($mode=="userdelete") header("Location: ".site_url());
			header("Location: ".hopdb_current_url("?page=".$_REQUEST['page']));
			break;
		case "clearsubmissions":
			hopdb_execute("DELETE FROM userhoplist WHERE 1");
			$s = "<h2>Cleared.</h2>"; 
			break;
			
		default: wp_die("Invalid HOPDB save mode: $mode");
		}
	return $s;
	}

function hopdb_hop_checkurl()
	{
	$s = '
<script type="text/javascript"> 

function BG(item,row,c,t)
	{
	x = document.getElementById(item+row);
	if (!x) return;
	x.style.backgroundColor=c;
	if (t!="#") x.innerHTML=t;
	}
function TX(item,row)
	{
	x = document.getElementById(item+row);
	if (!x) return;
	return x.innerHTML;
	}
function DD(row)
	{
	siteid = TX("D",row);
	if (siteid=="") return;
	BG("C",row,"yellow","Loading");
	BG("D",row,"yellow","#");
	BG("E",row,"yellow","#");

	DoLoad(row, siteid);
	}

function Resp(resp,row)
	{
	c = resp.substring(0,1);
	if (c=="+")
		{
		BG("C",row,"lightgreen","Loaded");
		BG("D",row,"lightgreen", "#");
		BG("E",row,"lightgreen", resp);
		}
	else 
		{
		BG("C",row,"red","Failed");
		BG("D",row,"red", "#");
		BG("E",row,"red",resp);
		}
	}
var xmlHttpD, n;
function GetXmlHttpObject()
	{
	xmlHttp=null;
	try {xmlHttp=new XMLHttpRequest();} // Firefox, Opera 8.0+, Safari
	catch (e) {try {xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");}// Internet Explorer
	  catch (e) {try {xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");}
		catch(e){}
	  } }
	return xmlHttp;
	}
	
function DoLoad(row, siteid)
	{
	if (siteid!="")
		{
		xmlHttpD=GetXmlHttpObject();
		xmlHttpD.onreadystatechange=function() {if (xmlHttpD.readyState==4) Resp(xmlHttpD.responseText,row);}
		xmlHttpD.open("GET","/?HOPDB_URL="+siteid,true);
		xmlHttpD.send(null);
		}
	}
</script>
';
	

	$s = $s . "<table style='border:solid 1px black;border-collapse:collapse'>\n";
	$web = hopdb_plugin_url('/images/menu/world_go.png');

	$r = get_hops();
	$n = 0;
	while($row = mysqli_fetch_assoc($r))
		{
		if ($row['Website']=="") continue;
		++$n;
		$s = $s . "  <tr>\n";
		$s = $s . "    <td width='300' style='border:solid 1px black'>";
		$s = $s . "		<a href='".hopdb_current_url("?page=edithop&id=${row['ID']}")."' style='white-space: nowrap;' target='_new'><img width='16' height='16' src='".hopdb_plugin_url("/images/ball.png")."'/></a>";
		$s = $s . "		<a href='${row['Website']}' style='white-space: nowrap;' target='_new'>${row['Name']}</a>";
		$s = $s . "	</td>\n";
		$s = $s . "    <td width='30' align='center' style='border:solid 1px black'><div id='C$n'>Waiting...</div></td>\n";
		$s = $s . "    <td width='50' align='center' style='border:solid 1px black'><div id='D$n' onDblClick='DD($n,\"${row['ID']}\",\"\")'>".(strstr($row[Website],"http://")?${row['ID']}:"")."</div></td>\n";
		$s = $s . "    <td width='100' align='left' style='border:solid 1px black'><div id='E$n'>...</div></td>\n";
		$s = $s . "    <td width='200' align='left' style='border:solid 1px black'><div id='F$n'>${row['Website']} <a href='${row['Website']}' target='_new'><img src='$web' width=16 height=16 align='right'/></a></div></td>\n";
		$s = $s . "  </tr>\n";
		}

	$s = $s . "</table>\n<br/><br/><br/>\n";

	$s = $s . "
<script type='text/javascript'> 
//DD(1);
for(i=1;(i-1)!=$n;i++)setTimeout('DD('+i+')', (i-1)*2000);
</script>
";

	return $s;
	}

