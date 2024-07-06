<?php
function func_variable_product_add_cart($product_id) {
    // Use global product if no product ID is provided
    global $product;
    if ($product_id) {
        $product = wc_get_product($product_id);
    }
    if ( $product->is_type( 'variable' ) ) {
        $available_variations = $product->get_available_variations();
        $attributes = $product->get_variation_attributes();
        $selected_attributes = $product->get_default_attributes();

        // Get product categories
        $categories = get_the_terms($product->get_id(), 'product_cat');
        // Check if product has categories
        if(!empty($categories)){
            // Let's just get the first category
            $product_category = $categories[0]->name;
        } else {
            $product_category = "Uncategorized";
        }
        $producer_id = get_associated_productor_id($product->get_id());
        $producer_name = get_the_title($producer_id); // Replace this line with your method if it's different
        $escaped_producer_name = esc_attr($producer_name);

        ?>
        <form action="<?php echo esc_url( $product->add_to_cart_url() ); ?>" 
            class="variations_form cart flex--col gap--xs" 
            method="post" 
            data-product-name="<?php echo esc_attr( $product->get_name() ); ?>"
            data-product-category="<?php echo esc_attr($product_category); ?>"
            data-producer_name="<?php echo $escaped_producer_name; ?>">
            <div class="add-to-cart-button__form-wrapper--variable">
                <span class="product-card__price"><?php echo $product->get_price_html(); ?></span>
                <select name="variation_id" class="variation-id">
                    <?php
                    echo '<option value="">Seleccionar variante</option>';
                    foreach ( $available_variations as $variation ) {
                        $variation_id = $variation['variation_id'];
                        $sku = get_post_meta($variation_id, '_sku', true);  // Retrieve SKU
                        $item_variant = implode(', ', $variation['attributes']); // Assemble item variant from variation attributes
                        $price = $variation['display_price']; // Retrieve price
                        echo '<option value="' . $variation_id . '" data-sku="' . $sku . '" data-item-variant="' . $item_variant . '" data-price="' . $price . '">' . $item_variant . ' - ' . wc_price($price) . '</option>';
                    }
                    ?>
                </select>                
            </div>

            <?php
            echo '<input type="hidden" name="product_id" class="product-id-input" value="'.$product->get_id().'">';
            ?>
            <button type="submit" class="btn--secondary wp-element-button product_type_variable add_to_cart_button ajax_add_to_cart" style="background-color: var(--secondary); border: 1px solid var(--secondary);">Agregar</button>
        </form>
        <?php
    } else {
    ?>
        <div class="add-to-cart-button__form-wrapper--simple"><?php echo $product->get_price_html(); ?></div>
    <?php
        woocommerce_template_loop_add_to_cart();

    }
}
add_shortcode( 'variable-product-add-cart', 'func_variable_product_add_cart' );
?>