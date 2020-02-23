<?php
/*
Plugin Name: HOPDB
Plugin URI: http://www.IHOPNetwork.com/
Description: House of Prayer Database Management
Version: 2.0
Author: David Schadlich & Benjamin Hoogterp
Author URI: http://Jesusphreak.net/
License: None
History:
1.0 initial release
1.0.1 adds address2
1.1 add intergration support
2.0 Benjamin Hoogterp re-working
*/

/*  Copyright 2011 Benjamin Hoogterp (benjaminhoogterp@gmail.com)

    This is free.

*/

require(dirname(__FILE__) . "/hopdb_locales.php");						//  things like the state select box and country flags
require(dirname(__FILE__) . "/hopdb_db.php");							//  the main database access for our stuff

$hopdb_admin_init = false;
$hopdb_message = "";

function hopdb_admin()			{	return true; }
function hopdb_contact()			{	return get_option("HOPDB_contact");	}
function hopdb_adminname()			{	return get_option("HOPDB_admin");	}
function hopdb_masterpass()			{	add_option("HOPDB_master_password", "08221975"); return get_option("HOPDB_master_password");	}
//function hopdb_site_url()			{	$x = site_url();  if ($x == "http://www.ihopnetwork.com") $x = "http://www.IHOPNetwork.com";	}
function hopdb_msg($s=1)			{	global $hopdb_message; return $hopdb_message==""?"":hopdb_msg_style($s).$hopdb_message.hopdb_msg_style($s,1);	}
function hopdb_plugin_url($q="")		{	return WP_PLUGIN_URL."/".dirname(plugin_basename(__FILE__)).$q;	}
function hopdb_plugin_dir($q="")		{	return dirname(__FILE__).$q;	}
function hopdb_current_url($qs="qs")	//{	return "http://".$_SERVER[HTTP_HOST].$_SERVER[SCRIPT_NAME].($qs=="qs"&&$_SERVER[QUERY_STRING]!=""?"?".$_SERVER[QUERY_STRING]:($qs=="qs"?"":$qs));	}
	{
	$x = "http://".$_SERVER[HTTP_HOST].$_SERVER[SCRIPT_NAME];
	if ($qs == "qs")
		{
		$x = $_SERVER[REQUEST_URI];
		}
	else
		$x = $x . $qs;
	return $x;
	}
function hopdb_msg_style($style, $end=false, $str="")
	{
	if ($str!="") return hopdb_msg_style($style).$str.hopdb_msg_style($style, true);
	switch($style)
		{
		case 1:	return $end?"</div>":"<div style='display:inline-block;background-color:lightyellow;width:93%;margin:15px 15px 15px 15px;padding:5px 5px 5px 5px;border: solid 1px lightblue;border-radius:3px;'>";
		case 2:	return $end?"</div>":"<div style='display:inline-block;background-color:lightyellow;width:93%;margin:15px 15px 15px 15px;padding:5px 5px 5px 5px;border: solid 1px lightblue;border-radius:3px;'>";
		case 401:	return $end?"</div>":"<div style='display:inline-block;background-color:#CFFFCF;width:93%;margin:15px 15px 15px 15px;padding:5px 5px 5px 5px;border: solid 1px green;border-radius:3px;'>";
		case 501:	return $end?"</div>":"<div style='display:inline-block;background-color:#FFCFCF;width:93%;margin:15px 15px 15px 15px;padding:5px 5px 5px 5px;border: solid 1px red;border-radius:3px;'>";
		case 1001:	return $end?"</span></span>":"<span class='update-plugins count-$str' title='hopdb'><span class='update-count'>";
		case 1002:	return $end?"</span>":"<span id='ab-updates' class='update-count'>";
//<span class='update-plugins count-1' title='title'><span class='update-count'>1</span></span>
		default:	return "";
	}	}

function hopdb_feed_file($x="xml")		{	return hopdb_plugin_dir("/ihopnetwork.$x");	}
function hopdb_feed_url($x="xml")		{	return hopdb_plugin_url("/ihopnetwork.$x");	}
function hopdb_write_feeds()		{	include_once("hopdb_feed.php"); hopdb_do_write_feeds();	}


