<?php

/**
 * The Ad Position
 *
 * Returns the ad position based on a few parameters.
 *
 * @param string $name Name of position. E.g. "Leaderboard".
 * @param string $pos Position of ad. E.g. "Top".
 * @param bool $inline Display box inline. Defaults to false
 */
function the_ad_position($name, $pos, $inline = FALSE) {

    $name = strtolower($name);
    $pos = strtolower($pos);

    $position = 'ads/' . $name . '-' . $pos;

    if ($inline === TRUE) {
        get_template_part("$position");
        return FALSE;
    }

    $the_ad = CHILDDIR . '/' . $position . '.php';

    switch ($name):
        case 'leaderboard':
            $classes = 'small-12 large-12 center-column soldPosition leaderboard';
            echo '<div class="' . $classes . '">';
            include("$the_ad");
            echo '</div>';

            break;

        case 'box':
            //small-12 large-4 column right-rail soldPosition
            $classes = 'small-12 large-4 column soldPosition box';
            echo '<div class="' . $classes . '">';
            include("$the_ad");
            echo '</div>';

            break;

        default:

    endswitch;
    return NULL;
}