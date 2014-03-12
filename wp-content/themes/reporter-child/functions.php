<?php
/*
 *
 * Get the content position with the sidebar options
 *
 */

if (!function_exists('engine_content_position'))
{
    function engine_content_position() {

        if( !is_admin() ) {

            global $reporter_data;

            // Default
            $sidebar_position = 'right';
            $content_position = 'large-8 left';

            // Default sidebar positions
            if( is_single() )
                if( isset($reporter_data['post_sidebar_pos']) )
                    $sidebar_position = $reporter_data['post_sidebar_pos'];

            if( is_page() )
                if( isset($reporter_data['page_sidebar_pos']) )
                    $sidebar_position = $reporter_data['page_sidebar_pos'];

            if( is_archive() )
                if( isset($reporter_data['archive_sidebar_pos']) )
                    $sidebar_position = $reporter_data['archive_sidebar_pos'];

            // Override if sidebar position is set for post/page metabox
            if( is_singular() ) {

                $single_sidebar_position = get_post_meta(get_the_ID(), 'engine_sidebar_pos', TRUE);

                if( $single_sidebar_position != '' )
                    $sidebar_position = $single_sidebar_position;

            }

            if( $sidebar_position == 'right-sidebar' ) $content_position = 'large-8 left';
            if( $sidebar_position == 'left-sidebar' ) $content_position = 'large-8 right';
            if( $sidebar_position == 'no-sidebar' ) $content_position = 'large-12';

            $output = $content_position;

            return $output;

        }

    }
}

/**
 *
 * Removes "Top Stories" and "Uncategorized" categories from printed category lists.
 *
 * @param $thelist
 * @param string $separator
 * @return string
 */

function the_category_filter($thelist,$separator=' ') {
    if(!defined('WP_ADMIN')) {
        //Category IDs to exclude
        //Excludes Uncategorized, Top Stories
        $exclude = array(1, 91);

        $exclude2 = array();
        foreach($exclude as $c) {
            $exclude2[] = get_cat_name($c);
        }

        $cats = explode($separator,$thelist);
        $newlist = array();
        foreach($cats as $cat) {
            $catname = trim(strip_tags($cat));
            if(!in_array($catname,$exclude2))
                $newlist[] = $cat;
        }
        return implode($separator,$newlist);
    } else {
        return $thelist;
    }
}
add_filter('the_category','the_category_filter', 10, 2);

/**
 * Ad Position Widget
 * Adds a widget that takes an ad position's variables and creates a new ad position
 *
 */
class WP_Widget_google_ads_position extends WP_Widget {

    function __construct() {
        $widget_ops = array('classname' => 'widget_google_ad_position', 'description' => __("A simple DoubleClick ad position.", 'reactor') );
        parent::__construct('dbc-ad-position', __('Double Click Ad Position', 'reactor'), $widget_ops);
        $this->alt_option_name = 'widget_dbc_ad_position';
    }

    function widget($args, $instance) {
        ob_start();
        extract($args);

        if( empty($instance['title']) ){
            $title = 'Ad Position';
        }else{
            $title = apply_filters('widget_title', $instance['title'], $instance, $this->id_base);
        }

        if ( $instance['adPositionId'] ) {
            $content =  "\n" .'<script type="text/javascript">' .
                "\n" . 'googletag.cmd.push(function() { googletag.display("' . $instance['adPositionId'] . '"); });' .
                "\n" . '</script>';
        } else {
            $content = '';
        }
        $before_cmt = "\n<!-- $title -->\n";
        $before_content = "\n" . '<div id="' .  $instance['adPositionId'] . '" class="adPosition" style="width:' .
            $instance['width'] . 'px; height:' .  $instance['height'] . 'px;">' . "\n";
        $after_content = "\n" . '</div>' . "\n";

        ## Output - Position ID included
        if ($content) :
            $output_content =
                $before_widget .
                    $before_cmt .
                    $before_content .
                        $content .
                    $after_content .
                $after_widget;
            ## Print the output
            echo $output_content;
        endif;

        ob_flush();
    }

    function form( $instance ) {
        // div comment
        $title = isset($instance['title']) ? esc_attr( $instance['title'] ) : '';
        // div id
        $adPositionId = isset($instance['adPositionId']) ? esc_attr( $instance['adPositionId'] ) : '';
        // position min-width
        $width = isset($instance['width']) ? absint( $instance['width'] ) : '';
        // position min-height
        $height = isset($instance['height']) ? absint( $instance['height'] ) : '';
        ?>
        <p><label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'engine'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>"
                type="text" value="<?php echo $title; ?>" placeholder="Comment from Code" /></p>
        <p><label for="<?php echo $this->get_field_id('adPositionId'); ?>"><?php _e('Ad Position ID:', 'engine'); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id('adPositionId'); ?>" name="<?php echo $this->get_field_name('adPositionId'); ?>"
            type="text" value="<?php echo $adPositionId; ?>" placeholder="The ID of the div" /></p>
        <div class="fatHalf">
            <p><label for="<?php echo $this->get_field_id('width'); ?>"><?php _e('Position Width:', 'engine'); ?></label>
            <input id="<?php echo $this->get_field_id('width'); ?>" name="<?php echo $this->get_field_name('width'); ?>"
                type="number" value="<?php echo $width; ?>" placeholder="In Pixels" /></p>
            <p><label for="<?php echo $this->get_field_id('height'); ?>"><?php _e('Position Height:', 'engine'); ?></label>
            <input id="<?php echo $this->get_field_id('height'); ?>" name="<?php echo $this->get_field_name('height'); ?>"
                type="number" value="<?php echo $height; ?>" placeholder="In Pixels" /></p>
        </div>
        <?php
    }

    function update( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['adPositionId'] = strip_tags($new_instance['adPositionId']);
        $instance['width'] = (int) $new_instance['width'];
        $instance['height'] = (int) $new_instance['height'];
        return $instance;
    }
}

function WP_Widget_google_ads_position_init() {
    register_widget('WP_Widget_google_ads_position');
}

add_action('widgets_init', 'WP_Widget_google_ads_position_init');

function archive_first_excerpt() {

}



/*
 * Talk with theme create about supporting child blocks.
//require_once( TEMPLATEPATH . '/library/theme-pagebuilder/aq-page-builder.php');
if(class_exists('AQ_Page_Builder')) {
    //include the block files
    //require_once(AQPB_PATH . 'blocks/epg-stock-index-block.php');
    //register the blocks
    //aq_register_block('EPG_Stock_Index_Block');
}
 */