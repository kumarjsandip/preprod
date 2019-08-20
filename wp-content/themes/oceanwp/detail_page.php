
<?php
/*Template Name: Detail Page */

get_header(); ?>

	

	<div id="content-wrap" class="container clr">
		<div id="primary" class="content-area clr">
			<div id="content" class="site-content clr">
                <?php the_content(); ?>
            <div class="col-lg-12">
               <?php 
               $paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
               $contractor_query = new WP_Query(array(
                  'post_type' => 'contractors',
                  'order_by' => 'DATE',
                  'order'  =>'ASC',
                  'paged' => $paged,
                  'posts_per_page' => 1,
               ));
               while($contractor_query->have_posts()):
                  $contractor_query->the_post();
                
               ?>
   <div class="card">
      <div class="card-body cardcontainer">
         <div class="card-two">
           
            <div class="row">
               <div class="col-lg-4 d-left">
                  <div class="" style="text-align: center;">
                  <?php 
                  $profile_image = get_the_post_thumbnail_url();
                  if(!empty($profile_image)){
                  ?>
                     <img style="border-radius: 75%; width:300px; height:327px;" src="<?php echo $profile_image; ?>">
                  <?php }else { ?>
                     <img style="border-radius: 75%; width:300px; height:327px;" src="https://www.brookfield.edu.pk/wp-content/uploads/2016/10/people-profile-dummy-219x227-300x300.jpg">
                  <?php } ?>
                  </div>
                  <div class="qcontrol" style="text-align: center;">
                     <div class="margin-bottom-20 margin-top-20">
                     <h2><?php the_field('firstname'); the_field('lastname'); ?></h2>
                     </div>
                  </div>
               </div>
               <div class="col-lg-8 d-right">
                  <table class="table table-hover table-profile">
                     <tbody>
                     <tr>
                           <td><strong>Salutaion : </strong></td>
                           <td><?php the_field('salutaion'); ?></td>
                        </tr>
                        <tr>
                           <td><strong>First Name : </strong></td>
                           <td><?php the_field('firstname'); ?></td>
                        </tr>
                        <tr>
                           <td><strong>Last Name : </strong></td>
                           <td><?php the_field('lastname'); ?></td>
                        </tr>
                        <tr>
                           <td><strong>Email : </strong></td>
                           <td><?php the_field('email'); ?></td>
                        </tr>
                        <tr>
                           <td><strong>Contact Number : </strong></td>
                           <td><?php the_field('phone_details'); ?></td>
                        </tr>
                        <tr>
                           <td><strong>Tradename : </strong></td>
                           <td><?php the_field('tradename'); ?></td>
                        </tr>
                        <tr>
                           <td><strong>Speciality : </strong></td>
                           <td><?php the_field('speciality'); ?></td>
                        </tr>
                        <tr>
                           <td><strong>Salesforce : </strong></td>
                           <td><?php the_field('salesforce'); ?></td>
                        </tr>
                        <tr>
                           <td><strong>4-5 pics to update : </strong></td>
                           <td><?php the_field('4-5_pics_to_update'); ?></td>
                        </tr>
                      
                        <tr>
                           <td><strong>Description : </strong></td>
                           <td><?php the_field('description'); ?>
                           </td>
                        </tr>
                        
                        <tr>
                           <td><strong>Address :</strong></td>
                           <td> <span><?php the_field('street'); ?>, <?php the_field('city'); ?>, <?php the_field('zip_code'); ?>, <?php the_field('state'); ?>, <?php the_field('country'); ?></span>
                           
                           </td>
                        </tr>
                    
                     </tbody>
                  </table>
               </div>
            </div>
         </div>
      </div>
   </div>
              
</div>
			</div><!-- #content -->
		</div><!-- #primary -->
	</div><!-- #content-wrap -->
    <section class="detail-page-new elementor-element elementor-element-84efc31 elementor-section-full_width elementor-section-height-default elementor-section-height-default elementor-section elementor-top-section" data-id="84efc31" data-element_type="section" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
   <div class="elementor-container elementor-column-gap-wide">
      <div class="elementor-row">
      <?php $get_works =  get_field('work'); 
      if (!empty($get_works)) {
          foreach ($get_works as $work):
         ?>
         <div class="elementor-element elementor-element-281f2de elementor-column elementor-col-33 elementor-top-column" data-id="281f2de" data-element_type="column">
            <div class="elementor-column-wrap  elementor-element-populated">
               <div class="elementor-background-overlay"></div>
               <div class="elementor-widget-wrap">
                  <div class="elementor-element elementor-element-c222039 elementor-widget elementor-widget-text-editor" data-id="c222039" data-element_type="widget" data-widget_type="text-editor.default">
                     <div class="elementor-widget-container">
                        <div class="elementor-text-editor elementor-clearfix">
                           <?php
                              $work_image_url = get_the_post_thumbnail_url($work->ID, 'medium_large');
                           
          if (!empty($work_image_url)) {
              ?>
                          
                            <img style="height:350px;width:100%;" src="<?php echo $work_image_url; ?>"/>
                              <?php
          } else { ?>
                                 <img src="https://www.nextprojectx.com/wp-content/uploads/2019/02/flooring.jpg"/>

                              <?php } ?>
                            <h2><?php echo $work->post_title; ?></h2>
                           <p><?php echo substr($work->post_content,0,370).'.....'; ?></p>
                        </div>
                     </div>
                  </div>
                  <div class="elementor-element elementor-element-e61283b elementor-align-center elementor-widget elementor-widget-button" data-id="e61283b" data-element_type="widget" data-widget_type="button.default">
                     <div class="elementor-widget-container">
                        <div class="elementor-button-wrapper">
                           <a href="<?php echo get_site_url(); ?>/works/<?php echo $work->post_name ?>" class="elementor-button-link elementor-button elementor-size-sm" role="button">
                           <span class="elementor-button-content-wrapper">
                           <span class="elementor-button-text">View Detail</span>
                           </span>
                           </a>
                        </div>
                     </div>
                  </div>
                  <div class="elementor-element elementor-element-58982cb elementor-widget elementor-widget-spacer" data-id="58982cb" data-element_type="widget" data-widget_type="spacer.default">
                     <div class="elementor-widget-container">
                        <div class="elementor-spacer">
                           <div class="elementor-spacer-inner"></div>
                        </div>
                     </div>
                  </div>
               </div>
            </div>
         </div>
                  <?php endforeach;
      } ?>
         
      </div>
   </div>
</section>
<?php endwhile;  ?>
<div class="col-md-12 text-center" style="text-align: center;">
         <?php 
           $total_pages = wp_count_posts( 'contractors' )->publish;
           $posts_per_page = '1';
           $page_number_max = ceil($total_pages / $posts_per_page);
        
          if ($total_pages > 1){

            $current_page = max(1, get_query_var('paged'));
    
            echo paginate_links(array(
                'base' => get_pagenum_link(1) . '%_%',
                'format' => '/page/%#%',
                'current' => $current_page,
                'total' => $page_number_max,
                'prev_text'    => __('«'),
                'next_text'    => __('»'),
            ));
        }    
         ?>
         </div>
<style>
.col-lg-4.d-left {
width: 33.33%;
float: left;
}
.col-lg-8.d-right {
width: 66%;
float: left;
}
.detail-page-new p{
   text-align: justify;
}
.detail-page-new h2 {

text-align: center;
padding-top: 15px;
margin-bottom: 0px;

}
.card {

margin-top: 60px;

}
#main #content-wrap {

padding-top: 0;
padding-bottom: 50px;

}
</style>
<?php get_footer(); ?>