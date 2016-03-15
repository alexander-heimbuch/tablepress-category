<?php
/**
 * WordPress plugin "TablePress Categories" main file, responsible for initiating the plugin
 *
 * @package TablePress Plugins
 * @author Alexander Heimbuch
 * @version 0.1
 */

/*
Plugin Name: TablePress Categories
Plugin URI: http://aktivstoff.de/
Description: Extend TablePress tables with the ability to group rows into categories
Version: 0.1
Author: Alexander Heimbuch
Author URI: http://aktivstoff.de
Author email: kontakt@aktivstoff.de
Text Domain: tablepress
Domain Path: /i18n
License: GPL 2
Donate URI: https://tablepress.org/donate/
*/

// Prohibit direct script loading.
defined( 'ABSPATH' ) || die( 'No direct script access allowed!' );

/**
 * Init TablePress_Category Shortcodes.
 */

/**
 * Class that contains the TablePress Row Filtering functionality
 * @author Tobias Bäthge
 * @since 1.0
 */
class TablePress_Category {

    const tableShortcode = 'category-table';
    const categoryShortcode = 'category';

    private $categories = array();
    private $table = '';

    public function __construct() {
        add_shortcode( TablePress_Category::tableShortcode, array(  $this, 'shortcode_table' ) );
        add_shortcode( TablePress_Category::categoryShortcode, array(  $this, 'shortcode_category_placeholder' ) );
        $this->enqueue_script();
    }

    private function enqueue_script () {
        $script = plugins_url( 'tablepress-categories.js', __FILE__ );
        $style = plugins_url( 'tablepress-categories.css', __FILE__ );

        wp_enqueue_script( 'tablepress-categories', $script, array( 'tablepress-datatables' ), false, true );
        wp_enqueue_style( 'tablepress-categories', $style);
    }

    public function shortcode_table( $atts, $content ) {
        $attributes = shortcode_atts( array(
            'id' => null
        ), $atts );

        if( !$attributes['id'] ) {
            return '';
        }

        if (strlen(content) > 0) {
            preg_match_all( '/'. get_shortcode_regex() .'/s', $content, $shortcodes);

            foreach( $shortcodes[3] as $shortcode ) {
                array_push( $this->categories, $this->shortcode_category( $shortcode ) );
            }
        }

        return $this->tablepress( $atts ) . $this->convert_to_javascript( $attributes['id'], $this->categories );
    }

    private function tablepress( $atts ) {
        $attributes = shortcode_atts( array(
            'id' => null
        ), $atts );

        $tablePress = 'table';

        foreach( $attributes as $key => $value ) {
            $tablePress .= " ${key}=\"${value}\"";
        }

        return do_shortcode("[" . $tablePress . "/]");
    }

    private function shortcode_category( $atts = '' ) {
        $attributes = shortcode_atts( array(
            'name' => '',
            'row_start' => null,
            'row_end' => null,
            'opened' => false
        ), shortcode_parse_atts( $atts ), 'category' );

        $attributes['row_start'] = intval($attributes['row_start']);
        $attributes['row_end'] = intval($attributes['row_end']);

        if ( $attributes['opened'] !== false ) {
            $attributes['opened'] = true;
        }

        return $attributes;
    }

    private function convert_to_javascript($id = null, $categories = array() ) {
        if (!$id) {
            return '';
        }

        return '<script>
            if (window.TABLE_CATEGORIES === undefined) {
                window.TABLE_CATEGORIES = {};
            }

            window.TABLE_CATEGORIES[' . $id . '] = {
                selector: "tablepress-' . $id . '",
                categories: JSON.parse(\'' . json_encode( $categories ) . '\')
            };
        </script>';
    }

    public function shortcode_category_placeholder( ) {
        return;
    }
}

new TablePress_Category();
