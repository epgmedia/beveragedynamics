<?php

/**
 * REGISTERS AQ PAGEBUILDER BLOCKS
 */
add_action( 'after_setup_theme', function() {

    // add blocks
    require_once( CHILDDIR . '/blocks/epg-stock-index-block.php');
    require_once( CHILDDIR . '/blocks/epg-ad-position-block.php');
    require_once( CHILDDIR . '/blocks/aq-widgets-block.php');
    require_once( CHILDDIR . '/blocks/epg-ai1ec-agenda-widget-block.php');

    // register blocks
    aq_register_block('EPG_Stock_Index_Block');
    aq_register_block('EPG_Ad_Position_Block');
    aq_register_block('AQ_Widgets_Block');
    aq_register_block('epg_ai1ec_agenda_widget_block');

}, 2 );