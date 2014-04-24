<?php

/*
  Copyright (C) <2014>  Vasyl Martyniuk <martyniuk.vasyl@gmail.com>

  This program is free software: you can redistribute it and/or modify
  it under the terms of the GNU General Public License as published by
  the Free Software Foundation, either version 3 of the License, or
  (at your option) any later version.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program.  If not, see <http://www.gnu.org/licenses/>.

 */

@set_time_limit(300);

$basedir = dirname(__FILE__);

if (file_exists($basedir . '/wp-config.php')) {
    require $basedir . '/wp-config.php';
    require ABSPATH . 'wp-admin/includes/upgrade.php';
} else {
    echo 'Please insert uninstall.php in the same folder where wp-config.php locates';
    exit; //no more action
}

global $wpdb, $wp_user_roles, $blog_id;

$set = 'a:62:{s:13:"switch_themes";b:1;s:11:"edit_themes";b:1;s:16:"activate_plugins";b:1;s:12:"edit_plugins";b:1;s:10:"edit_users";b:1;s:10:"edit_files";b:1;s:14:"manage_options";b:1;s:17:"moderate_comments";b:1;s:17:"manage_categories";b:1;s:12:"manage_links";b:1;s:12:"upload_files";b:1;s:6:"import";b:1;s:15:"unfiltered_html";b:1;s:10:"edit_posts";b:1;s:17:"edit_others_posts";b:1;s:20:"edit_published_posts";b:1;s:13:"publish_posts";b:1;s:10:"edit_pages";b:1;s:4:"read";b:1;s:8:"level_10";b:1;s:7:"level_9";b:1;s:7:"level_8";b:1;s:7:"level_7";b:1;s:7:"level_6";b:1;s:7:"level_5";b:1;s:7:"level_4";b:1;s:7:"level_3";b:1;s:7:"level_2";b:1;s:7:"level_1";b:1;s:7:"level_0";b:1;s:17:"edit_others_pages";b:1;s:20:"edit_published_pages";b:1;s:13:"publish_pages";b:1;s:12:"delete_pages";b:1;s:19:"delete_others_pages";b:1;s:22:"delete_published_pages";b:1;s:12:"delete_posts";b:1;s:19:"delete_others_posts";b:1;s:22:"delete_published_posts";b:1;s:20:"delete_private_posts";b:1;s:18:"edit_private_posts";b:1;s:18:"read_private_posts";b:1;s:20:"delete_private_pages";b:1;s:18:"edit_private_pages";b:1;s:18:"read_private_pages";b:1;s:12:"delete_users";b:1;s:12:"create_users";b:1;s:17:"unfiltered_upload";b:1;s:14:"edit_dashboard";b:1;s:14:"update_plugins";b:1;s:14:"delete_plugins";b:1;s:15:"install_plugins";b:1;s:13:"update_themes";b:1;s:14:"install_themes";b:1;s:11:"update_core";b:1;s:10:"list_users";b:1;s:12:"remove_users";b:1;s:9:"add_users";b:1;s:13:"promote_users";b:1;s:18:"edit_theme_options";b:1;s:13:"delete_themes";b:1;s:6:"export";b:1;}';
$default = unserialize($set);

/**
 * Run the fix procedure
 *
 * @global array $default
 *
 * @return void
 *
 */
function runFix()
{
    global $default;

    $roles = new WP_Roles;
    $capabilities = $default;

    foreach ($roles->role_objects as $role) {
        $capabilities = array_merge($capabilities, $role->capabilities);
    }

    if ($admin = $roles->get_role('administrator')) {
        foreach ($capabilities as $cap => $dummy) {
            $admin->add_cap($cap);
        }
    } else {
        $roles->add_role('administrator', 'Administrator', $capabilities);
    }
}

if (is_multisite()) {
    //get all sites first and iterate through each
    $query = 'SELECT blog_id FROM ' . $wpdb->blogs;
    $blog_list = $wpdb->get_results($query);
    if (is_array($blog_list)) {
        foreach ($blog_list as $blog) {
            //reset roles & blog id
            $wp_user_roles = null;
            $wpdb->set_blog_id($blog_id);
            runFix();
        }
    }
} else {
    runFix();
}
echo "The Process Completed Successfully!";