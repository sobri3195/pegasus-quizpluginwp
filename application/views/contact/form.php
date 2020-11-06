<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container-fluid pt-5 body_background">
   <div class="container">
      <div class="row">
         <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
            <div class="card card-primary">
               <div class="card-header">
                  <h4>Contact Us</h4>
               </div>
               <div class="card-body">
                  <?php echo form_open('', array('role'=>'form')); ?>
                  <div class="row">
                     <?php  // message title ?>
                     <div class="form-group col-sm-12<?php echo form_error('title') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('title'), 'title', array('class'=>'control-label')); ?>
                        <span class="required">*</span>
                        <?php echo form_input(array('name'=>'title', 'value'=>set_value('title'), 'class'=>'form-control')); ?>
                        <span class="small text-danger"> <?php echo strip_tags(form_error('title')); ?> </span>
                     </div>
                     <?php  // name ?>
                     <div class="form-group col-sm-6<?php echo form_error('name') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('name'), 'name', array('class'=>'control-label')); ?>
                        <span class="required">*</span>
                        <?php echo form_input(array('name'=>'name', 'value'=>set_value('name'), 'class'=>'form-control')); ?>
                        <span class="small text-danger"> <?php echo strip_tags(form_error('name')); ?> </span>
                     </div>
                     <?php  // email ?>
                     <div class="form-group col-sm-6<?php echo form_error('email') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('email'), 'email', array('class'=>'control-label')); ?>
                        <span class="required">*</span>
                        <?php echo form_input(array('name'=>'email', 'value'=>set_value('email'), 'class'=>'form-control')); ?>
                        <span class="small text-danger"> <?php echo strip_tags(form_error('email')); ?> </span>
                     </div>
                  </div>
                  <div class="row">
                     <?php  // messsage body ?>
                     <div class="form-group col-sm-12<?php echo form_error('message') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('message'), 'message', array('class'=>'control-label')); ?>
                        <span class="required">*</span>
                        <?php echo form_textarea(array('name'=>'message', 'value'=>set_value('message'), 'class'=>'form-control')); ?>
                        <span class="small text-danger"> <?php echo strip_tags(form_error('message')); ?> </span>
                     </div>
                  </div>
                  <div class="row">
                     <?php  // captcha ?>
                     <div class="form-group col-sm-12<?php echo form_error('captcha') ? ' has-error' : ''; ?>">
                        <?php echo form_label(lang('captcha'), 'captcha', array('class'=>'control-label')); ?>
                        <br />
                        <div class="row">
                           <div class="col-3 captcha_image">  <?php echo xss_clean($captcha_image); ?> </div>
                           <div class="col-9">
                              <?php echo form_input(array('name'=>'captcha', 'id'=>'captcha','placeholder'=>'captcha', 'value'=>"", 'class'=>'form-control h-auto')); ?>
                              <span class="small text-danger"> <?php echo strip_tags(form_error('captcha')); ?> </span>
                           </div>
                        </div>
                     </div>
                  </div>
                  <?php // buttons ?>
                  <div class="row">
                     <div class="form-group col-sm-12">
                        <button type="submit" name="submit" class="btn btn-block btn-primary">Send</button>
                     </div>
                  </div>
                  <?php echo form_close(); ?>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>