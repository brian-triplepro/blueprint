<?php
/**
 * Register 'Cases' custom post type (Nederlands)
 *
 * @package Blueprint
 */

if ( ! function_exists( 'blueprint_register_cases_cpt' ) ) {
    add_action( 'init', 'blueprint_register_cases_cpt' );

    function blueprint_register_cases_cpt() {
        $labels = array(
            'name'                  => __( 'Cases', 'blueprint' ),
            'singular_name'         => __( 'Case', 'blueprint' ),
            'menu_name'             => __( 'Cases', 'blueprint' ),
            'name_admin_bar'        => __( 'Case', 'blueprint' ),
            'add_new'               => __( 'Toevoegen', 'blueprint' ),
            'add_new_item'          => __( 'Nieuwe case toevoegen', 'blueprint' ),
            'new_item'              => __( 'Nieuwe case', 'blueprint' ),
            'edit_item'             => __( 'Bewerk case', 'blueprint' ),
            'view_item'             => __( 'Bekijk case', 'blueprint' ),
            'all_items'             => __( 'Alle cases', 'blueprint' ),
            'search_items'          => __( 'Zoek cases', 'blueprint' ),
            'parent_item_colon'     => __( 'Bovenliggende case:', 'blueprint' ),
            'not_found'             => __( 'Geen cases gevonden.', 'blueprint' ),
            'not_found_in_trash'    => __( 'Geen cases in de prullenbak gevonden.', 'blueprint' ),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'has_archive'        => false,
            'show_in_rest'       => true,
            'supports'           => array( 'title', 'editor', 'thumbnail', 'excerpt', 'revisions', 'author', 'custom-fields' ),
            'menu_icon'          => 'dashicons-portfolio',
            'capability_type'    => 'post',
        );

        register_post_type( 'cases', $args );
    }
}

add_action( 'after_switch_theme', 'blueprint_flush_rewrite_rules_for_cases' );
function blueprint_flush_rewrite_rules_for_cases() {

    if ( function_exists( 'blueprint_register_cases_cpt' ) ) {
        blueprint_register_cases_cpt();
    }
    flush_rewrite_rules();
}
