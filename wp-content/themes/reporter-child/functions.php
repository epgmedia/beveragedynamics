<?php

//error_reporting(E_ALL);


/** Child Theme Directory  @name CHILDURI */
define('CHILDDIR', get_stylesheet_directory());

/** Child theme URI  @name CHILDURI */
define('CHILDURI', get_stylesheet_directory_uri());

/** Current Child Theme Version  @name CHILDVERSION */
define('CHILDVERSION', '1.0.0');

/** Auto Version CSS  @name VERSIONCSS */
define('VERSIONCSS', TRUE);

/**
 * Additional Scripts and Styles
 *
 * @see assets/styles.php
 */
require_once( CHILDDIR . '/assets/styles.php');

/**
 * Admin page functions
 *
 * @see admin/admin-functions.php
 */
require_once( CHILDDIR . '/admin/admin-functions.php');

/**
 * View Parts
 *
 * @see parts/parts-functions.php
 */
require_once( CHILDDIR . '/parts/parts-functions.php');

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