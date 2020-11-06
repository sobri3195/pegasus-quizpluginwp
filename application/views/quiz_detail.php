<div class="container">
	<div class="row">
    	<div class="col-md-12">
      		<div class="card mt-5">
      			<div class="card-body">
      				<div class="text-wrap p-lg-6">
			            <h1 class="text-primary text-center"><?php echo lang('front_quiz_detail'); ?></h1>
			            <hr>
			        </div>
			        <div class="row">
				        <div class="col-xs-6 col-sm-6 col-md-4 col-lg-3"> 
				        	<div class="detail-left">
				        		<?php  
				        			$quiz_title = strlen($quiz_data->title) > 40 ? substr($quiz_data->title,0,40)."..." : $quiz_data->title;
				        		?>
				        		<div class="detail_quiz_title">Quiz Title: <?php echo xss_clean($quiz_title); ?></div>
				        		<div class="detail_quiz_title">No. Of Questions: <?php echo xss_clean($quiz_data->number_questions); ?> </div>
				        		<div class="detail_quiz_title">Duration: <?php echo xss_clean($quiz_data->duration_min); ?> </div>
				        		<div class="detail_quiz_title">Like The Quiz: 
				        			<a class="btn btn-neutral btn-fill like-quiz" data-toggle="tooltip"  title="<?php echo lang('like'); ?>">
				        			<?php $like_or_not = (isset($quiz_data->like_id) && !empty($quiz_data->like_id) ? 'text-success' : 'text-muted');?>
				        			<i class="fav_icon fas fa-heart <?php echo xss_clean($like_or_not);?>" data-quiz_id="<?php echo xss_clean($quiz_data->id);?>"></i>
				        			</a>
				        		</div>
				        		<?php $quiz_price = $quiz_data->price > 0 ? $quiz_data->price : "Free"; ?>  
				        		<div class="detail_quiz_title">Quiz Price: <?php echo xss_clean($quiz_price); ?></div> 
				        	</div>
				        </div>
				        <div class="col-xs-6 col-sm-6 col-md-4 col-lg-9"> 
				        	<p class="text-justify"><?php echo xss_clean($quiz_data->description);?></p>
				        </div>
				    </div>
				    <hr>
				    <div class="row">
				    	<div class="col-xs-6 col-sm-6 col-md-4 col-lg-7">
				        	<?php echo form_open('rating', array('role'=>'form',)); ?>
				        		<input type="hidden" name="quizid" class="quizid" value="<?php echo xss_clean($quiz_data->id) ?>">
				        		<textarea class="form-control save-heading" name="ratingcontent" placeholder="Wrtie your comment here"></textarea>
				        		<section class='rating-widget'>
								  <!-- Rating Stars Box -->
								  <div class='rating-stars'>
								    <ul id='stars'>
								      <li class='star' data-toggle="tooltip" data-placement="bottom" title="Poor" data-value='1'>
								        <i class='fa fa-star fa-fw'></i>
								      </li>
								      <li class='star' data-toggle="tooltip" data-placement="bottom" title='Fair' data-value='2'>
								        <i class='fa fa-star fa-fw'></i>
								      </li>
								      <li class='star' data-toggle="tooltip" data-placement="bottom" title='Good' data-value='3'>
								        <i class='fa fa-star fa-fw'></i>
								      </li>
								      <li class='star' data-toggle="tooltip" data-placement="bottom" title='Excellent' data-value='4'>
								        <i class='fa fa-star fa-fw'></i>
								      </li>
								      <li class='star' data-toggle="tooltip" data-placement="bottom" title='WOW!!!' data-value='5'>
								        <i class='fa fa-star fa-fw'></i>
								      </li>
								    </ul>
								  </div>
								  <span class="small text-danger"> <?php echo strip_tags(form_error('title')); ?> </span>
								  <div class='success-box'>
								    <div class='text-message'></div>
								    <div class='clearfix'></div>
								  </div>
								</section>
                                <input type="hidden" class="rate" name="reviewstar" value="">
                                <input type="submit" name="save" value="Save" class="btn btn-primary btn-lg">
				        	</form>
				        </div> 
				        <div class="col-xs-6 col-sm-6 col-md-4 col-lg-5">
				        	<div class="border border-secondary">
				        		<div class="average-text">Average Rating</div>
				        		<div class="average-star">
				        			<span><i class="fa fa-star fill-star" aria-hidden="true"></i></span>
				        			<span><i class="fa fa-star fill-star" aria-hidden="true"></i></span>
				        			<span><i class="fa fa-star fill-star" aria-hidden="true"></i></span>
				        			<span><i class="far fa-star empty-star" aria-hidden="true"></i></span>
				        			<span><i class="far fa-star empty-star" aria-hidden="true"></i></span>
				        			<span>(0)</span>
				        		</div>
				        	</div>
				        </div> 
				    </div>
      			</div>
    		</div>
    	</div>
    </div>
</div>