//  hook up
add_shortcode( 'hopdb-list', 'hopdb_shortcode_list_hook' );
add_shortcode( 'hopdb-find', 'hopdb_shortcode_find_hook' );
add_shortcode( 'hopdb-submit', 'hopdb_shortcode_submit_hook' );
add_shortcode( 'hopdb-useredit', 'hopdb_shortcode_useredit_hook' );
add_shortcode( 'hopdb-stats', 'hopdb_shortcode_stats_hook' );
add_shortcode( 'hopdb-contact', 'hopdb_contact_hook' );
add_action('admin_menu', 'hopdb_admin_menu');
add_action('admin_bar_menu', 'hopdb_admin_bar_menu', 40);
add_action('widgets_init', 'hopdb_widgets_init' );
add_filter( 'wp_title', 'hopdb_custom_title', 10, 2 );

add_action('init', 'hopdb_style');


//add_filter('query_vars', 'hopdb_add_query_vars');
//add_action('init', 'hopdb_do_rewrite');
//add_action('init', 'hopdb_rewrite');

//add_filter('wp_redirect ', 'hopdb_wp_redirect');

function hopdb_style()
	{
	wp_enqueue_style('hopdb_css', hopdb_plugin_url('/style.css'));
	}


function hopdb_admin_menu()
	{
	global $hopdb_admin_init, $hopdb_message;
	$hopdb_admin_init = true;

	$s = hopdb_msg_style(1001,false,get_hop_count('','user'));
	add_menu_page('HOP DB Options', 'HOP DB'.$s, 8, 'HOPDBmain', 'hopdb_main_page', hopdb_plugin_url('/images/menu/package.png'));
	add_submenu_page('HOPDBmain', 'Add House of Prayer', 'Add HOP', 8, 'addhop', 'hopdb_hop_add_page');
	add_submenu_page('HOPDBmain', 'Edit House of Prayer', 'Edit HOP', 8, 'edithop', 'hopdb_hop_edit_page');
	add_submenu_page('HOPDBmain', 'Review Submited HOPs', 'Review Submited', 8, 'reviewhop', 'hopdb_hop_review_page');
	add_submenu_page('HOPDBmain', 'List All HOPs', 'List All', 8, 'listhops', 'hopdb_hop_list_page');
	add_submenu_page('HOPDBmain', 'Import HOP List', 'Import', 8, 'hopdbimport', 'hopdb_import_page');
	add_submenu_page('HOPDBmain', 'Integrity Check', 'Validate DB Entries', 8, 'hopdb_integrity', 'hopdb_integrity_page');
	add_submenu_page('HOPDBmain', 'Check URLs', 'Validate Site URLs', 8, 'checkhops', 'hopdb_hop_checkurl_page');
	add_submenu_page('HOPDBmain', 'HOPDB Feeds', 'Feeds', 8, 'hopdbfeedlist', 'hopdb_hop_feed_page');
	add_submenu_page('HOPDBmain', 'Form Emails', 'Form Emails', 8, 'hopdbformletters', 'hopdb_formletters_page');

	add_filter('ozh_adminmenu_icon_HOPDBmain', 'hopdb_adminmenu_customicons');
	add_filter('ozh_adminmenu_icon_addhop', 'hopdb_adminmenu_customicons');
	add_filter('ozh_adminmenu_icon_edithop', 'hopdb_adminmenu_customicons');
	add_filter('ozh_adminmenu_icon_reviewhop', 'hopdb_adminmenu_customicons');
	add_filter('ozh_adminmenu_icon_listhops', 'hopdb_adminmenu_customicons');
	add_filter('ozh_adminmenu_icon_hopdbimport', 'hopdb_adminmenu_customicons');
	add_filter('ozh_adminmenu_icon_hopdb_integrity', 'hopdb_adminmenu_customicons');
	add_filter('ozh_adminmenu_icon_checkhops', 'hopdb_adminmenu_customicons');
	add_filter('ozh_adminmenu_icon_hopdbfeedlist', 'hopdb_adminmenu_customicons');
	add_filter('ozh_adminmenu_icon_hopdbformletters', 'hopdb_adminmenu_customicons');
	}

