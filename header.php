<?php
/**
 * Package for displaying the header
 *
 * @package Blueprint
 * @since 0.0.1
 */
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="icon" href="<?php echo esc_url( get_template_directory_uri() . '/assets/images/favicon.ico' ); ?>">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>

    <div id="page" class="site">
        <?php get_template_part( 'template-parts/headers/header-1/header' ); ?>
        <main id="main" class="site-main">


