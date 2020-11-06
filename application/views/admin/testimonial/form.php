<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $update = isset($article_id) && $article_id ? '_update' : '' ?>
<div class="row page">
  <div class="col-12 col-md-12 col-lg-12">
    <div class="card">
      <div class="card-body">
        <?php echo form_open_multipart('', array('role'=>'form','novalidate'=>'novalidate')); ?> 
        <div class="row">

          <div class="col-6">
            <div class="form-group <?php echo form_error('name') ? ' has-error' : ''; ?>">
              <?php echo  form_label(lang('admin_testimonial_name_form'), 'name'); ?> 
              <span class="required">*</span>
              <?php 
                $populateData = $this->input->post('name') ? $this->input->post('name') : (isset($testimonial_data->name) ? $testimonial_data->name :  '' );
              ?>
              <input type="text" name="name" id="name" class="form-control" value="<?php echo xss_clean($populateData);?>">
              <span class="small form-error"> <?php echo strip_tags(form_error('name')); ?> </span>
            </div>
          </div>

          <div class="col-6">
            <div class="form-group <?php echo form_error('profile') ? ' has-error' : ''; ?>">
              <?php echo  form_label(lang('admin_testimonial_form_profile'), 'profile'); ?>

              <span class="required">*</span>
              <?php 
                $populateData = isset($testimonial_data->profile) && $testimonial_data->profile ? $testimonial_data->profile :  ''; 
              ?> 
              <input type="file" name="profile" id="profile" class="form-control" value="<?php echo xss_clean($populateData);?>">
              <span class="small form-error"> <?php echo strip_tags(form_error('profile')); ?> </span> 
              <?php 
                if($populateData)
                {
              ?> 
                <div class="">
                  <img class="image_preview popup" src="<?php echo base_url('assets/images/testimonial/').$populateData; ?>">
                </div>
              <?php
                }
              ?>
            </div>
          </div>

          <div class="clearfix"></div>

          <div class="col-12">
            <div class="form-group <?php echo form_error('content') ? ' has-error' : ''; ?>">
              <?php echo  form_label(lang("admin_testimonial_message"), 'content'); ?>
              <span class="required">*</span>
              <?php
                $populateData = $this->input->post('content') ? $this->input->post('content') : (isset($testimonial_data->content) ? $testimonial_data->content :  '' );
              ?>
              <textarea name="content" id="p_desc" class="form-control editor" rows="5" ><?php echo xss_clean($populateData);?></textarea>
              <span class="small form-error"> <?php echo strip_tags(form_error('content')); ?> </span>
            </div>
          </div>

          <div class="clearfix"></div>

          <hr />

          <div class="col-12">
            <?php $saveUpdate = isset($page_id) ? lang('core_button_update') : lang('core_button_save'); ?>
            <input type="submit"  value="<?php echo ucfirst($saveUpdate);?>" class="btn btn-primary px-5">
            <a href="<?php echo base_url('admin/pages');?>" class="btn btn-dark px-5"><?php echo lang('core_button_cancel'); ?></a>
          </div>
          <div class="clearfix"></div>
        </div>
        <?php echo form_close();?>
      </div>
    </div>
  </div>
</div>
