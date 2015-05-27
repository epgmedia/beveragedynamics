<?php

/**
 * The concrete class for posterboard view.
 *
 * @author     Time.ly Network Inc.
 * @since      2.0
 *
 * @package    AI1EC
 * @subpackage AI1EC.View
 */
class Ai1ec_Calendar_View_Posterboard extends Ai1ec_Calendar_View_Agenda {

	/* (non-PHPdoc)
	 * @see Ai1ec_Calendar_View_Abstract::get_name()
	*/
	public function get_name() {
		return 'posterboard';
	}

	/**
	 * Add Posterboard-specific arguments to template.
	 *
	 * @param array $args Twig args.
	 *
	 * @return array Twig args.
	 */
	public function get_extra_template_arguments( array $args ) {
		$settings = $this->_registry->get( 'model.settings' );
		$args['tile_min_width'] = $settings->get( 'posterboard_tile_min_width' );
		$args['posterboard_equal_height'] = $settings->get( 'posterboard_equal_height' );
		$args['text'] = array(
			'no_results' => __(
				'There are no upcoming events to display at this time.',
				AI1ECEV_PLUGIN_NAME
			),
			'edit' => __(
				'Edit',
				AI1ECEV_PLUGIN_NAME
			),
		);
		$show_location_in_title = (bool)$args['show_location_in_title'];
		if ( ! $show_location_in_title ) {
			return $args;
		}
		foreach ( $args['dates'] as $date => &$date_info ) {
			foreach ( $date_info['events'] as &$category ) {
				foreach ( $category as &$event ) {
					$location = '';
					$venue    = $this->_get_event_value( $event, 'venue' );
					if ( ! empty( $venue ) ) {
						$location = sprintf(
							_x(
								' @ %s',
								'separator for venue suffix in event title',
								AI1ECEV_PLUGIN_NAME
							),
							$venue
						);
						$this->_set_event_value(
							$event,
							'event_location',
							$location
						);
					}
				}
			}
		}
		$args['action_buttons'] = apply_filters(
			'ai1ec_action_buttons',
			''
		);
		return $args;
	}

	/* (non-PHPdoc)
	 * @see Ai1ec_Calendar_View_Abstract::_add_view_specific_runtime_properties()
	*/
	protected function _add_view_specific_runtime_properties(
		Ai1ec_Event $event
	) {
		parent::_add_view_specific_runtime_properties( $event );
		$taxonomy = $this->_registry->get( 'view.event.taxonomy' );
		$event->set_runtime(
			'category_bg_color',
			$taxonomy->get_category_bg_color( $event )
		);
		$event->set_runtime(
			'category_text_color',
			$taxonomy->get_category_text_color( $event )
		);
	}

	/**
	 * Gets property from event checking if it's compatibility class or array.
	 *
	 * @param mixed  $event Array of Ai1ec_Event_Compatiblity.
	 * @param string $name  Property name.
	 *
	 * @return string|null Value or null.
	 */
	protected function _get_event_value( $event, $name ) {
		if ( $event instanceof Ai1ec_Event_Compatibility ) {
			return $event->$name;
		}
		if ( is_array( $event ) ) {
			return isset( $event[$name] ) ? $event[$name] : null;
		}
		return null;
	}

	/**
	 * Sets property on event checking if it's compatibility class or array.
	 *
	 * @param mixed  $event Array of Ai1ec_Event_Compatiblity.
	 * @param string $name  Property name.
	 * @param mixed  $value Property value.
	 *
	 * @return string|null Value or null.
	 */
	protected function _set_event_value( $event, $name, $value ) {
		if ( $event instanceof Ai1ec_Event_Compatibility ) {
			$event->$name = $value;
		}
		if ( is_array( $event ) ) {
			$event[$name] = $value;
		}
	}
}
