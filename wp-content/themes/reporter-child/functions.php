<?php

function the_category_filter($thelist,$separator=' ') {
    if(!defined('WP_ADMIN')) {
        //Category IDs to exclude
        //Excludes Uncategorized, Top Stories
        $exclude = array(1, 91);

        $exclude2 = array();
        foreach($exclude as $c) {
            $exclude2[] = get_cat_name($c);
        }

        $cats = explode($separator,$thelist);
        $newlist = array();
        foreach($cats as $cat) {
            $catname = trim(strip_tags($cat));
            if(!in_array($catname,$exclude2))
                $newlist[] = $cat;
        }
        return implode($separator,$newlist);
    } else {
        return $thelist;
    }
}
add_filter('the_category','the_category_filter', 10, 2);