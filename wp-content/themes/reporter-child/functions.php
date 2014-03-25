<?php
/** Child Theme Directory  @name CHILDURI */
define('CHILDDIR', get_stylesheet_directory());

/** Child theme URI  @name CHILDURI */
define('CHILDURI', get_stylesheet_directory_uri());

/** Current Child Theme Version  @name CHILDVERSION */
define('CHILDVERSION', 'v0.0.1');

/**
 * View Parts
 *
 * @see parts/parts-functions.php
 */
require_once( CHILDDIR . '/parts/parts-functions.php');

/**
 * Additional Scripts and Styles
 *
 * @see assets/styles.php
 */
require_once( CHILDDIR . '/assets/styles.php');

/**
 * Ads
 *
 * @see ads/ad-functions.php
 */
require_once( CHILDDIR . '/ads/ad-functions.php');
/**
 * Aqua Page Builder Block Functions
 *
 * @see blocks/block-functions.php
 */
require_once( CHILDDIR . '/blocks/block-functions.php');

/**
 * Menu, Sidebar and Widget Registration
 *
 * @see widgets/widget-functions.php
 */
require_once( CHILDDIR . '/widgets/widget-functions.php');

