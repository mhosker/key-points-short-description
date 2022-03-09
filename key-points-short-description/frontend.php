<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

// ******************************************

// Key Points Frontend

// ******************************************

// Frontend HTML

function key_points_frontend_html(){

  $keypoints = get_post_custom_values('key_point');

  // If no key points are set then we return blank e.g dont show anything

  if ( ! $keypoints ) {
  	return;
  }

  // Otherwise we display some lovely HTML showing the key points in a bullet list

  echo '

  <div class="woocommerce-product-details__short-description product-key-points">
    <h4 class="product-key-points-title">Key Points</h4>
    <ul class="product-key-points-list">
        ';
        foreach ($keypoints as $keypoint) {
          echo '<li>'.$keypoint.'</li>';
        }
        echo '
    </ul>
  </div>

  ';

}

// Return empty for a blank short description

function woocommerce_template_single_excerpt() {
        return;
}

// Then we remove the short description

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );

// And add the new short description containing the key points

add_action( 'woocommerce_single_product_summary', 'key_points_frontend_html', 20 );

?>
