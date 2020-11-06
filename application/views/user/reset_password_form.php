<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container-fluid pt-5 body_background">
   <div class="container">
      <div class="row">
         <div class="col-12 col-sm-8 offset-sm-2 col-md-6 offset-md-3 col-lg-6 offset-lg-3 col-xl-4 offset-xl-4">
            <div class="card card-primary">
               <div class="card-header">
                  <h4>Reset Password</h4>
               </div>
               <div class="card-body">
                  <?php echo form_open($action, array('class'=>'form-signin formlogin')); ?>

                  <div class="row">
                        <?php // password   ?>
                        <div class="form-group col-12<?php echo form_error('password') ? ' has-error' : ''; ?>">
                           <?php echo form_label(lang('password'), 'password', array('class'=>'control-label')); ?>
                           
                           <span class="required">* </span>
                          
                           <?php echo form_password(array('name'=>'password', 'value'=>'', 'class'=>'form-control', 'autocomplete'=>'off')); ?>
                           <span class="small text-danger"> <?php echo strip_tags(form_error('password')); ?> </span>
                        </div>
                        <div class="clearfix"></div>
                        <?php // password repeat ?>
                        <div class="form-group col-12<?php echo form_error('password_repeat') ? ' has-error' : ''; ?>">
                           <?php echo form_label(lang('front_password_repeat'), 'password_repeat', array('class'=>'control-label')); ?>
                           
                           <span class="required">* </span>
                          
                           <?php echo form_password(array('name'=>'password_repeat', 'value'=>'', 'class'=>'form-control', 'autocomplete'=>'off')); ?>
                           <span class="small text-danger"> <?php echo strip_tags(form_error('password_repeat')); ?> </span>
                        </div>
                        
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