<?php
/**
 * Post Authors
 *
 * 1. Checks for certain author names and returns nothing for
 * Beverage Dynamics and Digital
 *
 * 2. Checks for Custom Author field and displays author
 * 3. If no custom author, displays Wordpress default.
 * 4. Adds "By " if missing.
 */

// checks for "By" at beginning of author name
function prependByAuthor($author) {
    if ( mb_substr($author, 0, 3) != 'By ' ) {
        $author = 'By ' . mb_substr($author, 3);
    }
    return $author;
}

$authorId = get_the_author_meta( 'ID' );
// quick author check to filter "Digital" and "Beverage Dynamics"
if ( get_field('author_name') == NULL && ($authorId === 1 || $authorId === 16) ) {
    return false;
}

// Let's get the author's name
// First we check if the custom field is set. If not, we'll use the WordPress entry
$author = ( get_field('author_name') != NULL ? get_field('author_name') : get_the_author_meta( 'display_name' ) );

/*
 * Displays link to author if in database, otherwise if there's a custom name,
 * it will display the custom name.
 */
if ( $author == get_the_author_meta('display_name') ) {
    $authorPostsUrl = get_author_posts_url( $authorId );
    $author = '<a href="' . $authorPostsUrl . '">' . $author . '</a>';
    $author = 'By ' . $author;
} elseif ( $author == get_field('author_name') ) {
    $author = prependByAuthor($author);
}
// if we have something to work with, display it on page. Otherwise, panic
if ( $author !== NULL ):
// a little markup for the content piece
$beforeAuthor = '<span class="entry-author">';
$afterAuthor = '</span>';
// send it to the page
echo $beforeAuthor . $author . $afterAuthor;
// closed it out
endif;