<?php
/*
Plugin Name: Easy WP Feed Widget
Plugin URI: http://wordpress.org/plugins/easy-wp-feed-widget/
Description: Wordpress widget to show a Wordpress feed.
Author: Jonas Hjalmarsson, Hultsfreds kommun
Version: 1.2.1
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
		if ( isset( $instance[ 'always_show_title' ] ) ) {	$always_show_title = $instance[ 'always_show_title' ];
		} else { $always_show_title = ""; }
		if ( isset( $instance[ 'no_content_text' ] ) ) {	$no_content_text = $instance[ 'no_content_text' ];
		} else { $no_content_text = ""; }
		$options = get_option('hk_wp_feed_widget_' . $this->id);
		$horizontal = strip_tags( $options['horizontal'] );
		$hide_date = strip_tags( $options['hide_date'] );
		$hide_updated_date = strip_tags( $options['hide_updated_date'] );
		$hk_wp_feed_rss = strip_tags( $options['hk_wp_feed_rss'] );
		$hk_wp_feed_days_new = strip_tags( $options['hk_wp_feed_days_new'] );
		$hk_wp_feed_num = strip_tags( $options['hk_wp_feed_num'] );
		$hk_wp_feed_more_text = $options['hk_wp_feed_more_text'];
		$hk_wp_feed_more_link = strip_tags( $options['hk_wp_feed_more_link'] );
		$hk_wp_feed_show_num_new = $options['hk_wp_feed_show_num_new'];
		$hk_fix_timezone = $options['hk_fix_timezone'];		
		
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
		<input type="checkbox" id="<?php echo $this->get_field_id( 'hide_updated_date' ); ?>" name="<?php echo $this->get_field_name( 'hide_updated_date' ); ?>" value="1"<?php checked( 1 == $hide_updated_date ); ?> /> 
		<label for="<?php echo $this->get_field_id( 'hide_updated_date' ); ?>">Hide updated dates</label>
		</p>
		<p>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'hide_date' ); ?>" name="<?php echo $this->get_field_name( 'hide_date' ); ?>" value="1"<?php checked( 1 == $hide_date ); ?> /> 
		<label for="<?php echo $this->get_field_id( 'hide_date' ); ?>">Hide dates</label>
		</p>
		<p>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'horizontal' ); ?>" name="<?php echo $this->get_field_name( 'horizontal' ); ?>" value="1"<?php checked( 1 == $horizontal ); ?> /> 
		<label for="<?php echo $this->get_field_id( 'horizontal' ); ?>">Use horizontal widget style</label>
		</p>
		<p>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'enable_cron' ); ?>" name="<?php echo $this->get_field_name( 'enable_cron' ); ?>" value="1"<?php checked( 1 == $enable_cron ); ?> /> 
		<label for="<?php echo $this->get_field_id( 'enable_cron' ); ?>">Use cron to generate cache every quarter</label>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'hk_wp_feed_show_num_new' ); ?>" title="">Show subtitle, first line = one in feed, second line = two in feed ... last line = if more in feed (replaces [nr] with number items in feed)</label> 
		<textarea class="widefat" id="<?php echo $this->get_field_id( 'hk_wp_feed_show_num_new' ); ?>" name="<?php echo $this->get_field_name( 'hk_wp_feed_show_num_new' ); ?>"><?php echo $hk_wp_feed_show_num_new; ?></textarea>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'hk_wp_feed_more_text' ); ?>">Show more link text.</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'hk_wp_feed_more_text' ); ?>" name="<?php echo $this->get_field_name( 'hk_wp_feed_more_text' ); ?>" type="text" value="<?php echo esc_attr( $hk_wp_feed_more_text); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'hk_wp_feed_more_link' ); ?>">Show more link.</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'hk_wp_feed_more_link' ); ?>" name="<?php echo $this->get_field_name( 'hk_wp_feed_more_link' ); ?>" type="text" value="<?php echo esc_attr( $hk_wp_feed_more_link); ?>" />
		</p>
		<p>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'always_show_title' ); ?>" name="<?php echo $this->get_field_name( 'always_show_title' ); ?>" value="1"<?php checked( 1 == $always_show_title ); ?> /> 
		<label for="<?php echo $this->get_field_id( 'always_show_title' ); ?>">Always show title</label>
		</p>
		<p>
		<label for="<?php echo $this->get_field_id( 'no_content_text' ); ?>">Text when no content.</label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'no_content_text' ); ?>" name="<?php echo $this->get_field_name( 'no_content_text' ); ?>" type="text" value="<?php echo esc_attr( $no_content_text); ?>" />
		</p>
		<p>
		<input type="checkbox" id="<?php echo $this->get_field_id( 'hk_fix_timezone' ); ?>" name="<?php echo $this->get_field_name( 'hk_fix_timezone' ); ?>" value="1"<?php checked( 1 == $hk_fix_timezone ); ?> /> 
		<label for="<?php echo $this->get_field_id( 'hk_fix_timezone' ); ?>">Ignore timezone</label>
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
		$instance["always_show_title"] = $new_instance['always_show_title'];
		$instance["no_content_text"] = $new_instance['no_content_text'];

		$options = get_option('hk_wp_feed_widget_' . $this->id);
		$options["hk_wp_feed_rss"] = strip_tags( $new_instance['hk_wp_feed_rss'] );
		$options["hk_wp_feed_days_new"] = strip_tags( $new_instance['hk_wp_feed_days_new'] );
		$options["hk_wp_feed_num"] = strip_tags( $new_instance['hk_wp_feed_num'] );
		$options["hk_wp_feed_more_text"] = $new_instance['hk_wp_feed_more_text'];
		$options["hk_wp_feed_more_link"] = strip_tags( $new_instance['hk_wp_feed_more_link'] );
		$options["hk_wp_feed_show_num_new"] = $new_instance['hk_wp_feed_show_num_new'];
		$options["horizontal"] = $new_instance['horizontal'];
		$options["hide_date"] = $new_instance['hide_date'];
		$options["hide_updated_date"] = $new_instance['hide_updated_date'];
		$options["hk_fix_timezone"] = $new_instance['hk_fix_timezone'];

		update_option("hk_wp_feed_widget_" . $this->id, $options);

		/* regenerate cache */
		hk_wp_feed();
		
		return $instance;
	}

	public function widget( $args, $instance ) {
	    extract( $args );
		$options = get_option('hk_wp_feed_widget_' . $this->id);
		$horizontal  = ($options["horizontal"] != "")?true:false;

		// check for new rss here every 30 minutes if no cron enabled
		if (!wp_next_scheduled( 'hk_wp_feed_event' ) && ($options["hk_wp_feed_check_time"] == "" || strtotime("+30 minutes",$options["hk_wp_feed_check_time"]) - time() < 0)) {
			hk_wp_feed_update($this->id);
			$options = get_option('hk_wp_feed_widget_' . $this->id);
		}
		$showwp_feed = ($instance["show_wp_feed"] == "" || in_array(get_query_var("cat"), split(",",$instance["show_wp_feed"]))) && $options["hk_wp_feed"] != "";
		$showwp_feed_no_content = ($instance["show_wp_feed"] == "" || in_array(get_query_var("cat"), split(",",$instance["show_wp_feed"]))) && $instance["always_show_title"] != "";
		if ($showwp_feed) : 
			$title = $instance['title'];//apply_filters( 'widget_title', $instance['title'] );
			if ($horizontal) {
				$before_widget = str_replace('class="','class="horizontal ',$before_widget);
			}
			echo $before_widget;
			if ( ! empty( $title ) ) {
				echo $before_title . $title . $after_title;
			}
			echo $options["hk_wp_feed"];
			echo $after_widget;
		elseif ($showwp_feed_no_content) :
			$title = $instance['title'];//apply_filters( 'widget_title', $instance['title'] );
			if ($horizontal) {
				$before_widget = str_replace('class="','class="horizontal ',$before_widget);
			}
			echo $before_widget;
			if ( ! empty( $title ) ) {
				echo $before_title . $title . $after_title;
				if ($instance["no_content_text"] != "") {
					echo "<div class='sub-title'>".$instance["no_content_text"]."</div>";
				}
			}
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
			hk_wp_feed_update($widget["id"]);
		}
	}
}

