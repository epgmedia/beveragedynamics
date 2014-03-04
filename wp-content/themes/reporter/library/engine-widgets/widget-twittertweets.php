<?php

/* ==  Widget ==============================*/

class DT_Twitter extends WP_Widget {


/* ==  Widget Setup ==============================*/

	function DT_Twitter() {
		/* Widget settings. */
		$widget_ops = array( 'classname' => 'DT_Twitter', 'description' => __('A widget that displays your latest tweets.', 'engine') );

		/* Widget control settings. */
		$control_ops = array( 'id_base' => 'dt_tweet_widget' );

		/* Create the widget. */
		$this->WP_Widget( 'dt_tweet_widget', __('DT Twitter', 'engine'), $widget_ops, $control_ops );
	}


/* ==  Display Widget ==============================*/

	function widget( $args, $instance ) {
		extract( $args );

		/* Our variables from the widget settings. */
		$title = apply_filters('widget_title', $instance['title'] );

		$dt_twitter_username = $instance['username'];
		$dt_twitter_postcount = $instance['postcount'];
		$tweettext = $instance['tweettext'];

		$twitter_access_token = $instance['twitter_access_token'];
		$twitter_access_token_secret = $instance['twitter_access_token_secret'];
		$twitter_consumer_key = $instance['twitter_consumer_key'];
		$twitter_consumer_key_secret = $instance['twitter_consumer_key_secret'];

		/* Before widget (defined by themes). */
		echo $before_widget;

		/* Display the widget title if one was input (before and after defined by themes). */
		if ( $title )
			echo $before_title . $title . $after_title;

		/* Display Latest Tweets */
		 ?>

            <?php
		    //ORIONK STARTS
		    require_once('TwitterAPIExchange.php');
		    $settings = array(
			    'oauth_access_token' => $twitter_access_token,
			    'oauth_access_token_secret' => $twitter_access_token_secret,
			    'consumer_key' => $twitter_consumer_key,
			    'consumer_secret' => $twitter_consumer_key_secret
			);
		    $url = 'https://api.twitter.com/1.1/statuses/user_timeline.json';
		    $getfield = '?screen_name='.$dt_twitter_username.'&count='.$dt_twitter_postcount;
		    $requestMethod = 'GET';

		    $twitter = new TwitterAPIExchange($settings);


		    $new_twits = $twitter->setGetfield($getfield)
		             ->buildOauth($url, $requestMethod)
		             ->performRequest();

            //convert links to clickable format
                if (!function_exists('tp_convert_links')) {
                    function tp_convert_links($status,$targetBlank=true,$linkMaxLen=250){

                        // the target
                            $target=$targetBlank ? " target=\"_blank\" " : "";

                        // convert link to url
                            $status = preg_replace("/((http:\/\/|https:\/\/)[^ )
                            ]+)/e", "'<a href=\"$1\" style=\"font-weight:bold;\"  title=\"$1\" $target >'. ((strlen('$1')>=$linkMaxLen ? substr('$1',0,$linkMaxLen).'...':'$1')).'</a>'", $status);

                        // convert @ to follow
                            $status = preg_replace("/(@([_a-z0-9\-]+))/i","<a href=\"http://twitter.com/$2\" style=\"font-weight:bold;\" title=\"Follow $2\" $target >$1</a>",$status);

                        // convert # to search
                            $status = preg_replace("/(#([_a-z0-9\-]+))/i","<a href=\"https://twitter.com/search?q=$2\" style=\"font-weight:bold;\"  title=\"Search $1\" $target >$1</a>",$status);

                        // return the status
                            return $status;
                    }
                }

                $new_twits_json = json_decode($new_twits);

				/* Show error on front end when user hasn't entered twitter keys */
                echo '<ul class="clearfix">';

                // Check if any of the four keys are not set or empty
                if( $settings['oauth_access_token'] == "" || $settings['oauth_access_token_secret'] == "" || $settings['consumer_key'] == "" || $settings['consumer_secret'] == "" ) {
					// Display a message
					echo "You must enter your Twitter OAuth keys in the widget settings.";
				} else {
					// Display the tweets.
	                foreach ($new_twits_json as $new_twits_json_single){
	                    echo '<li>' .tp_convert_links($new_twits_json_single->text) .'</li>';
	                }
				}
                echo '</ul>';

				//ORIONK ENDS
                ?>

            <?php if($tweettext != '') : ?>
	            <div class="visit-wrap">
	            	<a target="_blank" href="http://twitter.com/<?php echo $dt_twitter_username; ?>" class="follow-me"><?php echo $tweettext; ?><span class="right-arrow"></span></a>
	            </div>
			<?php endif; ?>

