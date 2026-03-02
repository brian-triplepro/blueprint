<?php
get_header();
?>

<?php if ( have_posts() ) :
  while ( have_posts() ) : the_post(); ?>
    <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
      <div class="entry-content">
        <?php the_content(); ?>
      </div>
      <?php
      /* if ( comments_open() || get_comments_number() ) {
        comments_template();
      } */
      ?>
    </article>
  <?php endwhile;
else :
  echo '<p>No content found.</p>';
endif;
?>

<?php
get_footer();
?>