<?php

get_header(); ?>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/owl.carousel.min.js"></script>
<div id="main-content" class="main-content container">

    <div id="primary" class="content-area">
        <div id="content" class="site-content" role="main">
            <?php
                // Start the Loop.
                while ( have_posts() ) : the_post(); ?>
                <div class="row">
                <div class="col-md-6 col-xs-12 left-s">
                    <h2><?php the_title(); 
                   
                    ?> </h2>
                    <p><?php the_content(); ?> </p>
                </div>
                <div class="col-md-6 col-xs-12 right-s">
                <div id="owl-demo" class="owl-carousel owl-theme">
 
 <div class="item"><img src="https://localhost/preprod/wp-content/uploads/2019/08/1-768x461.jpg" alt="The Last of us"></div>
 <div class="item"><img src="https://localhost/preprod/wp-content/uploads/2019/08/1-768x461.jpg" alt="GTA V"></div>
 <div class="item"><img src="https://localhost/preprod/wp-content/uploads/2019/08/1-768x461.jpg" alt="Mirror Edge"></div>

</div>
                </div>
                </div>

             <?php   endwhile;
            ?>
        </div><!-- #content -->
    </div><!-- #primary -->
</div><!-- #main-content -->
<script>

 
 $("#owl-demo").owlCarousel({
     navigation : true, // Show next and prev buttons
     slideSpeed : 300,
     paginationSpeed : 400,
     singleItem:true,
     items : 1, 

 });


</script>



<?php

get_footer();
?>
<style>
.col-md-6.col-xs-12 {
    width: 50%;
    display: inline;

}
#owl-demo .item img{
    display: block;
    width: 100%;
    height: 300px;
    padding-left: 30px;
}
.owl-carousel .owl-dots.disabled, .owl-carousel .owl-nav.disabled {

display: block;
float: right;
font-size: 25px;

}
.owl-next {

margin-left: 30px;

}
.content-area{
    width: 100%;
}
.left-s{
    float: left;
}
.right-s{
    float: left;
}

.content-area {

width: 100%;
border: 1px solid #f7f4f4;
padding: 15px;
margin-top: 50px;
margin-bottom: 50px;

}
</style>
