<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="container-fluid">
   <div class="row">
      <div class="col-12 col-md-12 col-lg-12">
         <div class="card">
            <div class="card-body">
               <?php echo form_open_multipart('', array('role'=>'form')); ?>
               <?php // hidden id ?>
               <?php if (isset($user_id)) : ?>
                  <?php echo form_hidden('id', $user_id); ?>
               <?php endif; ?>
               <div class="row">
                  <?php // username ?>
                  <div class="form-group col-sm-4<?php echo form_error('username') ? ' has-error' : ''; ?>">
                     <?php echo form_label(lang('admin_username'), 'username', array('class'=>'control-label')); ?>
                     <span class="required">*</span>
                     <?php echo form_input(array('name'=>'username', 'value'=>set_value('username', (isset($user['username']) ? $user['username'] : '')), 'class'=>'form-control')); ?>
                  </div>
                  <?php // first name ?>
                  <div class="form-group col-sm-4<?php echo form_error('first_name') ? ' has-error' : ''; ?>">
                     <?php echo form_label(lang('admin_first_name'), 'first_name', array('class'=>'control-label')); ?>
                     <span class="required">*</span>
                     <?php echo form_input(array('name'=>'first_name', 'value'=>set_value('first_name', (isset($user['first_name']) ? $user['first_name'] : '')), 'class'=>'form-control')); ?>
                  </div>
                  <?php // last name ?>
                  <div class="form-group col-sm-4<?php echo form_error('last_name') ? ' has-error' : ''; ?>">
                     <?php echo form_label(lang('admin_last_name'), 'last_name', array('class'=>'control-label')); ?>
                     <span class="required">*</span>
                     <?php echo form_input(array('name'=>'last_name', 'value'=>set_value('last_name', (isset($user['last_name']) ? $user['last_name'] : '')), 'class'=>'form-control')); ?>
                  </div>
                  <?php // language ?>
                  <div class="form-group col-sm-6<?php echo form_error('language') ? ' has-error' : ''; ?>">
                     <?php echo form_label(lang('admin_user_language'), 'language', array('class'=>'control-label')); ?>
                     <span class="required">*</span>
                     <?php echo form_dropdown('language', $this->languages, (isset($user['language']) ? $user['language'] : $this->config->item('language')), 'id="language" class="form-control"'); ?>
                  </div>
                  <?php // email ?>
                  <div class="form-group col-sm-6<?php echo form_error('email') ? ' has-error' : ''; ?>">
                     <?php echo form_label(lang('email'), 'email', array('class'=>'control-label')); ?>
                     <span class="required">*</span>
                     <?php echo form_input(array('name'=>'email', 'value'=>set_value('email', (isset($user['email']) ? $user['email'] : '')), 'class'=>'form-control')); ?>
                  </div>
                  <?php // status ?>
                  <div class="form-group col-sm-6<?php echo form_error('status') ? ' has-error' : ''; ?>">
                     <?php echo form_label(lang('admin_status'), '', array('class'=>'control-label')); ?>
                     <span class="required">*</span>
                     <?php 
                     $status_array = array('Active'=>'Active','Inactive'=>'Inactive');
                     $status = isset($user['status']) == 1 ? 'Active' : 'Inactive'; 

                     echo form_dropdown('status', $status_array, $status, 'id="status" class="form-control"'); 
                     ?>                     
                  </div>
                  <?php // administrator ?>
                  <div class="form-group col-sm-6<?php echo form_error('is_admin') ? ' has-error' : ''; ?>">
                     <?php echo form_label(lang('user_is_admin'), '', array('class'=>'control-label')); ?>
                     <span class="required">*</span>

                     <?php 
                     $user_array = array('Admin'=>'Admin','Authors'=>'Authors','Visitor'=>'Visitor',);
                     $is_admin = isset($user['is_admin']) === 1 ? 'Admin' :(isset($user['is_admin']) === 2 ? 'Authors' : 'Visitor'); 

                     echo form_dropdown('is_admin', $user_array, $is_admin, 'id="is_admin" class="form-control"'); 
                     ?>
                  </div>
                  <?php // password ?>
                  <div class="form-group col-sm-6<?php echo form_error('password') ? ' has-error' : ''; ?>">
                     <?php echo form_label(lang('user_password'), 'password', array('class'=>'control-label')); ?>
                     <?php if ($password_required) : ?><span class="required">*</span><?php endif; ?>
                     <?php echo form_password(array('name'=>'password', 'value'=>'', 'class'=>'form-control', 'autocomplete'=>'off')); ?>
                     <?php if ( ! $password_required) : ?>
                        <div class="width_100 mt-2">                
                           <span class="help-block text-warning"><?php echo lang('user_help_passwords'); ?></span>
                        </div>
                     <?php endif; ?>
                  </div>
                  <?php // password repeat ?>
                  <div class="form-group col-sm-6<?php echo form_error('password_repeat') ? ' has-error' : ''; ?>">
                     <?php echo form_label(lang('user_password_repeat'), 'password_repeat', array('class'=>'control-label')); ?>
                     <?php if ($password_required) : ?><span class="required">*</span><?php endif; ?>
                     <?php echo form_password(array('name'=>'password_repeat', 'value'=>'', 'class'=>'form-control', 'autocomplete'=>'off')); ?>
                  </div>
                  <div class="form-group col-sm-12 mb-3<?php echo form_error('password_repeat') ? ' has-error' : ''; ?>">
                     <?php echo form_label(lang('admin_upload_image'), 'user_image', array('class'=>'control-label')); ?>
                     <span class="required">*</span>
                     <?php echo form_upload(array('name'=>'user_image','class'=>'form-control')); ?>
                     <?php 
                     if(!empty($user['id']) && isset($user['id'])) 
                     {
                        $populateimg = (isset($user['image']) && $user['image'] ? $user['image'] : 'avatar-1.png');
                        $populateimg = base_url('assets/images/user_image/').$populateimg;
                        ?>
                        <img src="<?php echo xss_clean($populateimg);?>" class="img_thumb mt-2 popup">
                     <?php } ?>
                  </div>
               </div>
               <?php // buttons ?>
               <div class="row pull-right">
                  <div class="col-12">
                     <button type="submit" name="submit" class="btn btn-primary"><?php echo lang('core_button_save'); ?></button>
                     <a class="btn btn-dark ml-3" href="<?php echo xss_clean($cancel_url); ?>"><?php echo lang('core_button_cancel'); ?></a>
                  </div>
               </div>
               <?php echo form_close(); ?>
            </div>
         </div>
      </div>
   </div>
</div>