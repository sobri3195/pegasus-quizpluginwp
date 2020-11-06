<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $update = isset($language_id) && $language_id ? '_update' : '' ?>
<div class="row page">
   <div class="col-12 mb-3 col-md-12 col-lg-12">
      <?php
         if ($lang_name) 
         {
            echo "<h4 class='text-primary'> $lang_name </h4> ";
         }
      ?>
   </div>
   <div class="clearfix"></div>

   <div class="col-12 col-md-12 col-lg-12">
      <div class="card">
         <div class="card-body">
            <?php echo form_open_multipart('', array('role'=>'form','novalidate'=>'novalidate')); ?>
            <div class="row">
               <input type="hidden" name="language_id" value="<?php echo isset($language_id) && $language_id ? $language_id : ''; ?>">

               <?php
                  if($lang_tokens)
                  {
               ?>
                  <div class="col-12">
                     <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                           <a class="nav-item nav-link active" id="nav-admin-token-tab" data-toggle="tab" href="#nav-admin-token" role="tab" aria-controls="nav-admin-token" aria-selected="true"><?php echo lang('admin_lang_token'); ?></a>
                           <a class="nav-item nav-link" id="nav-front-token-tab" data-toggle="tab" href="#nav-front-token" role="tab" aria-controls="nav-front-token" aria-selected="false"><?php echo lang('front_lang_token'); ?></a>
                           <a class="nav-item nav-link" id="nav-other-token-tab" data-toggle="tab" href="#nav-other-token" role="tab" aria-controls="nav-other-token" aria-selected="false"><?php echo lang('other_lang_token'); ?></a>
                        </div>
                     </nav>
                  </div>

                  <div class="clearfix"></div>

                  <div class="tab-content w-100" id="nav-tabContent">
                     <div class="tab-pane fade show active  mb-5" id="nav-admin-token" role="tabpanel" aria-labelledby="nav-admin-token-tab">
                        <div class="row m-0">
                           <?php
                           foreach ($lang_tokens as $lang_token_array) 
                           {

                              if($lang_token_array->group_name == 'admin')
                              {
                                 ?>  

                                 <div class="col-6">
                                    <div class="form-group">
                                       <label > <?php echo xss_clean($lang_token_array->token);?> </label>
                                       <?php 
                                       $populateData = $this->input->post("token[$lang_token_array->id]") ? $this->input->post("token[$lang_token_array->id]") : ($lang_token_array->description ? $lang_token_array->description :  '' );
                                       ?>
                                       <input type="text" name="token[<?php echo xss_clean($lang_token_array->id); ?>]" id="token" class="form-control token" value="<?php echo xss_clean($populateData);?>">

                                    </div>
                                 </div>

                                 <?php
                              }
                           }
                           ?>
                        </div>
                     </div>

                     <div class="tab-pane fade  mb-5" id="nav-front-token" role="tabpanel" aria-labelledby="nav-front-token-tab">
                        <div class="row m-0">
                           <?php
                           foreach ($lang_tokens as $lang_token_array) 
                           {

                              if($lang_token_array->group_name == 'front')
                              {
                                 ?>

                                 <div class="col-6">
                                    <div class="form-group">
                                       <label > <?php echo xss_clean($lang_token_array->token);?> </label>
                                       <?php 
                                       $populateData = $this->input->post("token[$lang_token_array->id]") ? $this->input->post("token[$lang_token_array->id]") : ($lang_token_array->description ? $lang_token_array->description :  '' );
                                       ?>
                                       <input type="text" name="token[<?php echo xss_clean($lang_token_array->id); ?>]" id="token" class="form-control token" value="<?php echo xss_clean($populateData);?>">

                                    </div>
                                 </div>

                                 <?php
                              }
                           }
                           ?>
                        </div>
                     </div>

                     <div class="tab-pane fade mb-5" id="nav-other-token" role="tabpanel" aria-labelledby="nav-other-token-tab">
                        <div class="row m-0">
                           <?php
                           foreach ($lang_tokens as $lang_token_array) 
                           {

                              if($lang_token_array->group_name != 'admin' && $lang_token_array->group_name != 'front')
                              {
                                 ?>

                                 <div class="col-6">
                                    <div class="form-group">
                                       <label > <?php echo xss_clean($lang_token_array->token);?> </label>
                                       <?php 
                                       $populateData = $this->input->post("token[$lang_token_array->id]") ? $this->input->post("token[$lang_token_array->id]") : ($lang_token_array->description ? $lang_token_array->description :  '' );
                                       ?>
                                       <input type="text" name="token[<?php echo xss_clean($lang_token_array->id); ?>]" id="token" class="form-control token" value="<?php echo xss_clean($populateData);?>">

                                    </div>
                                 </div>

                                 <?php
                              }
                           }
                           ?>
                        </div>
                     </div>

                  </div>
               <?php
                  }  
               ?>  
               <hr>

               <div class="col-12">
                  <?php $saveUpdate = isset($language_id) ? 'Update' : 'Save'; ?>
                  <input type="submit"  value="<?php echo ucfirst($saveUpdate);?>" class="btn btn-primary px-5">
                  <a href="<?php echo base_url('admin/language');?>" class="btn btn-dark px-5">Cancel</a>
               </div>

               <div class="clearfix"></div>
            </div>
            <?php echo form_close();?>
         </div>
      </div>
   </div>
</div>
