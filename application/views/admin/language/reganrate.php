<div class="row page">
   <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
         <div class="card-body">
            <?php echo form_open_multipart('', array('role'=>'form','novalidate'=>'novalidate')); ?>
            <div class="row">
               <div class="col-12">
                  <div class="form-group">
                     <select class="form-control select2" name="lang">
                        <option value=""><?php echo lang('Select Language'); ?></option>
                        <?php
                        foreach ($all_langague as $language) 
                           { ?>
                              <option value="<?php echo xss_clean($language->id); ?>"><?php echo xss_clean($language->lang); ?></option>

                              <?php
                           }
                           ?>
                        </select>
                     </div>

                     <div class="col-12 text-center">
                        <div class="form-group">
                           <input type="submit" value="<?php echo lang('admin button language regnarate'); ?>" class="btn btn-primary px-5">
                        </div>
                     </div>

                  </div>
               </div>
               <?php echo form_close();?>
            </div>
         </div>
      </div>
   </div>