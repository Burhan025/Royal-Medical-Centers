<?php get_header(); 
$termChild = get_the_terms(get_the_ID(), 'gallery_categories');
$id = $termChild[0]->parent;
$termParent = get_term($id, $termChild[0]->taxonomy);
$termParentSlug = $termParent->slug;
$termChildSlug = $termChild[0]->slug;
$backLink = site_url().'/gallery/'.$termParentSlug.'/'.$termChildSlug.'/';

// $args = array(
// 'post_type' => 'gallery',
// 'tax_query' => array(
//     array(
//         'taxonomy' => $termChild[0]->taxonomy,
//         'terms'    => $termChild[0]->term_id
//         )
//     ),
// );// end args
// $getRelatedGalleries = new WP_Query( $args);
?>

<div class="container">
	<div class="row">
		<div class="fl-content">
            <div class="items-before-carousel">
                <div class="back-btn">
                    <i class='icon-chevron-left icon-white'><</i> <?php echo '<a href="'.$backLink.'">Back</a>'; ?>
                </div>
                <div class="single-gallery-breadcrumb">
                    <?php echo do_shortcode('[wpseo_breadcrumb]'); ?>
                </div>
                <div class="single-gallery-title">
                    <?php echo the_title(); ?>
                </div>
                <div class="gallery-sebcategories">
                    <?php
                     //echo do_shortcode('[gallerySubCategories]');?>
                     <a href="<?php echo $backLink; ?>"> <i class='icon-chevron-left icon-white'></i> Back to <?php echo $termChildSlug ?></a>

                </div>
            </div>
            <div class="caro-top">
                <div class="owl-carousel" id="single-gallery-main-slider">
                    <?php if( get_field('sildes', get_the_ID()) ):?>
                        <?php while( the_repeater_field('sildes', get_the_ID()) ): ?>
                        <div>
                            <img src="<?php echo get_sub_field('slide')['sizes']['large'] ?>" />
                            
                        </div>
                        <?php endwhile; ?>
                    <?php endif;?>
                </div>
                
                <div class="owl-carousel" id="single-gallery-thumb-slider">
                    <?php if( get_field('sildes', get_the_ID()) ):?>
                        <?php while( the_repeater_field('sildes', get_the_ID()) ): ?>
                        <div>
                            <img src="<?php echo get_sub_field('slide')['sizes']['medium'] ?>" />
                        </div>
                        <?php endwhile; ?>
                    <?php endif;?>
                </div>

            </div>
            <div class="single-gallery-paragraph">
                <?php echo the_content(); ?> 
            </div>
            <div class="related-gallery">
                <?php echo do_shortcode('[getGalleryPosts slider=true]'); ?>
                <?php?>
            </div>
		</div>
	</div>
</div>

<?php get_footer(); ?>
