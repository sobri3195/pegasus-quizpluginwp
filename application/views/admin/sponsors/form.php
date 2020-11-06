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
              <?php echo  form_label(lang('admin_sponser_form_name'), 'name'); ?> 
              <span class="required">*</span>
              <?php 
                $populateData = $this->input->post('name') ? $this->input->post('name') : (isset($sponsors_data->name) ? $sponsors_data->name :  '' );
              ?>

              <input type="text" name="name" id="name" class="form-control" value="<?php echo xss_clean($populateData);?>">
              <span class="small form-error"> <?php echo strip_tags(form_error('name')); ?> </span>
            </div>
          </div>

          <div class="col-6">
            <div class="form-group <?php echo form_error('logo') ? ' has-error' : ''; ?>">
              <?php echo  form_label(lang('admin_sponsors_logo'), 'logo'); ?>

              <span class="required">*</span>
              <?php 
                $populateData = isset($sponsors_data->logo) && $sponsors_data->logo ? $sponsors_data->logo :  ''; 
              ?> 

              <input type="file" name="logo" id="logo" class="form-control" value="<?php echo xss_clean($populateData);?>">
              <span class="small form-error"> <?php echo strip_tags(form_error('logo')); ?> </span> 
              <?php 
                if($populateData)
                {
              ?> 
                <div class="">
                  <img class="image_preview popup" src="<?php echo base_url('assets/images/sponsors/').$populateData; ?>">
                </div>
              <?php
                }
              ?>
            </div>
          </div>

          <div class="clearfix"></div>

          <div class="col-12">
            <div class="form-group <?php echo form_error('link') ? ' has-error' : ''; ?>">
              <?php echo  form_label(lang('admin_sponsors_form_link'), 'link'); ?> 
              <span class="required">*</span>
              <?php 
                $populateData = $this->input->post('link') ? $this->input->post('link') : (isset($sponsors_data->link) ? $sponsors_data->link :  '' );
              ?>

              <input type="text" name="link" id="link" class="form-control" value="<?php echo xss_clean($populateData);?>">
              <span class="small form-error"> <?php echo strip_tags(form_error('link')); ?> </span>
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

