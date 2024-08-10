<?php
// Function to get products associated with a producer
function get_products_by_producer($producer_id)
{
    // Query products associated with the producer using Meta Box Relationships API
    $products = MB_Relationships_API::get_connected([
        "id" => "producto-productor-relationship",
        "from" => $producer_id,
    ]);
    return $products;
}

// Function to display a product card
function display_product_card($product_id, $producer_id)
{
    // Ensure we have a valid product ID
    if (empty($product_id)) {
        return "";
    }
    // Ensure we have a valid producer
    if (empty($producer_id)) {
        // Query products associated with the producer using Meta Box Relationships API
        $connected = MB_Relationships_API::get_connected([
            "id" => "producto-productor-relationship",
            "to" => $product_id,
        ]);
        if (!empty($connected)) {
            $producer_id = $connected[0]->ID;
        }
    }

    // Get the product object
    $product = wc_get_product($product_id);

    // If no product object is found, return an empty string
    if (!$product) {
        return "";
    }

    // Get product data
    $product_name = $product->get_name();
    $product_weight = $product->get_attribute("peso"); // Assuming wc_peso_text returns the desired custom text
    $product_permalink = get_permalink($product->get_id());
    $product_image_url = wp_get_attachment_image_src(
        get_post_thumbnail_id($product->get_id()),
        "single-post-thumbnail"
    )[0];

    // Get the producer information using Meta Box Relationships API
    $producer_name = get_the_title($producer_id);
    $producer_permalink = get_permalink($producer_id);

    // Output the product card HTML
    ob_start();
    ?>
    <div class="product-card">
            <a href="<?php echo esc_url(
                $product_permalink
            ); ?>" class="product-card__image-wrapper">
                <img src="<?php echo esc_url(
                    $product_image_url
                ); ?>" alt="<?php echo esc_attr($product_name); ?>">
            </a>
            <h3 class="product-card__name text--bold text--s text--primary text--link">
                <a href="<?php echo esc_url($product_permalink); ?>">
                    <?php echo esc_html($product_name); ?>
                </a>
                <span class="product-card__weight"><?php echo $product_weight; ?></span>
            </h3>
            <p class="product-card__producer text--s text--link">
                <a href="<?php echo esc_url($producer_permalink); ?>">
                    <?php echo esc_html($producer_name); ?>
                </a>
            </p>
            <div class="product-card__add-to-cart-wrapper">
                <?php echo func_variable_product_add_cart($product->get_id()); ?>
            </div>
    </div>
    <?php return ob_get_clean();
}

// Shortcode function to display products associated with a producer in a repeater format
function producer_product_cards_shortcode($atts)
{
    // Ensure we are on a single productor page
    if (is_singular("productor")) {
        // Get the current productor ID
        $producer_id = get_the_ID();
        // Get the products associated with this producer
        $products = get_products_by_producer($producer_id);
    } else {
        $args = [
            "post_type" => "product",
            "posts_per_page" => -1, // Retrieve all products
        ];

        // Get products using WP_Query
        $query = new WP_Query($args);
        $products = [];

        // Loop through products and add them to the array
        if ($query->have_posts()) {
            while ($query->have_posts()) {
                $query->the_post();
                $products[] = get_post(); // Get the WP_Post object and add to array
            }
            wp_reset_postdata();
        }
    }

    // Output the containing div and the products
    ob_start();
    // Loop through each product and display its product card
    if (!empty($products)) {
        foreach ($products as $product) {
            echo display_product_card($product->ID, $producer_id);
        }
    } else {
        echo "<p>No products found for this producer.</p>";
    }
    return ob_get_clean();
}

add_shortcode('producer_product_cards', 'producer_product_cards_shortcode');
?>