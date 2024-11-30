<?php
/**
 * Single Product Image
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.1
 */

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
    return;
}

?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flexslider/2.7.0/flexslider.css">
<?php

global $product;

$columns           = apply_filters( 'woocommerce_product_thumbnails_columns', 4 );
$post_thumbnail_id = $product->get_image_id();
$wrapper_classes   = apply_filters(
    'woocommerce_single_product_image_gallery_classes',
    array(
        'woocommerce-product-gallery',
        'woocommerce-product-gallery--' . ( $post_thumbnail_id ? 'with-images' : 'without-images' ),
        'woocommerce-product-gallery--columns-' . absint( $columns ),
        'images',
    )
);

$attachment_ids = $product->get_gallery_image_ids();

if ( has_post_thumbnail() ) { ?>
        <div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>" style="opacity: 0; transition: opacity .25s ease-in-out;">
            <figure>
                <section class="slider">
					<div id="slider" class="flexslider">
						<ul class="slides">
							<li>
								<img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="">
							</li>
							<?php 
							foreach ( $attachment_ids as $attachment_id ) {
								$image_url = wp_get_attachment_url($attachment_id);
							?>
								<li>
									<img src="<?php echo $image_url; ?>" />
								</li>
							<?php } ?>
						</ul>
					</div>
					<div id="carousel" class="flexslider">
						<ul class="slides">
						<li>
								<img src="<?php echo get_the_post_thumbnail_url(); ?>" alt="">
							</li>
						<?php 
                            foreach ( $attachment_ids as $attachment_id ) {
                                $image_url = wp_get_attachment_url($attachment_id);
                            ?>
                                <li>
                                    <img src="<?php echo $image_url; ?>" />
                                </li>
                            <?php } ?>
					</ul>
					</div>
                </section>
            </figure>
        </div>
<?php } ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flexslider/2.7.0/jquery.flexslider.js"></script>
<script type="text/javascript">
    jQuery(window).load(function(){
		jQuery('#carousel').flexslider({
			animation: "slide",
			controlNav: false,
			animationLoop: false,
			slideshow: false,
			touch: true,
			itemWidth: 233,
			itemMargin: 12,
			asNavFor: '#slider',
			minItems: 2, // use function to pull in initial value
      		maxItems: 2 // use function to pull in initial value
		});
		
		jQuery('#slider').flexslider({
			animation: "slide",
			controlNav: false,
			animationLoop: false,
			slideshow: false,
			touch: true,
			directionNav: false,
			smoothHeight: true,
			sync: "#carousel",
			start: function(){
                jQuery("#slider .flex-active-slide").zoom();
            },
            before: function(){
                jQuery('#slider .flex-active-slide').trigger('zoom.destroy');
            },
            after: function(){
                jQuery("#slider .flex-active-slide").zoom();
            }
		});
    });
</script>