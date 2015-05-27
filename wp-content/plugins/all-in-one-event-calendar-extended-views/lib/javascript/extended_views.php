<?php

/**
 * The class which adds JavaScript for extended views.
 *
 * @author     Time.ly Network Inc.
 * @since      2.0
 *
 * @package    AI1EC
 * @subpackage AI1EC.Facebook
 */
class Ai1ec_Javascript_Extended_Views extends Ai1ec_Base {

	/**
	 * Adds the JavaScript for extended views if on the calendar page.
	 *
	 * @param array  $files
	 * @param string $page_to_load
	 *
	 * @return array
	 */
	public function add_js( array $files, $page_to_load ) {
		switch ( $page_to_load ) {
			case Ai1ec_Javascript_Controller::CALENDAR_PAGE_JS:
				$files[] = AI1ECEV_PATH . '/public/js/pages/extended_views.js';
				break;

			case 'ai1ec_widget.js':
			case 'main_widget.js':
				$files[] = array(
					'url' => AI1ECEV_URL . '/public/js/pages/extended_views.js',
					'id'  => 'ai1ec_extended_views_js'
				);
				break;
		}
		return $files;
	}
}
