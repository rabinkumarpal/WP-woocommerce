<?php
$args = array(
    'post_type'   => 'product',
    'post_status' => 'publish',
    'posts_per_page' => -1,
);

$products = new WP_Query($args);

if ($products->have_posts()) {
    while ($products->have_posts()) {
        $products->the_post();
        global $product;

        $product_id = $product->get_id();

        $reviews = get_comments(array(
            'post_id' => $product_id,
            'status'  => 'approve',
        ));

        foreach ($reviews as $review) {
            // Output review data as needed
            echo 'Review for product ' . get_the_title() . ':';
            echo 'Author: ' . $review->comment_author . '<br>';
            echo 'Rating: ' . get_comment_meta($review->comment_ID, 'rating', true) . '<br>';
            echo 'Comment: ' . $review->comment_content . '<br>';
            echo 'Date: ' . $review->comment_date . '<br>';
            echo '--------------------<br>';
        }
    }
    wp_reset_postdata();
} else {
    echo 'No products found';
}
?>
