<?php
if ( ! defined( 'ABSPATH' ) ) exit;

function rsl_scf_add_json_load_point( $paths ) {
    return RSL_PLUGIN_DIR . 'fields/scf-json';
}

function rsl_scf_set_json_save_point( $path ) {
    return RSL_PLUGIN_DIR . 'fields/scf-json';
}

function rsl_scf_register_hooks() {
    add_filter( 'acf/settings/load_json', 'rsl_scf_add_json_load_point' );
    add_filter( 'acf/settings/save_json', 'rsl_scf_set_json_save_point' );
}

// Register immediately (or call manually where needed)
rsl_scf_register_hooks();