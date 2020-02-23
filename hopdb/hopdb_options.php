<?php 
	function hopdb_init_options($opts, $defs, $operation='')
		{
		for ($i = 0; $i < count($opts); $i++)
			{
			if ($operation == "delete") {delete_option($opts[$i]);continue;}
			add_option($opts[$i], $defs[$i]);
			if ($operation == "saving") update_option($opts[$i], $_POST[$opts[$i]]);
			}

//		if ($operation != "") header( "Location: " . $_SERVER['REQUEST_URI'] ) ;  // redirect w/o the post variables, once saved
		}


hopdb_init_options(
	array("HOPDB_user","HOPDB_pass","HOPDB_host","HOPDB_name","HOPDB_contact","HOPDB_admin","HOPDB_imgdir","HOPDB_master_password"), 
	array("wpihop","wpihop","localhost","wpihop_data","","","/links/","08221975"), 
	$_POST["submit"] != '' ? "saving" : $_POST["delete"]);

?>

<div class="wrap">
<?php    echo "<h2>" . __( 'HOP DB Options' ) . "</h2>"; ?>

	<p>
	These are the settings for you HOP Database for connecting.
	</p>

<form name="option_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">

	<?php    echo "<h4>" . __( 'HOP DB Options' ) . "</h4>"; ?>
	<p>
	<table>
		<tr><td><?php _e("User: " ); ?></td><td><input type="text" name="HOPDB_user" size='20' value="<?php echo get_option('HOPDB_user'); ?>" size="20"></td></tr>
		<tr><td><?php _e("Pass: " ); ?></td><td><input type="text" name="HOPDB_pass" size='20' value="<?php echo get_option('HOPDB_pass'); ?>" size="20"></td></tr>
		<tr><td><?php _e("Host: " ); ?></td><td><input type="text" name="HOPDB_host" size='30' value="<?php echo get_option('HOPDB_host'); ?>" size="20"></td></tr>
		<tr><td><?php _e("Name: " ); ?></td><td><input type="text" name="HOPDB_name" size='20' value="<?php echo get_option('HOPDB_name'); ?>" size="20"></td></tr>
		<tr><td colspan='2'><hr/></td></tr>
		<tr><td><?php _e("Master Password: " ); ?></td><td><input type="text" name="HOPDB_master_password" size='20' value="<?php echo get_option('HOPDB_master_password'); ?>" size="20"></td></tr>
		<tr><td><?php _e("Contact Email: " ); ?></td><td><input type="text" name="HOPDB_contact" size='50' value="<?php echo get_option('HOPDB_contact'); ?>" size="20"></td></tr>
		<tr><td><?php _e("Contact Name: " ); ?></td><td><input type="text" name="HOPDB_admin" size='50' value="<?php echo get_option('HOPDB_admin'); ?>" size="20"></td></tr>
		<tr><td><?php _e("Default Img Dir: " ); ?></td><td><input type="text" name="HOPDB_imgdir" size='50' value="<?php echo get_option('HOPDB_imgdir'); ?>" size="20"></td></tr>
		</tr>
	</table>
	</p>
	

	<p class="submit">
	<input type="submit" name="submit" value="Save" class='button-primary' />
	</p>
</form>

<?php print hopdb_msg_style(501); ?>

	<p>
		Use this option to clear all the settings from your WP database (such as, if you want to delete this plugin entirely).
	</p>
<form name="option_form" method="post" action="<?php echo str_replace( '%7E', '~', $_SERVER['REQUEST_URI']); ?>">
	<input type='hidden' name='delete' value='delete'/>
	<p class="submit">
		<input type="submit" name="submit" value="<?php _e('Delete Settings' ) ?>" />
	</p>

</form>
<?php print hopdb_msg_style(501, true); ?>

</div>