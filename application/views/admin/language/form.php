<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $update = isset($language_id) && $language_id ? '_update' : '' ?>
<div class="row page">
   <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
         <div class="card-body">
            <?php echo form_open_multipart('', array('role'=>'form','novalidate'=>'novalidate')); ?>
            <div class="row">
               <input type="hidden" name="language_id" value="<?php echo isset($language_id) && $language_id ? $language_id : ''; ?>">
               <div class="col-8">
                  <div class="form-group <?php echo form_error('lang') ? ' has-error' : ''; ?>">
                     <?php echo  form_label(lang('token_name'), 'lang'); ?> 
                     <span class="required">*</span>
                     <?php 
                     $populateData = $this->input->post('lang') ? $this->input->post('lang') : (isset($language_data->lang) ? $language_data->lang :  '' );
                     ?>

                     <input type="text" name="lang" id="lang" class="form-control" value="<?php echo xss_clean($populateData);?>">
                     <span class="small form-error text-danger"> <?php echo strip_tags(form_error('lang')); ?> </span>
                  </div>
               </div>

               <?php
               $checked = $this->input->post('is_rtl') ? 'checked' : (isset($language_data->is_rtl) && $language_data->is_rtl == 1 ? 'checked' : '');

               ?>
               <div class="col-4">
                  <div class="form-group"> 
                     <?php echo  form_label(lang('admin_language_is_rtl'), 'is_rtl'); ?> 
                     <label class="custom-switch form-control">
                        <input type="checkbox" <?php echo xss_clean($checked); ?> name="is_rtl" value="1" class="custom-switch-input">
                        <span class="custom-switch-indicator"></span>
                        <span class="custom-switch-description"><?php echo lang('admin_language_is_rtl_msg'); ?></span>
                     </label>
                  </div>
               </div>

               <div class="clearfix"></div>

               <hr />

               <div class="col-12 ">
                  <?php $saveUpdate = isset($language_id) ? lang('core_button_update') : lang('core_button_save');  ?>
                  <input type="submit"  value="<?php echo ucfirst($saveUpdate);?>" class="btn btn-primary px-5">
                  <a href="<?php echo base_url('admin/language');?>" class="btn btn-dark px-5"><?php echo lang('core_button_cancel'); ?></a>
               </div>
               <div class="clearfix"></div>
            </div>
            <?php echo form_close();?>
         </div>
      </div>
   </div>
</div>

