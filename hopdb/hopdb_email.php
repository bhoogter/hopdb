<?php
function hopdb_letter_names()			{	return array_keys(hopdb_form_letter_base());	}
function hopdb_letter_select($n,$v='N*NE')	{	return str_replace("<option>$v","<option selected='selected'>$v","<select name='$n'><option>".implode("</option><option>",hopdb_letter_names())."</option></select>\n");	}

function hopdb_form_letter_base($name='')
	{
	$x = array(
		"User Password Request" =>
<<<EOC
Hello.

Thank-you for your interest in #site for #hopname.

Using your password, you can edit your listing for your HoP at any time, as often as you like.
Changes show up immediately, so you can review your changes as soon as you submit them, and see how they look.

The password for your listing is: #password

You can find the edit link on the bottom right corner of your listing, or click on this link.
#editlink

Thank-you again for your interest.  If you experience any problems or have any other suggestions, 
feel free to contact me at #email.

His.

#admin
#site
EOC
,
		"Submission Integration" =>
<<<EOC
Hello.

Thank-you for your submission of #hopname.  Your information has been activated, and you can view your listing now on the #site website.

In addition, if you look, you'll notice a small link in the bottom right corner of your listing which will take you to an edit page.  
Using a password which we provide, you can edit your details at any time.  The password for your listing is:  #password

You can find the edit link on the bottom right corner of your listing, or click on this link.
#editlink

Thank-you again for your submission.  If you experience any problems or have any other suggestions, 
feel free to contact me at #email.

His.

#admin
#site
EOC
,
		"New Listing Discovered" =>
<<<EOC
Hello.

My name is #admin from the #site website.  We are attempting to keep a somewhat up-to-date listing of houses of prayer and other
prayer-related groups through out the US and the world.  We recently discovered your organziation and thought to list it.

You can view your site online by clicking on your area from our home page.  In addition, you can edit your listing
any time you want using a password that is generated automatically for your site.  The password for your site is:  #password

If you have any questions or comments, or other feedback, feel free to contact me at #email.

His.

#admin
#site
EOC
,
		"Information Update" =>
<<<EOC
Hello.

My name is #admin from the #site website.

This email is a friendly reminder that your House of Prayer, #hopname, is listed on our website.

Our current listing for your site is as follows:
--------------------------------------------------------
#listing
--------------------------------------------------------

You can view your listing here:  #locate

Using the password provided, you can edit your listing at any time, as often as you would like, or, should you need to, delete it.
The changes will appear immediately, and you can keep people informed this way of important things like address changes or a new website address.
Simply click the edit link on the lower right of your listing for the entry form, or go to #editlink.

The password for your listing is:  #password

Please visit our site to find or connect with other houses of prayer in your area and beyond, as well as to tell others about #site and let them know
about it as well.  If you feel it is appropriate, you could also list us in a "Links" section on your website, and help people
connect with houses of prayer wherever they are at.

Additionally, feel free to check out the "Blog" section of our website, containing thoughts on prayer, prayer houses, and things of interest to the prayer 
and prophetic community.

Thanks, and be sure to come back and visit our page again, as well as clicking "Like" on the Facebook link in the top right, as this boosts the visibility
of our site and helps others to find out about the prayer network when they see it on your News Feed.

His.

#admin
#contact
#site
EOC
		);

	if ($name=='') return $x;
	return $x[$name];
	}

function hopdb_get_letter($id, $subject)
	{
//wp_die("hopdb_get_letter($id, $subject)");
	$row = get_hop_row($id);

	$letter = hopdb_form_letter_base($subject);
//wp_die($letter);
	if ($letter == "") return "";

	$editlink = site_url()."/index.php/home/edit?id=$id";

	$site_listing = $row[Name]."\nAddress: ".$row[Address]."\nCity: ".$row[City].", ".$row[State].' '.$row[Country]."\nDirector: ".$row[Director]."\nEmail: ".$row[Email]."\nPhone: ".$row[Phone]."\nWebsite: ".$row[Website]."\n\n".$row[Description]."\n";
	$locate = site_url()."?state=".$row[State]."#L".$row[ID];

	$letter = str_replace(
array( '#hopname',    '#password',     '#editlink',   '#email',         '#site',     '#admin',          '#listing',   '#locate',   '#contact'),
array( $row[Name],    $row[Password],  $editlink,     hopdb_contact(),  site_url(),  hopdb_adminname(), $site_listing, $locate,    hopdb_contact()),
$letter
);

	return $letter;
	}

function hopdb_formletters()
	{
	$s = "";

	$s .= hopdb_msg();
	
	$s .= "<p>\n";

	$s .= "<form name='HOP_EMAIL_FORM' method='GET' action='#'>\n";
	$s .= "<input type='hidden' name='page' value='hopdbformletters' />\n";
	$s .= "<input type='hidden' name='HOPDB_EMAIL' value='1' />\n";

	$s .= "<table>\n";
	$s .= " <tr><td>Letter:</td><td>".hopdb_letter_select("letter")."</td></tr>\n";
	$s .= " <tr><td>HOP ID:</td><td><input name='hopid' size='10' value='" . $_REQUEST[id] . "'/></td></tr>\n";
	$s .= " <tr><td>To Addr:</td><td><input name='email' size='50'  value='" . urldecode($_REQUEST[e]) . "'/></td></tr>\n";
	$s .= "</table>\n";

	$s .= "<input type='submit' name='submit' class='button-primary' value='Submit' />\n";
	$s .= "</form>\n";

	$s .= "<br/>\n";
	$s .= "<hr/>\n";
	$s .= "<br/>\n";



	$s .= "<form name='HOP_AUTOMAILER_FORM' method='GET' action='#'>\n";
	$s .= "<input type='hidden' name='page' value='hopdbformletters' />\n";
	$s .= "<input type='hidden' name='HOPDB_AMAIL' value='1' />\n";

	$s .= "<table>\n";
//	$s .= " <tr><td>Letter:</td><td>".hopdb_letter_select("letter")."</td></tr>\n";
	$s .= " <tr><td>HOP ID:</td><td><input name='amail_min' size='10' value='0'/></td></tr>\n";
	$s .= " <tr><td>To Addr:</td><td><input name='amail_max' size='10'  value='2000'/></td></tr>\n";
	$s .= "</table>\n";

	$s .= "<input type='submit' name='submit' class='button-primary' value='Auto-Mailer' />\n";
	$s .= "</form>\n";

	$s .= "</p>\n";

	return $s;
	}

function hopdb_email($id, $subject, $to='')
	{
	if (!($message = hopdb_get_letter($id, $subject))) return false;
	$headers = "From: ".hopdb_contact();

	$subject = $subject . " at " . site_url();

	if (function_exists("wp_mail"))
		return wp_mail( $to, $subject, $message, $headers );
	else
		return mail( $to, $subject, $message, $headers );
	}

function hopdb_automailer_toid($id, $subject="Information Update")
	{
	if (!($row = get_hop_row($id))) return;
	if (!($em = $row[Email])) return;
//$em = "benjaminhoogterp@gmail.com";
	hopdb_email($id, $subject, $em);
	return true;
	}
function hopdb_automailer()
	{
	$n = 0;

	$a = 1;
	$b = 2000;

	if ($_GET[amail_min]>1) $a = $_GET[amail_min];
	if ($_GET[amail_max]>0) $b = $_GET[amail_max];


	for($i=$a;$i<=$b;$i++)
//	for($i=1;$i<200;$i++)
		{
		if (hopdb_automailer_toid($i)) $n++;
		}
	return "$n message(s) sent.";
	}

?>