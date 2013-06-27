<?php
/*
Plugin Name: Easy WP Feed Widget
Plugin URI: http://wordpress.org/extend/plugins/easy-wp-feed-widget/
Description: Wordpress widget to show a Wordpress feed.
Author: Jonas Hjalmarsson, Hultsfreds kommun
Version: 0.9.5
Author URI: http://www.hultsfred.se
*/

/*  Copyright 2013 Jonas Hjalmarsson (email: jonas.hjalmarsson@hultsfred.se)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 2 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
*/

/* 
 * WORDPRESS FEED WIDGET 
 */ 
 class hk_wp_feed_rss_widget extends WP_Widget {
	protected $vars = array();

	public function __construct() {
		parent::__construct(
	 		'hk_wp_feed_rss_widget', // Base ID
			'Easy WP Feed', // Name
			array( 'description' => "Widget showing feed from Wordpress feed url." ) // Args
		);
	}

 	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {	$title = $instance[ 'title' ];
		} else { $title = ""; }
		if ( isset( $instance[ 'show_wp_feed' ] ) ) {	$show_wp_feed = $instance[ 'show_wp_feed' ];
		} else { $show_wp_feed = ""; }
		if ( isset( $instance[ 'enable_cron' ] ) ) {	$enable_cron = $instance[ 'enable_cron' ];
		} else { $enable_cron = ""; }
		$options = get_option('hk_wp_feed_widget_' . $this->id);
		$hk_wp_feed_rss = strip_tags( $options['hk_wp_feed_rss'] );
		$hk_wp_feed_days_new = strip_tags( $options['hk_wp_feed_days_new'] );
		$hk_wp_feed_num = strip_tags( $options['hk_wp_feed_num'] );

		
		?>
		<p>
		<label for="<?php echo $this->get_field_id( 'title' ); ?>">Widget title</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'show_wp_feed' ); ?>">Show only in category (in format 23,42,19)</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'show_wp_feed' ); ?>" name="<?php echo $this->get_field_name( 'show_wp_feed' ); ?>" type="text" value="<?php echo esc_attr( $show_wp_feed); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'hk_wp_feed_rss' ); ?>">URL to feed.</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'hk_wp_feed_rss' ); ?>" name="<?php echo $this->get_field_name( 'hk_wp_feed_rss' ); ?>" type="text" value="<?php echo esc_attr( $hk_wp_feed_rss); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'hk_wp_feed_num' ); ?>">Number items to show from feed.</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'hk_wp_feed_num' ); ?>" name="<?php echo $this->get_field_name( 'hk_wp_feed_num' ); ?>" type="text" value="<?php echo esc_attr( $hk_wp_feed_num); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'hk_wp_feed_days_new' ); ?>">Number of days an item is new.</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'hk_wp_feed_days_new' ); ?>" name="<?php echo $this->get_field_name( 'hk_wp_feed_days_new' ); ?>" type="text" value="<?php echo esc_attr( $hk_wp_feed_days_new); ?>" />
		</p>
		<p>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'enable_cron' ); ?>" name="<?php echo $this->get_field_name( 'enable_cron' ); ?>" value="1"<?php checked( 1 == $enable_cron ); ?> /> 
		<label for="<?php echo $this->get_field_id( 'enable_cron' ); ?>">Use cron to generate cache every quarter</label>
		</p>
		<?php
		if ($enable_cron) {
			if ($options['hk_wp_feed_rss'] != "") {
				if ( !wp_next_scheduled( 'hk_wp_feed_event' ) ) {
					wp_schedule_event( time(), 'hk_wp_feed_schedule', 'hk_wp_feed_event');
				}
			}
			else
			{
				if ( wp_next_scheduled( 'hk_wp_feed_event' ) ) {
					wp_clear_scheduled_hook('hk_wp_feed_event');
				}
			}
		}
		else {
			if ( wp_next_scheduled( 'hk_wp_feed_event' ) ) {
				wp_clear_scheduled_hook('hk_wp_feed_event');	
			}
		}
		echo "<p>";
		echo $options["hk_wp_feed_log"];
		if (wp_next_scheduled( 'hk_wp_feed_event' )) {
			echo "<br>Next cron is run " . Date("Y-m-d H:i:s" , wp_next_scheduled( 'hk_wp_feed_event' )) . ".";
		} 
		else {
			echo "<br>Cron not used. The widget will be updated every half hour when visited.";
		}
		echo "</p>";

	}

	public function update( $new_instance, $old_instance ) {
		$instance['show_wp_feed'] = strip_tags( $new_instance['show_wp_feed'] );
		$instance['title'] = $new_instance['title'];
		$instance['enable_cron'] = $new_instance['enable_cron'];

		$options = get_option('hk_wp_feed_widget_' . $this->id);
		$options["hk_wp_feed_rss"] = strip_tags( $new_instance['hk_wp_feed_rss'] );
		$options["hk_wp_feed_days_new"] = strip_tags( $new_instance['hk_wp_feed_days_new'] );
		$options["hk_wp_feed_num"] = strip_tags( $new_instance['hk_wp_feed_num'] );
		update_option("hk_wp_feed_widget_" . $this->id, $options);

		return $instance;
	}

	public function widget( $args, $instance ) {
	    extract( $args );
		$options = get_option('hk_wp_feed_widget_' . $this->id);
		
		// check for new rss here every 30 minutes if no cron enabled
		if (!wp_next_scheduled( 'hk_wp_feed_event' ) && ($options["hk_wp_feed_check_time"] == "" || strtotime("+30 minutes",$options["hk_wp_feed_check_time"]) - time() < 0)) {
			hk_wp_feed_update($this->id);
		}
		$showwp_feed = ($instance["show_wp_feed"] == "" || in_array(get_query_var("cat"), split(",",$instance["show_wp_feed"]))) && $options["hk_wp_feed"] != "";
		if ($showwp_feed) : 
		
			$title = $instance['title'];//apply_filters( 'widget_title', $instance['title'] );
			
			echo $before_widget;
			if ( ! empty( $title ) ) {
				echo $before_title . $title . $after_title;
			}
			echo $options["hk_wp_feed"];
			echo $after_widget;
		endif;

	}
}
/* add the widget  */
add_action( 'widgets_init', create_function( '', 'register_widget( "hk_wp_feed_rss_widget" );' ) );



