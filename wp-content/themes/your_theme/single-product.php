<?php
// Template Name: Products Listing

get_header();

?>

<div id="container">
<div id="content" role="main">

<div id="Products" style="padding:10px;">
<?php

while ( have_posts() ) : the_post();

    $meta = get_post_meta( get_the_ID(), '_product_meta');

    ?>
        <h1>
            <a href="<?php the_permalink();?>">
                <?php the_title(); ?>
            </a>
        </h1>

        
            

            <?php the_post_thumbnail(null, array('style'=>'float:left; padding:5px;')); ?>

            <div class="entry-content">
                <?php the_content(); ?>
            </div>

            <div>
                Price: <strong><?php  echo $meta[0]['product_price']?> USD</strong>
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
