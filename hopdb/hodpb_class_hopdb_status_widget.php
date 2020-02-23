<?php

class hopdb_status_widget extends WP_Widget 
	{
	function hopdb_status_widget() 
		{
		$widget_ops = array( 
			'classname' => 'hopdb_status', 
			'description' => 'Shows statistics for the HOP database.' 
			);

		$control_ops = array( 
			'width' => 300, 
			'height' => 350, 
			'id_base' => 'hopdb-status-widget' );

		$this->WP_Widget( 'hopdb-status-widget', 'HOP DB Status', $widget_ops, $control_ops );
		}

	function widget( $args, $instance )
		{
		extract($args);

		$w = "";

///////   Standard widget leadin
		$w = $w . $before_widget;
//		echo $before_widget;
		if ($title == '') $title = 'HoP Stats';
		if ($title) $w = $w . $before_title . $title . $after_title;
///////

		$r = hopdb_query("SELECT * FROM hoplist");
		$total_count = hopdb_record_count($r);
		hopdb_free_result($r);

		$r = hopdb_query("SELECT Country, COUNT(ID) AS Cnt FROM hoplist GROUP BY Country");
		while($row = hopdb_fetch_assoc($r))
			{
//print "<br/>$row[Country]=$row[Cnt]\n";
			if ($row[Country]=="United States") {$total_usa = $row[Cnt];continue;}
			if ($row[Country]=="Canada") {$total_canada = $row[Cnt];continue;}
			$total_other = $total_other + $row[Cnt];
			}
		hopdb_free_result($r);

		$most = 0; $mostnm = "";
		$least = 100000; $leastnm = "";
		$r = hopdb_query("SELECT State, COUNT(ID) AS Cnt FROM hoplist GROUP BY State");
		while($row = hopdb_fetch_assoc($r))
			{
//print "<br/>$row[State]=$row[Cnt]\n";
			if ($row[State] == '') continue;
			if ($row[Cnt] > $most) {$most = $row[Cnt]; $mostnm = $row[State];}
			if ($row[Cnt] < $least) {$least = $row[Cnt]; $leastnm = $row[State];}
			}
		hopdb_free_result($r);

		$title = apply_filters('widget_title', $instance['title'] );
		$sCnt =  isset( $instance['countries'] ) ? true : false;
		$sLMS =  isset( $instance['leastmost'] ) ? true : false;


///////   Our Widget Display code
		$w = $w . "<b>Total HoPs:</b> $total_count<br/>\n";
		if (sCnt)
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

		$w = $w . "<p align='center'><span style='font-size:8px;text-align:center;'><a href='http://www.ihopnetwork.com/index.php/home/stats-by-state/'>&lt;&lt;&lt;  More Stats  &gt;&gt;&gt;</a></span></p>";
///////

///////   Standard widget trailer
		$w = $w . $after_widget;
///////

		print $w;					//  output the widget
		}

	function update( $new_instance, $old_instance )
		{
		$instance = $old_instance;

		/* Strip tags (if needed) and update the widget settings. */
		$instance['countries'] = strip_tags( $new_instance['countries'] );
		$instance['leastmost'] = strip_tags( $new_instance['leastmost'] );
		return $instance;
		}
	
	function form()
		{
/* Set up some default widget settings. */
		$defaults = array( 'title' => 'Example', 'name' => 'John Doe', 'sex' => 'male', 'show_sex' => true );
		$instance = wp_parse_args( (array) $instance, $defaults );
?>

		<p>
			<label for="<?php echo $this->get_field_id( 'countries' ); ?>">Show Country Breakdown:</label>
			<input type='checkbox' id="<?php echo $this->get_field_id( 'countries' ); ?>" name="<?php echo $this->get_field_name( 'countries' ); ?>" value="<?php echo $instance['countries']; ?>" style="width:100%;" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'leastmost' ); ?>">Show Least/Most State:</label>
			<input type='checkbox' id="<?php echo $this->get_field_id( 'leastmost' ); ?>" name="<?php echo $this->get_field_name( 'leastmost' ); ?>" value="<?php echo $instance['leastmost']; ?>" style="width:100%;" />
		</p>
<?php
		}
	}

?>