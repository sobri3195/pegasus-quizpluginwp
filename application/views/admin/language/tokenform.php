<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="row page">
      <div class="col-12 col-md-12 col-lg-12">
            <div class="card">
                  <div class="card-body">
                        <?php echo form_open_multipart('', array('role'=>'form','novalidate'=>'novalidate')); ?>
                        <div class="row">
                              <div class="col-6">
                                    <div class="form-group <?php echo form_error('lang') ? ' has-error' : ''; ?>">
                                          <?php echo form_label(lang('language_group')); ?> 
                                          <select name="group" class="form-control">
                                                <option value="admin">Select Group</option>
                                                <option value="admin">admin</option>
                                                <option value="front">front</option>
                                                <option value="other">other</option>
                                          </select>
                                    </div>
                              </div>
                              <div class="col-6">
                                    <div class="form-group <?php echo form_error('lang') ? ' has-error' : ''; ?>">
                                          <?php echo  form_label(lang('token_name')); ?> 
                                          <input type="text" name="tokenname" id="tokenname" class="form-control" value="">
                                    </div>
                              </div>
                              <div class="col-10">
                                    <div class="form-group <?php echo form_error('lang') ? ' has-error' : ''; ?>">
                                          <?php echo  form_label(lang('description')); ?> 
                                          <?php foreach($languages as $lang_key => $lang_value)
                                          {
                                                ?>
                                                <div class="row">
                                                      <div class="col-9">
                                                            <input type="text" name="tokendesc[<?php echo xss_clean($lang_value->id);?>]" id="tokendesc" class="form-control" value="">
                                                      </div>
                                                      <div class="col-3" ><?php echo xss_clean($lang_value->lang);?></div>	
                                                </div>
                                          <?php } ?>
                                    </div>
                              </div>
                              <div class="col-12 ">
                                    <input type="submit"  value="<?php echo lang('core_button_save');?>" class="btn btn-primary px-5">
                                    <a href="<?php echo base_url('admin/language');?>" class="btn btn-dark px-5"><?php echo lang('core_button_cancel'); ?></a>
                              </div>
                        </div>
                        <?php echo form_close();?>
                  </div>
            </div>
      </div>
</div>
