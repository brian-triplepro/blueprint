<?php
/**
 * The main template file
 *
 * @package Blueprint
 */

get_header();
if ( have_posts() ) {
    while ( have_posts() ) {
        the_post();
        the_title( '<h1>', '</h1>' );
        the_content();
    }
} else {
    echo '<p>No posts found.</p>';
}
get_footer();
