<?php
/**
 * Register 'Medewerkers' custom post type (Nederlands)
 *
 * @package Blueprint
 */

if ( ! function_exists( 'blueprint_registe_medewerkers_cpt' ) ) {
    add_action( 'init', 'blueprint_register_medewerkers_cpt' );

    function blueprint_register_medewerkers_cpt() {
        $labels = array(
            'name'                  => __( 'Medewerkers', 'blueprint' ),
            'singular_name'         => __( 'Medewerker', 'blueprint' ),
            'menu_name'             => __( 'Medewerkers', 'blueprint' ),
            'name_admin_bar'        => __( 'Medewerker', 'blueprint' ),
            'add_new'               => __( 'Toevoegen', 'blueprint' ),
            'add_new_item'          => __( 'Nieuwe Medewerker toevoegen', 'blueprint' ),
            'new_item'              => __( 'Nieuwe Medewerker', 'blueprint' ),
            'edit_item'             => __( 'Bewerk Medewerker', 'blueprint' ),
            'view_item'             => __( 'Bekijk Medewerker', 'blueprint' ),
            'all_items'             => __( 'Alle Medewerkers', 'blueprint' ),
            'search_items'          => __( 'Zoek Medewerkers', 'blueprint' ),
            'parent_item_colon'     => __( 'Bovenliggende Medewerker:', 'blueprint' ),
            'not_found'             => __( 'Geen Medewerkers gevonden.', 'blueprint' ),
            'not_found_in_trash'    => __( 'Geen Medewerkers in de prullenbak gevonden.', 'blueprint' ),
        );

        $args = array(
            'labels'             => $labels,
            'public'             => true,
            'has_archive'        => false,
            'show_in_rest'       => true,
            'supports'           => array( 'title', 'thumbnail' ),
            'menu_icon'          => 'dashicons-portfolio',
            'capability_type'    => 'post',
        );

        register_post_type( 'medewerkers', $args );
        // register afdeling taxonomy for departments
        $tax_labels = array(
            'name'              => __( 'Afdelingen', 'blueprint' ),
            'singular_name'     => __( 'Afdeling', 'blueprint' ),
            'search_items'      => __( 'Zoek Afdelingen', 'blueprint' ),
            'all_items'         => __( 'Alle Afdelingen', 'blueprint' ),
            'parent_item'       => __( 'Bovenliggende Afdeling', 'blueprint' ),
            'parent_item_colon' => __( 'Bovenliggende Afdeling:', 'blueprint' ),
            'edit_item'         => __( 'Bewerk Afdeling', 'blueprint' ),
            'update_item'       => __( 'Update Afdeling', 'blueprint' ),
            'add_new_item'      => __( 'Nieuwe Afdeling toevoegen', 'blueprint' ),
            'new_item_name'     => __( 'Nieuwe Afdeling naam', 'blueprint' ),
            'menu_name'         => __( 'Afdeling', 'blueprint' ),
        );

        $tax_args = array(
            'hierarchical'      => true,
            'labels'            => $tax_labels,
            'show_ui'           => true,
            'show_in_rest'      => true,
            'show_admin_column' => true,
            'rewrite'           => array( 'slug' => 'afdeling' ),
        );
        register_taxonomy( 'afdeling', array( 'medewerkers' ), $tax_args );
    }
}

add_action( 'after_switch_theme', 'blueprint_flush_rewrite_rules_for_medewerkers' );
function blueprint_flush_rewrite_rules_for_medewerkers() {

    if ( function_exists( 'blueprint_register_Medewerkers_cpt' ) ) {
        blueprint_register_Medewerkers_cpt();
    }
    // taxonomy is registered inside the CPT function so no separate call needed
    flush_rewrite_rules();
}