/*
 * WORDPRESS FEED RSS CRONJOB
 */
function hk_wp_feed() {
	global $wp_registered_widgets;
	foreach($wp_registered_widgets as $widget) {
		if ($widget["classname"] == "widget_hk_wp_feed_rss_widget") {
			echo $widget["id"] . "<br>";
			echo $widget["classname"] . "<br><br>";
			hk_wp_feed_update($widgetid);
		}
	}
}

function hk_wp_feed_update($widgetid) {	
	$options = get_option('hk_wp_feed_widget_' . $widgetid);
	$hk_wp_feed_check_time = time();
	$options["hk_wp_feed_check_time"] = $hk_wp_feed_check_time;
	
	$hk_wp_feed_days_new  = ($options["hk_wp_feed_days_new"] != "")?$options["hk_wp_feed_days_new"]:"1";
	$hk_wp_feed_num  = ($options["hk_wp_feed_num"] != "")?$options["hk_wp_feed_num"]:"10";
	
	$log = "Ingen wp_feed kollad.";
	$wp_feed = "";
	
	if ($options['hk_wp_feed_rss'] != "") :
		$log = "Checked rss " . date("d M H:i:s", strtotime("now"));
		$url = $options['hk_wp_feed_rss'];
		$rss =  simplexml_load_file($url);
		$has_new = "";
		if (count($rss->channel->item) > 0 ) {
			$log .= "<br>Found " . count($rss->channel->item) . " items in RSS feed.";
			$baseurl = $rss->channel->link;
			$newrsstime = strtotime("-" . $hk_wp_feed_days_new . " days");
			$count = 0;
			foreach ($rss->channel->item as $item)
			{
				if ($hk_wp_feed_num <= $count++) break;
				$time = strtotime($item->pubDate);
				$newclass = "";
				if ($time > $newrsstime) { 
					$has_new = "true";
					$newclass = " isnew";
				}
				$wp_feed .= "<div class='entry-wrapper$newclass'><span class='time'>".
				hk_nicedate($time) . "</span><a title='" . $item->description
				 . "' href='". $item->link
				 . "' target='_blank'>" . $item->title
				 . "</a></div>";
			} 
		}	
	endif;
	$options["hk_wp_feed_log"] = $log;
	$options["hk_wp_feed"] = $wp_feed;
	$options["hk_wp_feed_has_new"] = $has_new;
	$hk_wp_feed_rss = $options["hk_wp_feed_rss"];
	$hk_wp_feed_days_new  = ($options["hk_wp_feed_days_new"] != "")?$options["hk_wp_feed_days_new"]:"1";
	$hk_wp_feed_num  = ($options["hk_wp_feed_num"] != "")?$options["hk_wp_feed_num"]:"10";

	update_option("hk_wp_feed_widget_" . $widgetid, $options);
}
add_action("hk_wp_feed_event", "hk_wp_feed");


// add special cron interval to wp schedules
function hk_wp_feed_add_scheduled_interval($schedules) {
 
    $schedules['hk_wp_feed_schedule'] = array('interval'=>900, 'display'=>'Wordress Feed cron (15 minutes)');
 
    return $schedules;
}
add_filter('cron_schedules', 'hk_wp_feed_add_scheduled_interval');

if (!function_exists("hk_nicedate")) {
	function hk_nicedate($time) {
		$time = date("j F Y" , $time);
		$mo = array('januari' => 'January',
				'februari' => 'February',
				'mars' => 'March',
				'april' => 'April',
				'maj' => 'May',
				'juni' => 'June',
				'juli' => 'July',
				'augusti' => 'August',
				'september' => 'September',
				'oktober' => 'October',
				'november' => 'November',
				'december' => 'December');
				
		foreach ($mo as $swe => $eng)
		$time = preg_replace('/\b'.$eng.'\b/', $swe, $time);
		return $time;
	}
}
?>