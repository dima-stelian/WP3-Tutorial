<?php
// Template Name: Products Listing

get_header();

?>
<div id="container">
<div id="content" role="main">

<h1> Basic Product Listing </h1>

<div id="Products" style="padding:10px;">
<?php
$loop = new WP_Query( array( 'post_type' => 'product', 'posts_per_page' => 10 ) );
while ( $loop->have_posts() ) : $loop->the_post();

    $meta = get_post_meta( get_the_ID(), '_product_meta');

    ?>
        <div class="product" style="border:1px solid #CCC; margin:1px; padding:10px; float:left; width:180px; height:200px;">
            <h2>
                <a href="<?php the_permalink();?>">
                    <?php the_title(); ?>
                </a>
            </h2>

            <?php the_post_thumbnail(null, array('style'=>'float:left; width:64px; height:auto; padding:5px;')); ?>

            <div class="entry-content">
                <?php the_content(); ?>
            </div>

            <div>
                Price: <strong><?php  echo $meta[0]['product_price']?> USD</strong>
            </div>
        </div>

     <?php

endwhile;

?>

<div class="clear"></div>
</div>

</div>

</div>

<?php 
get_sidebar();
get_footer();
?>
