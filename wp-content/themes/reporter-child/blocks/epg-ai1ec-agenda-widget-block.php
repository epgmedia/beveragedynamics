<?php
/** A Stock Index Block **/
class epg_ai1ec_agenda_widget_block extends AQ_Block {
    function __construct() {
        $block_options = array(
            'name' => 'AIOE-Calendar',
            'size' => 'span4',
        );
        parent::__construct('epg_ai1ec_agenda_widget_block', $block_options);
    }

    function form($instance) {
        global $ai1ec_view_helper;
        $default = array(
            'title'                  => __( 'Upcoming Events', AI1EC_PLUGIN_NAME ),
            'events_seek_type'       => 'events',
            'events_per_page'        => 10,
            'days_per_page'          => 10,
            'show_subscribe_buttons' => true,
            'show_calendar_button'   => true,
            'hide_on_calendar_page'  => true,
            'limit_by_cat'           => false,
            'limit_by_tag'           => false,
            'limit_by_post'          => false,
            'event_cat_ids'          => array(),
            'event_tag_ids'          => array(),
            'event_post_ids'         => array(),
        );
        $instance = wp_parse_args( (array) $instance, $default );

        // Get available cats, tags, events to allow user to limit widget to certain categories
        $events_categories = get_terms( 'events_categories', array( 'orderby' => 'name', "hide_empty" => false ) );
        $events_tags       = get_terms( 'events_tags', array( 'orderby' => 'name', "hide_empty" => false ) );
        $get_events        = new WP_Query( array ( 'post_type' => AI1EC_POST_TYPE, 'posts_per_page' => -1 ) );
        $events_options    = $get_events->posts;

        // Generate unique IDs and NAMEs of all needed form fields
        $fields = array(
            'title'                  => array('value'   => $instance['title']),
            'events_seek_type'       => array('value'   => $instance['events_seek_type']),
            'events_per_page'        => array('value'   => $instance['events_per_page']),
            'days_per_page'          => array('value'   => $instance['days_per_page']),
            'show_subscribe_buttons' => array('value'   => $instance['show_subscribe_buttons']),
            'show_calendar_button'   => array('value'   => $instance['show_calendar_button']),
            'hide_on_calendar_page'  => array('value'   => $instance['hide_on_calendar_page']),
            'limit_by_cat'           => array('value'   => $instance['limit_by_cat']),
            'limit_by_tag'           => array('value'   => $instance['limit_by_tag']),
            'limit_by_post'          => array('value'   => $instance['limit_by_post']),
            'event_cat_ids'          => array(
                'value'   => (array)$instance['event_cat_ids'],
                'options' => $events_categories
            ),
            'event_tag_ids'          => array(
                'value'   => (array)$instance['event_tag_ids'],
                'options' => $events_tags
            ),
            'event_post_ids'         => array(
                'value'   => (array)$instance['event_post_ids'],
                'options' => $events_options
            )
        );
        foreach( $fields as $field => $data ) {
            $fields[$field]['id']    = $this->get_field_id( $field );
            $fields[$field]['name']  = $this->get_field_name( $field );
            $fields[$field]['value'] = $data['value'];
            if( isset($data['options']) ) {
                $fields[$field]['options'] = $data['options'];
            }
        }

        $ai1ec_view_helper->display_admin( 'agenda-widget-form.php', $fields );
    }

    function block($instance) {
        extract($instance);

        global $ai1ec_view_helper,
               $ai1ec_events_helper,
               $ai1ec_calendar_helper,
               $ai1ec_settings,
               $ai1ec_themes_controller,
               $ai1ec_requirejs_controller;

        if ( $ai1ec_themes_controller->frontend_outdated_themes_notice() ) {
            return;
        }

        $ai1ec_requirejs_controller->add_link_to_render_js(
            Ai1ec_Requirejs_Controller::LOAD_ONLY_FRONTEND_SCRIPTS,
            false
        );
        $defaults = array(
            'hide_on_calendar_page'  => true,
            'event_cat_ids'          => array(),
            'event_tag_ids'          => array(),
            'event_post_ids'         => array(),
            'events_per_page'        => 10,
            'days_per_page'          => 10,
            'events_seek_type'       => 'events',
        );
        $instance = wp_parse_args( $instance, $defaults );

        if( $instance['hide_on_calendar_page'] &&
            is_page( $ai1ec_settings->calendar_page_id ) ) {
            return;
        }

        // Add params to the subscribe_url for filtering by Limits (category, tag)
        $subscribe_filter  = '';
        $subscribe_filter .= $instance['event_cat_ids'] ? '&ai1ec_cat_ids=' . join( ',', $instance['event_cat_ids'] ) : '';
        $subscribe_filter .= $instance['event_tag_ids'] ? '&ai1ec_tag_ids=' . join( ',', $instance['event_tag_ids'] ) : '';
        $subscribe_filter .= $instance['event_post_ids'] ? '&ai1ec_post_ids=' . join( ',', $instance['event_post_ids'] ) : '';

        // Get localized time
        $timestamp = $ai1ec_events_helper->gmt_to_local(
            Ai1ec_Time_Utility::current_time()
        );

        // Set $limit to the specified category/tag
        $limit = array(
            'cat_ids'   => $instance['event_cat_ids'],
            'tag_ids'   => $instance['event_tag_ids'],
            'post_ids'  => $instance['event_post_ids'],
        );

        // Get events, then classify into date array
        // JB: apply seek check here
        $seek_days  = ( 'days' === $instance['events_seek_type'] );
        $seek_count = $instance['events_per_page'];
        $last_day   = false;
        if ( $seek_days ) {
            $seek_count = $instance['days_per_page'] * 5;
            $last_day   = strtotime(
                '+' . $instance['days_per_page'] . ' days'
            );
        }
        $event_results = $ai1ec_calendar_helper->get_events_relative_to(
            $timestamp,
            $seek_count,
            0,
            $limit
        );
        if ( $seek_days ) {
            foreach ( $event_results['events'] as $ek => $event ) {
                if ( $event->start >= $last_day ) {
                    unset( $event_results['events'][$ek] );
                }
            }
        }

        $dates = $ai1ec_calendar_helper->get_agenda_like_date_array( $event_results['events'] );

        $args['title']                     = $instance['title'];
        $args['show_subscribe_buttons']    = $instance['show_subscribe_buttons'];
        $args['show_calendar_button']      = $instance['show_calendar_button'];
        $args['dates']                     = $dates;
        $args['show_location_in_title']    = $ai1ec_settings->show_location_in_title;
        $args['show_year_in_agenda_dates'] =
            $ai1ec_settings->show_year_in_agenda_dates;
        $args['calendar_url']              =
            $ai1ec_calendar_helper->get_calendar_url( $limit );
        $args['subscribe_url']             = AI1EC_EXPORT_URL . $subscribe_filter;
        $args['is_ticket_button_enabled']  =
            $ai1ec_calendar_helper->is_buy_ticket_enabled_for_view( 'agenda' );

        $args['before_widget'] = '';
        $args['after_widget'] = '';
        $args['before_title'] = '<h4 class="widget-title">';
        $args['after_title'] = '</h4>';

        $ai1ec_view_helper->display_theme( 'agenda-widget.php', $args );
    }
    /**
     * _valid_seek_type method
     *
     * Return valid seek type for given user input (selection).
     *
     * @param string $value User selection for seek type
     *
     * @return string Seek type to use
     */
    protected function _valid_seek_type( $value ) {
        static $list = array( 'events', 'days' );
        if ( ! in_array( $value, $list ) ) {
            return (string)reset( $list );
        }
        return $value;
    }
}