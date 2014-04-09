<?php

/**
 * CSS Auto Versioning
 *
 * Given a file, i.e. /css/base.css, replaces it with a string containing the
 * file's mtime, i.e. /css/base.1221534296.css.
 *
 * @param string $file The file to be loaded.  Must be an absolute path (i.e.
 *      starting with slash).
 * @param boolean $run Runs function if true, otherwise returns base stylesheet
 * @return string $newFileName Returns new stylesheet directory if completed,
 *      otherwise will return the current stylesheet or root stylesheet, depending
 *      on conditions.
 */
function auto_version_css($file) {
    // turns function on and off.
    if ( !VERSIONCSS ) {
        return $file;
    }
    clearstatcache();
    $update_option = 'child_stylesheet_modified_time';
    $lastModifiedTime = get_option($update_option, "Time Not Set");
    $currentStylesheet = get_option($file, 'Stylesheet Not Set');

    $modifiedTime = filemtime(CHILDDIR . $file);
    /*
     * Checks what the last modified time was
     * Checks:
     * 1. Equal modified time           AND
     * 2. Stylesheet option is set      AND
     * 3. A time has been set to query  AND
     * 4. The new stylesheet exists
     *
     * If it's all true, returns the modified file.
     */
    if ( $lastModifiedTime == $modifiedTime
        && $currentStylesheet !== "Stylesheet Not Set"
        && $lastModifiedTime !== "Time Not Set"
        && file_exists(CHILDDIR . $currentStylesheet) ) {
        return $currentStylesheet;
    }
    $stylesheet = $file;
    /*
     * Running the function
     * If all of those aren't met, it's time to create a new stylesheet
     *
     * First we write the new file name
     * Create a var to hold base directory info
     *
     * Then, we check if everything is writable. If it is, we continue.
     * Otherwise, we return the un-cached file.
     *
     */
    // style.css
    $newStylesheetName = substr(strrchr($stylesheet, "/"), 1);
    // style.timestamp.css
    $newFileName = substr($newStylesheetName, 0, -4) . '.' . $modifiedTime . '.css';
    // "/css/" from "/css/style.css"
    $newStyleSheetDirectory = substr($stylesheet, 0, (strlen($stylesheet)-strlen($newStylesheetName)));
    /*
     * Add new file location.
     * Full directory location of new file.
     * /dir/user/www/etc/etc/wp-content/etc/etc/style.css
     */
    $newStyleSheet = CHILDDIR . $newStyleSheetDirectory . $newFileName;
    /*
     * Add file to folder
     * If it's not writable or files, it'll return the base stylesheet.
     * If we can't write, then chances are it wasn't written before.
     */
    if (is_writable(CHILDDIR . $newStyleSheetDirectory)) {
        // check if the file was created
        if (!$handle = fopen($newStyleSheet, 'w')) {
            return $file;
        }
        $oldStylesheet = file_get_contents(CHILDDIR . $stylesheet); // data
        // Write data to new stylesheet.
        if (fwrite($handle, $oldStylesheet) === FALSE) {
            return $file;
        }
        // Success, wrote data to file new stylesheet;
        fclose($handle);
    } else {
        return $file;
    }
    /*
     * Update Database
     * Everything worked and now it's time to update the database and return the new file
     * and then delete the old file.
     */

    $newFileName = $newStyleSheetDirectory . $newFileName;
    update_option($update_option, $modifiedTime);
    update_option($file, $newFileName);
    // And delete the old stylesheet
    if ($currentStylesheet !== "Stylesheet Not Set" ) {
        unlink(CHILDDIR . $currentStylesheet);
    }
    if (!file_exists(CHILDDIR . $newFileName)) {
        return $stylesheet;
    }
    return $newFileName;
}

/***********************************
 *
 * SCRIPTS AND STYLE REGISTRATION
 *
 ***********************************/
add_action( 'wp_enqueue_scripts', function() {

    // Child Stylesheet
    $fileName = auto_version_css( '/assets/beverage-dynamics.css' );
    wp_register_style( 'BDX-Styles', CHILDURI . $fileName, array('theme-style') );
    wp_enqueue_style( 'BDX-Styles' );

    $adminFileName = '/assets/beverage-admin.css';
    wp_enqueue_style( 'BDX-Admin', CHILDURI . $adminFileName );

    // Extra JS
    wp_enqueue_script(
        'child-js',
        CHILDURI . '/assets/beverage-scripts.js',
        array('jquery', 'foundation'),
        '',
        TRUE
    );

    /*// Maps
    wp_enqueue_script(
        'wine-map',
        CHILDURI . '/widgets/wine-map/wi.js',
        array('jquery', 'foundation'),
        '',
        TRUE
    );*/

});

add_action( 'admin_head', function() {

    // Child Stylesheet
    $adminFileName = auto_version_css( '/assets/beverage-admin.css' );
    wp_enqueue_style( 'BDX-Admin', CHILDURI . $adminFileName );

});