function hopdb_list_menu($atts, $content = null) {
	extract(shortcode_atts(array(  
		'menu'            => '', 
		'container'       => 'div', 
		'container_class' => '', 
		'container_id'    => '', 
		'menu_class'      => 'menu', 
		'menu_id'         => '',
		'echo'            => true,
		'fallback_cb'     => 'wp_page_menu',
		'before'          => '',
		'after'           => '',
		'link_before'     => '',
		'link_after'      => '',
		'depth'           => 0,
		'walker'          => '',
		'theme_location'  => ''), 
		$atts));

	return wp_nav_menu( array( 
		'menu'            => $menu, 
		'container'       => $container, 
		'container_class' => $container_class, 
		'container_id'    => $container_id, 
		'menu_class'      => $menu_class, 
		'menu_id'         => $menu_id,
		'echo'            => false,
		'fallback_cb'     => $fallback_cb,
		'before'          => $before,
		'after'           => $after,
		'link_before'     => $link_before,
		'link_after'      => $link_after,
		'depth'           => $depth,
		'walker'          => $walker,
		'theme_location'  => $theme_location));
}
//Create the shortcode
add_shortcode("listmenu", "hopdb_list_menu");

function hopdb_adminmenu_customicons($in) 
	{
	switch($in)
		{
		case "HOPDBmain":		return hopdb_plugin_url('/images/menu/wrench_orange.png');
		case "addhop":		return hopdb_plugin_url('/images/menu/add.png');
		case "edithop":		return hopdb_plugin_url('/images/menu/edit.png');
		case "reviewhop":		return hopdb_plugin_url('/images/menu/zoom.png');
		case "listhops":		return hopdb_plugin_url('/images/menu/picture_empty.png');
		case "hopdbimport":		return hopdb_plugin_url('/images/menu/pencil_add.png');
		case "hopdb_integrity":	return hopdb_plugin_url('/images/menu/application_error.png');
		case "checkhops":		return hopdb_plugin_url('/images/menu/lightning.png');
		case "hopdbfeedlist":	return hopdb_plugin_url('/images/menu/feed.png');
		case "hopdbformletters":	return hopdb_plugin_url('/images/menu/email_go.png');
		default:			return "";
		}
	}

function hopdb_admin_bar_menu()
	{
	global $wp_admin_bar;
	if ( !is_super_admin() || !is_admin_bar_showing() ) return;


	$s = hopdb_msg_style(1002,false,get_hop_count('','user'));
	$wp_admin_bar->add_menu( array(	'id' => 'hopdb_menu', 'title' => "HOPDB $s", 'href' => false ) );
	$wp_admin_bar->add_menu( array(	'parent' => 'hopdb_menu',	'title' => 'Add HOP',		'href' => site_url()."/wp-admin/admin.php?page=addhop") );
	$wp_admin_bar->add_menu( array(	'parent' => 'hopdb_menu',	'title' => 'Edit HOP',		'href' => site_url()."/wp-admin/admin.php?page=edithop" ) );	
	$wp_admin_bar->add_menu( array(	'parent' => 'hopdb_menu',	'title' => 'Review User HOPs',	'href' => site_url()."/wp-admin/admin.php?page=reviewhop" ) );	
	$wp_admin_bar->add_menu( array(	'parent' => 'hopdb_menu',	'title' => 'List HOPs',		'href' => site_url()."/wp-admin/admin.php?page=listhops" ) );	
	$wp_admin_bar->add_menu( array(	'parent' => 'hopdb_menu',   'title' => 'Feeds',			'href' => false, 'id' => 'hopdb_feeds'  ) );
	$wp_admin_bar->add_menu( array(	'parent' => 'hopdb_feeds',	'title' => 'Basic XML',		'href' => hopdb_feed_url("xml") ) );	
	$wp_admin_bar->add_menu( array(	'parent' => 'hopdb_feeds',	'title' => 'Google Earth KML',	'href' => hopdb_feed_url("kml") ) );	
	}

function hopdb_custom_title($title, $sep="", $seplocation="")
	{
	if (($s = $_REQUEST["state"]) == '') return $title;
	if ($s == 'xx') return "$title $sep Houses of Prayer Worldwide";
	$x = strtoupper($s) . " $sep "  . hopdb_state_name($s) . " Houses of Prayer";
	return bloginfo('name') . " $sep $x";
	}

