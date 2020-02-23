<?php
include_once("hopdb_db.php");
include_once("hopdb_class_xml_file.php");

function hopdb_shortcode_contact($atts)
	{
	return hopdb_contactlist();
	}

function hopdb_contactlist_options()
	{
	return array(
		'name' => true,
		'director' 	=> $_REQUEST[hdbcld] != '',
		'email' 	=> $_REQUEST[hdbcle] != '',
		'phone' 	=> $_REQUEST[hdbclp] != '',
		'url' 		=> $_REQUEST[hdbclu] != '',
		'address' 	=> $_REQUEST[hdbcla] != '',
		'city' 	=> $_REQUEST[hdbclc] != '',
		'state' 	=> $_REQUEST[hdbcls] != '',
		'zip' 		=> $_REQUEST[hdbclz] != '',
		'category' 	=> $_REQUEST[hdbclt] != '',
		);
	}

function hopdb_contactlist_xsl($options)
	{
	$s  = "";
	$s .= "<?xml version='1.0' encoding='ISO-8859-1'?>\n";
	$s .= "\n";
	$s .= "<xsl:stylesheet version='1.0' xmlns:xsl='http://www.w3.org/1999/XSL/Transform'>\n";
	$s .= "\n";
	$s .= "<xsl:template match='/'>\n";
	$s .= "\n";
	$s .= "	<h2>Michigan Houses of Prayer Contacts</h2>\n";
	$s .= "\n";
	$s .= "	<table border='1'>\n";
	$s .= "      <tr bgcolor='#9acd32'>\n";
	if ($options['name'])     $s .= "        <th style='text-align:left'>Name</th>\n";
	if ($options['director']) $s .= "        <th style='text-align:left'>Director</th>\n";
	if ($options['email'])    $s .= "        <th style='text-align:left'>Email</th>\n";
	if ($options['phone'])    $s .= "        <th style='text-align:left'>Phone</th>\n";
	if ($options['url'])      $s .= "        <th style='text-align:left'>Url</th>\n";
	if ($options['address'])  $s .= "        <th style='text-align:left'>Address</th>\n";
	if ($options['city'])     $s .= "        <th style='text-align:left'>City</th>\n";
	if ($options['state'])    $s .= "        <th style='text-align:left'>State</th>\n";
	if ($options['zip'])      $s .= "        <th style='text-align:left'>Zip</th>\n";
	if ($options['category']) $s .= "        <th style='text-align:left'>Category</th>\n";
	$s .= "      </tr>\n";
	$s .= "      <xsl:for-each select='*/hop[state=\"MI\"]'>\n";
	$s .= "      <tr>\n";
	if ($options['name'])     $s .= "        <td><xsl:value-of select='name'/></td>\n";
	if ($options['director']) $s .= "        <td><xsl:value-of select='director'/></td>\n";
	if ($options['email'])    $s .= "        <td><xsl:value-of select='email'/></td>\n";
	if ($options['phone'])    $s .= "        <td><xsl:value-of select='phone'/></td>\n";
	if ($options['url'])      $s .= "        <td><xsl:value-of select='website'/></td>\n";
	if ($options['address'])  $s .= "        <td><xsl:value-of select='address'/> <xsl:value-of select='address2'/></td>\n";
	if ($options['city'])     $s .= "        <td><xsl:value-of select='city'/></td>\n";
	if ($options['state'])    $s .= "        <td><xsl:value-of select='state'/></td>\n";
	if ($options['zip'])      $s .= "        <td><xsl:value-of select='zip'/></td>\n";
	if ($options['category']) $s .= "        <td><xsl:value-of select='category'/></td>\n";
	$s .= "      </tr>\n";
	$s .= "      </xsl:for-each>\n";
	$s .= "    </table>\n";
	$s .= "  </div>\n";
	$s .= "\n";
	$s .= "  </xsl:template>\n";
	$s .= "  </xsl:stylesheet>\n";
	return $s;
	}


function hopdb_contactlist()
	{
	$list = new xml_file();
	$list->load(hopdb_feed_file("xml"));
	$list->transformXSL(hopdb_contactlist_xsl(hopdb_contactlist_options()));
	return $list->saveXML();
	}
?>
