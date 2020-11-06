
<div class="container home_page">
  <div class="row">
    <?php 
    $category_img_dir = base_url("assets/images/category_image/");
    foreach ($category_data as $category_array) 
    {
      $category_image = $category_array->category_image ? $category_array->category_image : "default.jpg";
      $category_image = $category_img_dir.$category_image;
      ?>

      <div class="col-md-4 col-xl-4 col-sm-6" >
        <div class="grid">
          <figure class="effect-ming">
            <img src="<?php echo xss_clean($category_image); ?>" alt="img09"/>
            <figcaption>
              <h2><?php echo xss_clean($category_array->category_title);  ?></h2>
              <a href="<?php echo  base_url('category/').$category_array->category_slug ?>"><?php echo lang('view_more') ?></a>
            </figcaption>     
          </figure>
        </div>
      </div>
      <?php 
    } ?>

    <!-- Latest Quiz Work Start --> 
      <div class="col-12">
        <div class="row">
          <div class="col-12 text-center"> <h2 class="heading"><?php echo lang('latest_quizes') ?></h2> <hr></div>
          <?php 
              $data['quiz_list_data'] = $latest_quiz_data;
              $this->load->view('quiz_data_list',$data); 
          ?>
        </div>
      </div>    
    <!-- Latest Quiz Work End -->

        <!-- Popular Quiz Work Start -->
        <div class="col-12">
          <div class="row">
            <div class="col-12 text-center"> <h2 class="heading"><?php echo lang('popular_quizes'); ?></h2><hr></div>
            <?php
              $data['quiz_list_data'] = $popular_quiz_data;
              $this->load->view('quiz_data_list',$data); 
            ?>

            </div>
          </div>
          <!-- Popular Quiz Work End -->
        </div>
      </div>

      <div class="container-fluid p-0">
        <!-- Testimonials Work Start -->
        <div class="col-12 text-center">
          <h2 class="text-center heading"><?php echo lang('front_testimonial'); ?></h2>
          <hr />
        </div>

        <section class="testimonial-section">
          <div class="testimonials testimonial-reel">
            <?php
            if($testimonial_data)
            {
              $testimonial_path  = base_url('/assets/images/testimonial/');
              foreach ($testimonial_data as  $testimonial_array) 
              { 
                $testimonial_profile = $testimonial_array->profile ? $testimonial_array->profile : 'default.png';
                ?>
                <div class="testimonial">
                  <p>“<?php echo strip_tags($testimonial_array->content); ?>”</p>
                  <img src="<?php echo xss_clean($testimonial_path).xss_clean($testimonial_profile); ?>">
                  <div class="details">
                    <span><?php echo xss_clean($testimonial_array->name); ?></span>
                  </div>
                </div>
                <!-- / Testimonial -->
                <?php
              }
            } ?>
          </div>
        </section>
        <!-- Testimonials Work End -->
      </div>

      <div class="container home_page">
        <div class="row">
          <!-- Sponsers Work Start -->
          <div class="col-12">
            <?php 
            if($sponser_data)
            {
              ?>
              <div class="row">
                <div class="col-12 text-center"> <h2 class="heading"><?php echo lang('our_partners'); ?>  </h2> <hr></div>
              </div>
              <div class="sponsers text-center" data-slick='{"slidesToShow": 4, "slidesToScroll": 1}'>
                <?php

                foreach ($sponser_data as $sponser_array) 
                { 
                  $sponser_logo = $sponser_array->logo ? $sponser_array->logo : 'default.png';
                  ?>
                  <div class="col-6 sponser">
                    <a href="<?php echo xss_clean($sponser_array->link); ?>" title="<?php echo xss_clean($sponser_array->name); ?>">
                      <img src="<?php echo base_url('assets/images/sponsors/').$sponser_logo; ?>">
                    </a>
                  </div>
                  <?php
                }
                ?>
                <?php
              }
              ?>
            </div>
          </div>
          <!-- Sponsers Work End -->
        </div>
      </div>
