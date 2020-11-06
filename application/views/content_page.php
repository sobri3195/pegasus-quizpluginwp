<div class="width-100 content_page header-background" >
  <div class="container content_page">
   <h2 class="content_page_title"> <?php echo xss_clean($page_data->title); ?></h2>
  </div>
</div>

<div class="container content_page">
  	<div class="row"> 
      <div class="col-md-12 col-xl-12 col-sm-12 mt-5" >
      	<?php echo xss_clean($page_data->content); ?>
      </div>
  	</div>
</div>

