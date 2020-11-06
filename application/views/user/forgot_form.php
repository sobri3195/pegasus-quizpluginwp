<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container-fluid pt-5 body_background">
   <div class="container">
      <div class="row">
         <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="card card-primary">
               <div class="card-header">
                  <h4>Forgot Password</h4>
               </div> 
               <div class="card-body">
                  <p class="text-muted">We will send a link to reset your password</p>
                  <?php echo form_open('', array('class'=>'form-signin formlogin')); ?>
                  <div class="form-group">
                     <?php echo form_label(lang('front_email'), 'email', array('class'=>'control-label')); ?>
                     <span class="required">*</span>
                     <?php echo form_input(array('name'=>'email','id'=>'email', 'value'=>set_value('email', (isset($user['email']) ? $user['email'] : '')), 'class'=>'form-control', 'type'=>'email')); ?>     
                     <span class="small text-danger"> <?php echo strip_tags(form_error('email')); ?> </span>                    
                  </div>
                  <div class="form-group">
                     <button type="submit" name="submit" class="btn btn-block btn-primary btn-lg"><?php echo lang('user_reset_password'); ?></button>
                  </div>
                  <?php echo form_close(); ?>       
               </div>
            </div>
         </div>
      </div>
   </div>
</div>