function hk_wp_feed_update($widgetid) {	
	$options = get_option('hk_wp_feed_widget_' . $widgetid);
	$hk_wp_feed_check_time = time();
	$options["hk_wp_feed_check_time"] = $hk_wp_feed_check_time;
	
	$hk_wp_feed_days_new = ($options["hk_wp_feed_days_new"] != "")?$options["hk_wp_feed_days_new"]:"1";
	$hk_wp_feed_num = ($options["hk_wp_feed_num"] != "")?$options["hk_wp_feed_num"]:"10";
	$hk_wp_feed_more_text = ($options["hk_wp_feed_more_text"] != "")?$options["hk_wp_feed_more_text"]:"";
	$hk_wp_feed_more_link = ($options["hk_wp_feed_more_link"] != "")?$options["hk_wp_feed_more_link"]:"";
	$hk_wp_feed_show_num_new = ($options["hk_wp_feed_show_num_new"] != "")?$options["hk_wp_feed_show_num_new"]:"";
	$hk_fix_timezone = ($options["hk_fix_timezone"] != "")?$options["hk_fix_timezone"]:"";
	
	
	$hide_updated_date  = ($options["hide_updated_date"] != "")?true:false;
	$hide_date  = ($options["hide_date"] != "")?true:false;
	
	$log = "Ingen wp_feed kollad.";
	$wp_feed = "";
	
	if ($options['hk_wp_feed_rss'] != "") :
		$log = "Checked rss " . date("d M H:i:s", strtotime("now"));
		$url = $options['hk_wp_feed_rss'];
		$rss = "";
		if (!empty($url)) {
			$rss =  simplexml_load_file($url);
		}
		$has_new = "";
		if (!empty($rss) && count($rss->channel->item) > 0 ) {
			$log .= "<br>Found " . count($rss->channel->item) . " items in RSS feed.";
			$num_new_text = explode("\n",$hk_wp_feed_show_num_new);
			if (count($num_new_text)-1 >= count($rss->channel->item))
				$available_feeds_text = $num_new_text[count($rss->channel->item)-1];
			else if (count($num_new_text)-1 < count($rss->channel->item))
				$available_feeds_text = str_replace("[nr]",count($rss->channel->item),$num_new_text[count($num_new_text)-1]);
			
			if ($available_feeds_text != "") 
			{
				$hk_wp_feed_more_link_pre = "";
				$hk_wp_feed_more_link_post = "";
				if ($hk_wp_feed_more_link != "") {
					$hk_wp_feed_more_link_pre = "<a href='$hk_wp_feed_more_link'>";
					$hk_wp_feed_more_link_post = "</a>";
				}
				$wp_feed .= "<div class='sub-title'>$hk_wp_feed_more_link_pre$available_feeds_text$hk_wp_feed_more_link_post</div>";
			}

			$baseurl = $rss->channel->link;
			$newrsstime = strtotime("-" . $hk_wp_feed_days_new . " days");
			$count = 0;
			$hide_date_class = ($hide_date)?" hide_date":"";
			$wp_feed .= "<div class='content-wrapper$hide_date_class'>";
			foreach ($rss->channel->item as $item)
			{
				$if_hide_in_widget = "";
				if ($hk_wp_feed_num <= $count) {
					$if_hide_in_widget = " style='display:none;'";
				}
				else {
					$count++;
				}
				if ($hk_fix_timezone) {
					$time = strtotime(substr($item->pubDate,0,strrpos($item->pubDate, " ")));
				}
				else {
					$time = strtotime($item->pubDate);
				}
				$nice_time = hk_nicedate($time);
				if (!empty($item->modDate)) {
					$modtime = strtotime($item->modDate);
					$nice_modtime = hk_nicedate($modtime);
				}
				else {
					$modtime = "";
					$nice_modtime = "";
				}

				$newclass = "";
				if ($time > $newrsstime || $modtime > $newrsstime) { 
					$has_new = "true";
					$newclass = " isnew";
				}
				$wp_feed .= "<div class='entry-wrapper$newclass'$if_hide_in_widget>";
				if (!$hide_date) {
					$wp_feed .= "<span class='time'>".hk_nicedate($time) . "</span>";
				}
				$wp_feed .= "<a title='" . $item->description
				 . "' href='". $item->link
				 . "' target='_blank'>" . $item->title
				 . "</a>";
				if (!$hide_updated_date && $nice_modtime != "" && $nice_modtime != $nice_time ) 
					$wp_feed .= "<span class='modified time'>Uppdaterad $nice_modtime</span>";
				$wp_feed .= "</div>";
			} 
			if (count($rss->channel->item) > $count && $hk_wp_feed_more_text != "" && $hk_wp_feed_more_link != "") {
				$wp_feed .= "<div class='entry-wrapper'>";
				$wp_feed .= "<a class='read-more-link' title='" . $hk_wp_feed_more_text
				 . "' href='". $hk_wp_feed_more_link
				 . "'>" . $hk_wp_feed_more_text
				 . "</a>";
				$wp_feed .= "</div>";
			}
			$wp_feed .= "</div>";
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