function hopdb_add_query_vars($public_query_vars)
	{
	$public_query_vars[] = 'state';
	return $public_query_vars;
	}

function hopdb_do_rewrite()
	{
//	add_rewrite_tag('%state%','([^&]+)');
//	add_rewrite_rule('^state/([^/]*)/?$', 'index.php/?pagename=find&state=$matches[1]', 'top');
//	add_rewrite_rule('index.php/state/([a-zA-Z][a-zA-Z])/?$', 'index.php/?pagename=find&state=$matches[1]', 'top');
//	add_rewrite_rule('index.php/state/([a-zA-Z][a-zA-Z])/?$', 'index.php?pagename=find&state=$matches[1]', 'top');
//	add_rewrite_endpoint('state', EP_PERMALINK | EP_PAGES);
//	flush_rewrite_rules(true);
	}

function hopdb_wp_redirect($location, $status)
	{
//print "<br/>Loc=".$location;
	return $location;
	}

function hopdb_main_page        ()			{	include("hopdb_options.php");			}

function hopdb_hop_add_page     ()			{	include_once("hopdb_edit.php"); print hopdb_hop_add();	}
function hopdb_hop_edit_page    ()			{      include_once("hopdb_edit.php"); print hopdb_hop_edit();	}
function hopdb_hop_review_page  ()			{      include_once("hopdb_edit.php"); print hopdb_hop_review();	}
function hopdb_hop_list_page    ()			{      include_once("hopdb_edit.php"); print hopdb_hop_list();	}
function hopdb_import_page      ()			{	include_once("hopdb_import.php"); print hopdb_import();	}
function hopdb_integrity_page   ()			{	include_once("hopdb_integrity.php"); print hopdb_integrity();	}
function hopdb_hop_checkurl_page()			{	include_once("hopdb_edit.php"); print hopdb_hop_checkurl();	}
function hopdb_hop_feed_page    ()			{	include_once("hopdb_feed.php"); print hopdb_feeds();	}
function hopdb_formletters_page ()			{	include_once("hopdb_email.php"); print hopdb_formletters();	}

function hopdb_shortcode_list_hook($atts)		{	include_once("hopdb_find.php"); return hopdb_shortcode_list($atts);	}
function hopdb_shortcode_find_hook($atts)		{	include_once("hopdb_find.php"); return hopdb_shortcode_find($atts);	}
function hopdb_shortcode_submit_hook($atts)	{	include_once("hopdb_edit.php"); return hopdb_shortcode_submit($atts);	}
function hopdb_shortcode_useredit_hook($atts)	{	include_once("hopdb_edit.php"); return hopdb_shortcode_useredit($atts);	}
function hopdb_shortcode_stats_hook($atts)	{	include_once("hopdb_stat.php"); return hopdb_shortcode_stat($atts);	}
function hopdb_contact_hook($atts)			{	include_once("hopdb_contactlist.php"); return hopdb_shortcode_contact($atts);	}

function hopdb_widgets_init()			{	include_once("hodpb_class_hopdb_status_widget.php"); register_widget( 'hopdb_status_widget' );	}

if ($_POST[hopdb_form]!="") 	{global $hopdb_message; include_once("hopdb_edit.php"); $hopdb_message = hopdb_save();	}
if ($_REQUEST['HOPDB_KML']!='')	{include_once("hopdb_feed.php");header ("Content-Type:text/xml");print hopdb_kml();die();	}
if ($_REQUEST['HOPDB_XML']!='')	{include_once("hopdb_feed.php");header ("Content-Type:text/xml");print hopdb_xml();die();	}
if ($_REQUEST['HOPDB_URL']!='')	{include_once("hopdb_import.php");hopdb_check_url_by_id($_REQUEST['HOPDB_URL']);	}
if ($_REQUEST['HOPDB_EMAIL']!='')	{global $hopdb_message; include_once("hopdb_email.php"); $hopdb_message=hopdb_email($_REQUEST[hopid], $_REQUEST[letter], $_REQUEST[email])?"Message Sent.":"Message Failed."; }
if ($_REQUEST['HOPDB_AMAIL']!='')	{global $hopdb_message; include_once("hopdb_email.php"); $hopdb_message=hopdb_automailer(); }


	
?>