		<?php

		/* After widget (defined by themes). */
		echo $after_widget;
	}



/* ==  Update Widget ==============================*/

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;

		/* Strip tags for title and name to remove HTML (important for text inputs). */
		$instance['title'] = strip_tags( $new_instance['title'] );
		$instance['username'] = strip_tags( $new_instance['username'] );
		$instance['postcount'] = strip_tags( $new_instance['postcount'] );
		$instance['tweettext'] = strip_tags( $new_instance['tweettext'] );

		$instance['twitter_access_token'] = strip_tags( $new_instance['twitter_access_token'] );
		$instance['twitter_access_token_secret'] = strip_tags( $new_instance['twitter_access_token_secret'] );
		$instance['twitter_consumer_key'] = strip_tags( $new_instance['twitter_consumer_key'] );
		$instance['twitter_consumer_key_secret'] = strip_tags( $new_instance['twitter_consumer_key_secret'] );

		/* No need to strip tags for.. */

		return $instance;
	}



/* ==  Widget Settings ==============================*/

	/**
	 * Displays the widget settings controls on the widget panel.
	 * Make use of the get_field_id() and get_field_name() function
	 * when creating your form elements. This handles the confusing stuff.
	 */

	function form( $instance ) {

		/* Set up some default widget settings. */
		$defaults = array(
		'title' => 'Latest Tweets',
		'username' => 'designerthemes',
		'postcount' => '1',
		'tweettext' => 'Follow on Twitter',
		);
		$instance = wp_parse_args( (array) $instance, $defaults ); ?>

		<!-- Widget Title: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e('Title:', 'engine') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>" />
		</p>

		<!-- Username: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'username' ); ?>"><?php _e('Twitter Username e.g. designerthemes', 'engine') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'username' ); ?>" name="<?php echo $this->get_field_name( 'username' ); ?>" value="<?php echo $instance['username']; ?>" />
		</p>

		<!-- Postcount: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'postcount' ); ?>"><?php _e('Number of tweets (max 20)', 'engine') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'postcount' ); ?>" name="<?php echo $this->get_field_name( 'postcount' ); ?>" value="<?php echo $instance['postcount']; ?>" />
		</p>

		<!-- Tweettext: Text Input -->
		<p>
			<label for="<?php echo $this->get_field_id( 'tweettext' ); ?>"><?php _e('Follow Text e.g. Follow me on Twitter', 'engine') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'tweettext' ); ?>" name="<?php echo $this->get_field_name( 'tweettext' ); ?>" value="<?php echo $instance['tweettext']; ?>" />
		</p>

		<!-------- New ----->
		<div style="padding:10px; background:#e5e5e5;">
		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_consumer_key' ); ?>"><?php _e('OAuth Consumer Key:', 'engine') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'twitter_consumer_key' ); ?>" name="<?php echo $this->get_field_name( 'twitter_consumer_key' ); ?>" value="<?php echo $instance['twitter_consumer_key']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_consumer_key_secret' ); ?>"><?php _e('OAuth Consumer Secret:', 'engine') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'twitter_consumer_key_secret' ); ?>" name="<?php echo $this->get_field_name( 'twitter_consumer_key_secret' ); ?>" value="<?php echo $instance['twitter_consumer_key_secret']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_access_token' ); ?>"><?php _e('OAuth Access Token:', 'engine') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'twitter_access_token' ); ?>" name="<?php echo $this->get_field_name( 'twitter_access_token' ); ?>" value="<?php echo $instance['twitter_access_token']; ?>" />
		</p>

		<p>
			<label for="<?php echo $this->get_field_id( 'twitter_access_token_secret' ); ?>"><?php _e('OAuth Access Token Secret:', 'engine') ?></label>
			<input type="text" class="widefat" id="<?php echo $this->get_field_id( 'twitter_access_token_secret' ); ?>" name="<?php echo $this->get_field_name( 'twitter_access_token_secret' ); ?>" value="<?php echo $instance['twitter_access_token_secret']; ?>" />
		</p>

		<p><em><a target="_blank" href="http://f.cl.ly/items/3U1q3Y0z0U2y232r0C2e/twitter-keys.html">Where do I find these keys?</em></p>
		</div>

	<?php
	}
}


function DT_Twitter_Init() {
    register_widget('DT_Twitter');
}

add_action('widgets_init', 'DT_Twitter_Init');