<?php
/**
 * Template for displaying pages
 *
 * @package Blueprint
 * @since 0.0.1
 */

get_header();
if ( have_posts() ) :
  while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <div class="entry-content">
        <?php the_content(); ?>
      </div>
      <?php
      if ( comments_open() || get_comments_number() ) {
        comments_template();
      }
      ?>
    </article>
  <?php endwhile;
else :
  echo '<p>No content found.</p>';
endif;
get_footer();
