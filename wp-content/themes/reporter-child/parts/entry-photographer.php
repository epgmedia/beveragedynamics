<?php
/**
 * Adds photographer credit
 */
if ( get_field('photographer') != NULL ) {
    $photographer =  get_field('photographer');
    echo '<span class="entry-photographer">Photos by ' . $photographer . '</span>';
}

