<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $update = isset($article_id) && $article_id ? '_update' : ''; ?>

<div class="row page admin_footer_section ">
  <div class="col-12 col-md-12 col-lg-12">
    <div class="card">
      <div class="card-body">
        <?php echo form_open_multipart('', array('role'=>'form','novalidate'=>'novalidate')); ?> 
        <div class="row border-bottom mb-3">

          <div class="col-5">
            <div class="form-group">
              <label><?php  echo lang('admin_footer_section'); ?></label>
              <select class="footer_section form-control" name="footer_section" id="footer_section">
                <option value="first"><?php  echo lang('admin_footer_section_first'); ?></option>
                <option value="second"><?php  echo lang('admin_footer_section_second'); ?></option>
                <option value="third"><?php  echo lang('admin_footer_section_third'); ?></option>
                <option value="fourth"><?php  echo lang('admin_footer_section_fourth'); ?></option>
              </select>
            </div>
          </div>

          <div class="col-5">
            <div class="form-group">
              <label><?php  echo lang('admin_footer_input_type'); ?></label>
              <select class="input_type form-control" name="input_type" id="input_type">
                <option value="text"><?php  echo lang('admin_footer_text'); ?> </option>
                <option value="link"><?php  echo lang('admin_footer_link'); ?></option>
                <option value="editor"><?php  echo lang('admin_footer_content'); ?></option>
                <option value="image"><?php  echo lang('admin_footer_image'); ?></option>
              </select>
            </div>
          </div>

          <div class="col-2">
            <button type="button" class="btn btn-primary btn-lg mt-4 add_more_footer_field"><span class="mr-2 "><i class="fas fa-plus"></i></span><?php  echo lang('admin_footer_add_more'); ?> </button>
          </div>

        </div> <!-- end row -->

        <div class="row my-5 footer_sections">

          <div class="col-6">
            <div class="card border border-secondary mb-4">
              <div class="card-header border-dark"><h4><?php  echo lang('admin_footer_section_first'); ?></h4></div>
              <div class="card-body ">
                <div class="row">
                  <div class="col-12 body_of_field footer_section_b_1">

                    <?php 
                    if($footer_section_data['first'])
                    { 
                      foreach ($footer_section_data['first'] as  $first_section_data) 
                      {
                        if($first_section_data->type =='text')
                        {
                          ?>

                          <div class="form-group pb-3 border-bottom input_field_div">
                            <label class="w-100"><?php  echo lang('admin_footer_field_title'); ?><span class="float-right text-danger"><i class="remove_this_input far fa-times-circle"></i></span></label>

                            <input type="text" class="form-control" value="<?php echo xss_clean($first_section_data->title); ?>" name="post_data[1][text][title][]">  

                            <label class="w-100"><?php  echo lang('admin_footer_text'); ?></label>
                            <input type="text" class="form-control" value="<?php echo xss_clean($first_section_data->value); ?>" name="post_data[1][text][value][]">
                          </div>

                          <?php
                        }

                        elseif($first_section_data->type =='link')
                        {

                          ?>

                          <div class="form-group pb-3 border-bottom input_field_div">
                            <label class="w-100"><?php  echo lang('admin_footer_field_title'); ?><span class="float-right text-danger"><i class="remove_this_input far fa-times-circle"></i></span></label>

                            <input type="text" class="form-control" value="<?php echo xss_clean($first_section_data->title); ?>" name="post_data[1][link][title][]">  

                            <label class="w-100"><?php  echo lang('admin_footer_field_link_url'); ?></label>
                            <input type="text" class="form-control" value="<?php echo xss_clean($first_section_data->value); ?>" name="post_data[1][link][value][]">
                          </div>

                          <?php
                        }

                        elseif($first_section_data->type =='editor')
                        {

                          ?>

                          <div class="form-group pb-3 border-bottom input_field_div">
                            <label class="w-100"><?php  echo lang('admin_footer_field_title'); ?><span class="float-right text-danger"><i class="remove_this_input far fa-times-circle"></i></span></label>

                            <input type="text" class="form-control" value="<?php echo xss_clean($first_section_data->title); ?>" name="post_data[1][editor][title][]"> 

                            <label class="w-100"><?php  echo lang('admin_footer_content'); ?> </label> 
                            <textarea id="p_desc" class="form-control editor" value="<?php echo xss_clean($first_section_data->value); ?>" rows="5" name="post_data[1][editor][value][]"><?php echo xss_clean($first_section_data->value); ?></textarea>
                          </div>

                          <?php
                        }

                        elseif($first_section_data->type =='image')
                        {

                          ?>

                          <div class="form-group pb-3 border-bottom input_field_div">
                            <label class="w-100"><?php  echo lang('admin_footer_field_title'); ?><span class="float-right text-danger"><i class="remove_this_input far fa-times-circle"></i></span></label>

                            <input type="text" class="form-control" value="<?php echo xss_clean($first_section_data->title); ?>" name="post_data[1][image][title][]"> 

                            <input type="hidden" name="post_data[1][image][last_img][]" value="<?php echo xss_clean($first_section_data->value); ?>"> 

                            <label class="w-100"><?php  echo lang('admin_footer_image'); ?> </label> 
                            <input accept="image/*" type="file" class="form-control" name="post_data[1][image][value][]">

                            <div class="img_content"> 
                              <?php $img = $first_section_data->value ? $first_section_data->value : 'default.png'; ?>
                              <img src="<?php echo base_url('/assets/images/footer/section/').$img; ?>" class="field_img">
                            </div>

                          </div>

                          <?php
                        }
                      }

                      ?>
                      <?php
                    } ?>

                  </div>
                </div>
              </div>
            </div>              
          </div>                  

          <div class="col-6">
            <div class="card border border-secondary mb-4">
              <div class="card-header border-dark"><h4><?php  echo lang('admin_footer_section_second'); ?></h4></div>
              <div class="card-body ">
                <div class="row">
                  <div class="col-12 body_of_field footer_section_b_2">

                    <?php 
                    if($footer_section_data['second'])
                    { 
                      foreach ($footer_section_data['second'] as  $second_section_data) 
                      {
                        if($second_section_data->type =='text')
                        {
                          ?>

                          <div class="form-group pb-3 border-bottom input_field_div">
                            <label class="w-100"><?php  echo lang('admin_footer_field_title'); ?><span class="float-right text-danger"><i class="remove_this_input far fa-times-circle"></i></span></label>

                            <input type="text" class="form-control" value="<?php echo xss_clean($second_section_data->title); ?>" name="post_data[2][text][title][]">  

                            <label class="w-100"><?php  echo lang('admin_footer_text'); ?></label>
                            <input type="text" class="form-control" value="<?php echo xss_clean($second_section_data->value); ?>" name="post_data[2][text][value][]">
                          </div>

                          <?php
                        }

                        elseif($second_section_data->type =='link')
                        {

                          ?>

                          <div class="form-group pb-3 border-bottom input_field_div">
                            <label class="w-100"><?php  echo lang('admin_footer_field_title'); ?><span class="float-right text-danger"><i class="remove_this_input far fa-times-circle"></i></span></label>

                            <input type="text" class="form-control" value="<?php echo xss_clean($second_section_data->title); ?>" name="post_data[2][link][title][]">  

                            <label class="w-100"><?php  echo lang('admin_footer_field_link_url'); ?></label>
                            <input type="text" class="form-control" value="<?php echo xss_clean($second_section_data->value); ?>" name="post_data[2][link][value][]">
                          </div>

                          <?php
                        }

                        elseif($second_section_data->type =='editor')
                        {

                          ?>

                          <div class="form-group pb-3 border-bottom input_field_div">
                            <label class="w-100"><?php  echo lang('admin_footer_field_title'); ?><span class="float-right text-danger"><i class="remove_this_input far fa-times-circle"></i></span></label>

                            <input type="text" class="form-control" value="<?php echo xss_clean($second_section_data->title); ?>" name="post_data[2][editor][title][]"> 

                            <label class="w-100"><?php  echo lang('admin_footer_content'); ?> </label> 
                            <textarea id="p_desc" class="form-control editor" value="<?php echo xss_clean($second_section_data->value); ?>" rows="5" name="post_data[2][editor][value][]"><?php echo xss_clean($second_section_data->value); ?></textarea>
                          </div>

                          <?php
                        }

                        elseif($second_section_data->type =='image')
                        {

                          ?>

                          <div class="form-group pb-3 border-bottom input_field_div">
                            <label class="w-100"><?php  echo lang('admin_footer_field_title'); ?><span class="float-right text-danger"><i class="remove_this_input far fa-times-circle"></i></span></label>

                            <input type="text" class="form-control" value="<?php echo xss_clean($second_section_data->title); ?>" name="post_data[2][image][title][]">

                            <input type="hidden" name="post_data[1][image][last_img][]" value="<?php echo xss_clean($second_section_data->value); ?>">  

                            <label class="w-100"><?php  echo lang('admin_footer_image'); ?> </label> 
                            <input accept="image/*" type="file" class="form-control" name="post_data[2][image][value][]">

                            <?php $img = $second_section_data->value ? $second_section_data->value : 'default.png'; ?>

                            <div class="img_content"> 
                              <img src="<?php echo base_url('/assets/images/footer/section/').$img; ?>" class="field_img">
                            </div>

                          </div>

                          <?php
                        }
                      }

                      ?>
                      <?php
                    } ?>

                  </div>
                </div>
              </div>
            </div>              
          </div>             


          <div class="col-6">
            <div class="card border border-secondary mb-4">
              <div class="card-header border-dark"><h4><?php  echo lang('admin_footer_section_third'); ?></h4></div>
              <div class="card-body ">
                <div class="row">
                  <div class="col-12 body_of_field footer_section_b_3">

                    <?php 
                    if($footer_section_data['third'])
                    { 
                      foreach ($footer_section_data['third'] as  $third_section_data) 
                      {
                        if($third_section_data->type =='text')
                        {
                          ?>

                          <div class="form-group pb-3 border-bottom input_field_div">
                            <label class="w-100"><?php  echo lang('admin_footer_field_title'); ?><span class="float-right text-danger"><i class="remove_this_input far fa-times-circle"></i></span></label>

                            <input type="text" class="form-control" value="<?php echo xss_clean($third_section_data->title); ?>" name="post_data[3][text][title][]">  

                            <label class="w-100"><?php  echo lang('admin_footer_text'); ?></label>
                            <input type="text" class="form-control" value="<?php echo xss_clean($third_section_data->value); ?>" name="post_data[3][text][value][]">
                          </div>

                          <?php
                        }

                        elseif($third_section_data->type =='link')
                        {

                          ?>

                          <div class="form-group pb-3 border-bottom input_field_div">
                            <label class="w-100"><?php  echo lang('admin_footer_field_title'); ?><span class="float-right text-danger"><i class="remove_this_input far fa-times-circle"></i></span></label>

                            <input type="text" class="form-control" value="<?php echo xss_clean($third_section_data->title); ?>" name="post_data[3][link][title][]">  

                            <label class="w-100"><?php  echo lang('admin_footer_field_link_url'); ?></label>
                            <input type="text" class="form-control" value="<?php echo xss_clean($third_section_data->value); ?>" name="post_data[3][link][value][]">
                          </div>

                          <?php
                        }

                        elseif($third_section_data->type =='editor')
                        {

                          ?>

                          <div class="form-group pb-3 border-bottom input_field_div">
                            <label class="w-100"><?php  echo lang('admin_footer_field_title'); ?><span class="float-right text-danger"><i class="remove_this_input far fa-times-circle"></i></span></label>

                            <input type="text" class="form-control" value="<?php echo xss_clean($third_section_data->title); ?>" name="post_data[3][editor][title][]"> 

                            <label class="w-100"><?php  echo lang('admin_footer_content'); ?> </label> 
                            <textarea id="p_desc" class="form-control editor" value="<?php echo xss_clean($third_section_data->value); ?>" rows="5" name="post_data[3][editor][value][]"><?php echo xss_clean($third_section_data->value); ?></textarea>
                          </div>

                          <?php
                        }

                        elseif($third_section_data->type =='image')
                        {

                          ?>

                          <div class="form-group pb-3 border-bottom input_field_div">
                            <label class="w-100"><?php  echo lang('admin_footer_field_title'); ?><span class="float-right text-danger"><i class="remove_this_input far fa-times-circle"></i></span></label>

                            <input type="text" class="form-control" value="<?php echo xss_clean($third_section_data->title); ?>" name="post_data[3][image][title][]"> 

                            <input type="hidden" name="post_data[1][image][last_img][]" value="<?php echo xss_clean($third_section_data->value); ?>"> 

                            <label class="w-100"><?php  echo lang('admin_footer_image'); ?> </label> 
                            <input accept="image/*" type="file" class="form-control" name="post_data[3][image][value][]">

                            <?php $img = $third_section_data->value ? $third_section_data->value : 'default.png'; ?>

                            <div class="img_content"> 
                              <img src="<?php echo base_url('/assets/images/footer/section/').$img; ?>" class="field_img">
                            </div>

                          </div>

                          <?php
                        }
                      }

                      ?>
                      <?php
                    } ?>

                  </div>
                </div>
              </div>
            </div>              
          </div>                  

          <div class="col-6">
            <div class="card border border-secondary mb-4">
              <div class="card-header border-dark"><h4><?php  echo lang('admin_footer_section_fourth'); ?></h4></div>
              <div class="card-body ">
                <div class="row">
                  <div class="col-12 body_of_field footer_section_b_4">

                    <?php 
                    if($footer_section_data['fourth'])
                    { 
                      foreach ($footer_section_data['fourth'] as  $fourth_section_data) 
                      {
                        if($fourth_section_data->type =='text')
                        {
                          ?>

                          <div class="form-group pb-3 border-bottom input_field_div">
                            <label class="w-100"><?php  echo lang('admin_footer_field_title'); ?><span class="float-right text-danger"><i class="remove_this_input far fa-times-circle"></i></span></label>

                            <input type="text" class="form-control" value="<?php echo xss_clean($fourth_section_data->title); ?>" name="post_data[4][text][title][]">  

                            <label class="w-100"><?php  echo lang('admin_footer_text'); ?></label>
                            <input type="text" class="form-control" value="<?php echo xss_clean($fourth_section_data->value); ?>" name="post_data[4][text][value][]">
                          </div>

                          <?php
                        }

                        elseif($fourth_section_data->type =='link')
                        {

                          ?>

                          <div class="form-group pb-3 border-bottom input_field_div">
                            <label class="w-100"><?php  echo lang('admin_footer_field_title'); ?><span class="float-right text-danger"><i class="remove_this_input far fa-times-circle"></i></span></label>

                            <input type="text" class="form-control" value="<?php echo xss_clean($fourth_section_data->title); ?>" name="post_data[4][link][title][]">  

                            <label class="w-100"><?php  echo lang('admin_footer_field_link_url'); ?></label>
                            <input type="text" class="form-control" value="<?php echo xss_clean($fourth_section_data->value); ?>" name="post_data[4][link][value][]">
                          </div>

                          <?php
                        }

                        elseif($fourth_section_data->type =='editor')
                        {

                          ?>

                          <div class="form-group pb-3 border-bottom input_field_div">
                            <label class="w-100"><?php  echo lang('admin_footer_field_title'); ?><span class="float-right text-danger"><i class="remove_this_input far fa-times-circle"></i></span></label>

                            <input type="text" class="form-control" value="<?php echo xss_clean($fourth_section_data->title); ?>" name="post_data[4][editor][title][]"> 

                            <label class="w-100"><?php  echo lang('admin_footer_content'); ?> </label> 
                            <textarea  id="p_desc" class="form-control editorr" rows="5"    name="post_data[4][editor][value][]"><?php echo xss_clean($fourth_section_data->value); ?></textarea>
                            <div class="social-network social-circle">
                              <ul>
                                <li>
                                  <a href="#" title="Facebook"><i class="fab fa-facebook-square"></i></a>
                                </li>
                                <li>
                                  <a href="#" title="Linkedin"><i class="fab fa-linkedin"></i></a>
                                </li>
                              </ul>
                            </div>
                          </div>

                          <?php
                        }

                        elseif($fourth_section_data->type =='image')
                        {

                          ?>

                          <div class="form-group pb-3 border-bottom input_field_div">
                            <label class="w-100"><?php  echo lang('admin_footer_field_title'); ?><span class="float-right text-danger"><i class="remove_this_input far fa-times-circle"></i></span></label>

                            <input type="text" class="form-control" value="<?php echo xss_clean($fourth_section_data->title); ?>" name="post_data[4][image][title][]">

                            <input type="hidden" name="post_data[1][image][last_img][]" value="<?php echo xss_clean($fourth_section_data->value); ?>">  

                            <label class="w-100"><?php  echo lang('admin_footer_image'); ?> </label> 
                            <input accept="image/*" type="file" class="form-control" name="post_data[4][image][value][]">

                            <?php 
                            $img = $fourth_section_data->value ? $fourth_section_data->value : 'default.png'; 


                            ?>

                            <div class="img_content"> 
                              <img src="<?php echo base_url('/assets/images/footer/section/').$img; ?>" class="field_img">
                            </div>
                          </div>

                          <?php
                        }
                      }

                      ?>
                      <?php
                    } ?>


                  </div>
                </div>
              </div>
            </div>              
          </div>             

        </div>
        <div class="clearfix"></div>



        <hr>

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

