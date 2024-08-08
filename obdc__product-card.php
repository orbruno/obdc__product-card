<?php
/**
 * Plugin Name: Product Card for Turri.cr
 * Plugin URI:  orlandobruno.com
 * Author:      Orlando Bruno
 * Author URI:  orlandobruno.com
 * Description: This plugin is a custom product card for the OBDC website.
 * Version:     0.1.0
 * License:     GPL-2.0+
 * License URL: http://www.gnu.org/licenses/gpl-2.0.txt
 * text-domain: prefix-plugin-name
*/

// function to create add to cart component
require_once plugin_dir_path(__FILE__) . 'src/obdc__add-to-card-component.php';

// function to generates grid of product cards
require_once plugin_dir_path(__FILE__) . 'src/obdc__product-card-grid.php';

// load js file to update form action on product card
function enqueue_obdc_product_card_update_form_action_script() {
    wp_enqueue_script(
        'obdc-product-card-update-form-action', // Handle
        plugins_url('includes/js/obdc__product-card__update-form-action.js', __FILE__), // Correct URL to js file
        array(), // Dependencies
        '1.0.0', // Version number
        true, // Load script in footer
        array( 'defer' => true ) // Add defer attribute
    );
}

add_action('wp_enqueue_scripts', 'enqueue_obdc_product_card_update_form_action_script');

?>