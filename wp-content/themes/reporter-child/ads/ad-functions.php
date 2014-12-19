<?php

add_action( 'wp_enqueue_scripts', function() {
    // Double Click Ads
    wp_enqueue_script(
        'google-ads',
        CHILDURI . '/ads/google-ads.js',
        array( 'jquery' )
    );
});

/**
 * The Ad Position
 *
 * Returns the ad position based on a few parameters.
 *
 * @param string $name Name of position. E.g. "Leaderboard".
 * @param string $pos Position of ad. E.g. "Top".
 * @param bool $inline Display box inline or send to a variable. Defaults to false
 */
function the_ad_position($name, $pos, $container = TRUE) {

    $name = strtolower($name);
    $pos = strtolower($pos);

    $position = 'ads/' . $name . '-' . $pos;

    if ($container === FALSE) {
        get_template_part("$position");
        return FALSE;
    }

    $the_ad = CHILDDIR . '/' . $position . '.php';

    switch ($name):
        case 'leaderboard':
            $classes = 'large-12 column soldPosition leaderboard clearfix';
            echo '<div class="' . $classes . '">';
            include("$the_ad");
            echo '</div>';

            break;

        case 'box':
            // large-4 column right-rail soldPosition
            $classes = 'column soldPosition box';
            echo '<div class="' . $classes . '">';
            include("$the_ad");
            echo '</div>';

            break;

        case 'tower':
            // large-4 column right-rail soldPosition
            $classes = 'column soldPosition tower';
            echo '<div class="' . $classes . '">';
            include("$the_ad");
            echo '</div>';

            break;

        default:

    endswitch;
    return NULL;
}