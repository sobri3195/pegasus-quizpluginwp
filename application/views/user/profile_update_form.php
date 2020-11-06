<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<?php
   $currency =  get_admin_setting('currency_code');
   $currency_code =  get_currency_symbol($currency);
?>
<div class="container-fluid pt-5 body_background profile_update_page">
   <div class="container">
      <div class="row">
         <div class="col-12 ">
            <ul class="nav nav-tabs m-0 p-0 bg-white" id="my-profile-Tab" role="tablist">
               <li class="nav-item w-30">
                  <a class="nav-link active" id="profile-tab" data-toggle="tab" href="#profile" role="tab" aria-controls="profile" aria-selected="false"><?php echo 'Profile User'; ?></a>
               </li>
               <li class="nav-item w-30">
                  <a class="nav-link " id="quiz-like-tab" data-toggle="tab" href="#quiz-like" role="tab" aria-controls="fav-product" aria-selected="true"><?php echo 'Like Quiz'; ?></a> 
               </li>
            </ul>
            <div class="tab-content" id="my-profile-TabContent">
               <div class="tab-pane fade active show" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                  <div class="card card-primary">
                     <div class="card-header">
                        <h4><?php echo lang('Update Profile'); ?></h4>
                     </div>
                     <div class="card-body">
                        <?php echo form_open_multipart('', array('role'=>'form')); ?>
                        <?php // username ?>
                        <div class="row">
                           <div class="col-12 col-sm-10 offset-sm-1 col-md-8 offset-md-2 col-lg-8 offset-lg-2 col-xl-8 offset-xl-2">
                              <div class="row">
                                 <div class="form-group col-12<?php echo form_error('username') ? ' has-error' : ''; ?>">
                                    <?php echo form_label(lang('username'), 'username', array('class'=>'control-label')); ?>
                                    <span class="required"> * </span>
                                    <?php echo form_input(array('name'=>'username', 'id'=>'username','value'=>set_value('username', (isset($user['username']) ? $user['username'] : '')), 'class'=>'form-control')); ?>
                                    <span class="small text-danger"> <?php echo strip_tags(form_error('username')); ?> </span>
                                 </div>
                              </div>
                              <div class="row">
                                 <?php // first name ?>    
                                 <div class="form-group col-6<?php echo form_error('first_name') ? ' has-error' : ''; ?>">
                                    <?php echo form_label(lang('front_first_name'), 'first_name', array('class'=>'control-label')); ?>
                                    <span class="required">*</span>
                                    <?php echo form_input(array('name'=>'first_name','id'=>'first_name', 'value'=>set_value('first_name', (isset($user['first_name']) ? $user['first_name'] : '')), 'class'=>'form-control')); ?>
                                    <span class="small text-danger"> <?php echo strip_tags(form_error('first_name')); ?> </span>
                                 </div>
                                 <?php // last name ?>
                                 <div class="form-group col-6<?php echo form_error('last_name') ? ' has-error' : ''; ?>">
                                    <?php echo form_label(lang('front_last_name'), 'last_name', array('class'=>'control-label')); ?>
                                    <span class="required">*</span>
                                    <?php echo form_input(array('name'=>'last_name','id'=>'last_name', 'value'=>set_value('last_name', (isset($user['last_name']) ? $user['last_name'] : '')), 'class'=>'form-control')); ?>
                                    <span class="small text-danger"> <?php echo strip_tags(form_error('last_name')); ?> </span>
                                 </div>
                              </div>
                              <div class="row">
                                 <?php // email ?>
                                 <div class="form-group col-6<?php echo form_error('email') ? ' has-error' : ''; ?>">
                                    <?php echo form_label(lang('front_email'), 'email', array('class'=>'control-label')); ?>
                                    <span class="required">*</span>
                                    <?php echo form_input(array('name'=>'email', 'value'=>set_value('email', (isset($user['email']) ? $user['email'] : '')), 'class'=>'form-control', 'type'=>'email')); ?>
                                    <span class="small text-danger"> <?php echo strip_tags(form_error('email')); ?> </span>
                                 </div>
                                 <?php // language ?>
                                 <div class="form-group col-6<?php echo form_error('language') ? ' has-error' : ''; ?>">
                                    <?php echo form_label(lang('front_language'), 'language', array('class'=>'control-label')); ?>
                                    <span class="required">*</span>
                                    <?php echo form_dropdown('language', $this->languages, (isset($user['language']) ? $user['language'] : $this->config->item('language')), 'id="language" class="form-control"'); ?>
                                    <span class="small text-danger"> <?php echo strip_tags(form_error('language')); ?> </span>
                                 </div>
                              </div>
                              <div class="row">
                                 <?php // password ?>
                                 <div class="form-group col-6<?php echo form_error('password') ? ' has-error' : ''; ?>">
                                    <?php echo form_label(lang('password'), 'password', array('class'=>'control-label')); ?>
                                    <?php if ($password_required) : ?>
                                    <span class="required">* </span>
                                    <?php endif; ?>
                                    <?php echo form_password(array('name'=>'password', 'value'=>'', 'class'=>'form-control', 'autocomplete'=>'off')); ?>
                                    <span class="small text-danger"> <?php echo strip_tags(form_error('password')); ?> </span>
                                 </div>
                                 <?php // password repeat ?>
                                 <div class="form-group col-6<?php echo form_error('password_repeat') ? ' has-error' : ''; ?>">
                                    <?php echo form_label(lang('front_password_repeat'), 'password_repeat', array('class'=>'control-label')); ?>
                                    <?php if ($password_required) : ?>
                                    <span class="required">* </span>
                                    <?php endif; ?>
                                    <?php echo form_password(array('name'=>'password_repeat', 'value'=>'', 'class'=>'form-control', 'autocomplete'=>'off')); ?>
                                    <span class="small text-danger"> <?php echo strip_tags(form_error('password_repeat')); ?> </span>
                                 </div>
                                 <?php if ( ! $password_required) : ?>
                                 <div class="col-12 mb-3">
                                    <span class="help-block text-warning"><?php echo lang('help_passwords'); ?></span>
                                 </div>
                                 <?php endif; ?>
                              </div>
                              <div class="row d-none">
                                 <div class="form-group col-12<?php echo form_error('password_repeat') ? ' has-error' : ''; ?>">
                                    <?php echo form_label(lang('front_upload_image'), 'user_image', array('class'=>'control-label')); ?>
                                    <?php echo form_upload(array('name'=>'user_image','class'=>'form-control'));?>
                                 </div>
                              </div>
                              <?php // buttons ?>
                              <div class="row ">
                                 <div class="form-group col-12 mt-3"> 
                                    <?php if ($this->session->userdata('logged_in')) : ?>
                                    <button type="submit" name="submit" class="btn btn-block btn-primary"><span class="glyphicon glyphicon-save"></span> <?php echo lang('core_button_save'); ?></button>
                                    <?php else : ?>
                                    <button type="submit" name="submit" class="btn btn-block btn-primary btn-lg"><?php echo lang('users_register'); ?></button>
                                    <?php endif; ?>
                                 </div>
                              </div>
                           </div>
                        </div>
                        <?php echo form_close(); ?>      
                     </div>
                  </div>
               </div>
               <div class="tab-pane fade " id="quiz-like" role="tabpanel" aria-labelledby="quiz-like-tab">
                  <div class="card card-primary">
                     <div class="card-header">
                        <h4><?php echo lang('Update Profile'); ?></h4>
                     </div>
                     <div class="card-body">
                        <div class="row">
                           <?php 
                            $quiz_running = NULL;
                            $session_quiz_id = NULL;

                            if($session_quiz_data && $session_quiz_question_data) 
                            { 
                              $quiz_running = 'quiz_running';
                              $session_quiz_id = $session_quiz_data['id'];

                              echo "<input type='hidden' value='".$session_quiz_id."' class='session_quiz_id'>";
                              
                            }
                            if($quiz_data)
                            {
                              foreach ($quiz_data as  $quiz_array) 
                              {
                                 $price = $quiz_array->price > 1 ? '₹ '.$quiz_array->price : ' '.lang('free');
                                 $start_quiz_link = $quiz_array->price > 1 ? '₹ '.$quiz_array->price : ' '.lang('free');
                                 $quiz_id = $quiz_array->id;
                                 $quiz_url = $session_quiz_id == $quiz_id ?  base_url("test/$session_quiz_id/1") : base_url("instruction/$quiz_id");
                                 $quiz_btn_name = $session_quiz_id == $quiz_id  ?  lang('resume_test') : lang("start_quiz");
                                 if($quiz_url !=  base_url("test/$session_quiz_id/1") && $quiz_array->price > 1)
                                 {   
                                    $quiz_url = base_url("quiz-pay/payment-mode/$quiz_id");
                                    
                                    $quiz_btn_name = $quiz_btn_name != lang('resume_test') ? 'Pay Now' : $quiz_btn_name;
                                 }

                                 $quiz_running_btn = $session_quiz_id == $quiz_id ?  "" : $quiz_running;
                                 
                                 $quiz_title = strlen($quiz_array->title) > 60 ? substr($quiz_array->title,0,60)."..." : $quiz_array->title;
                           ?>
                              <div class="col-sm-6 col-lg-3">
                                 <div class="card p-3">
                                   <div class="card-header pl-0">
                                     <h3 class="card-title custom-title"><?php echo xss_clean($quiz_title); ?> <span class="text-danger"><?php echo xss_clean($price); ?></span></h3>
                                   </div>
                                   <ul class="number-question">
                                    <li><?php echo lang('front_questions'); ?> <?php echo xss_clean($quiz_array->number_questions); ?></li>
                                    <li><?php echo lang('duration'); ?>  <?php echo xss_clean($quiz_array->duration_min); ?> <?php echo lang('minutes'); ?></li>
                                   </ul>
                                  <div class="text-muted">
                                    <a href="/javascript:void(0)" class="icon float-left"><i class="fe fe-eye mr-1"></i> <?php echo xss_clean($quiz_array->total_view); ?></a>
                                    <a href="/javascript:void(0)" class="icon d-none d-md-inline-block ml-3 float-right like-quiz">
                                      <?php $like_or_not = (isset($quiz_array->like_id) && !empty($quiz_array->like_id) ? 'text-success' : 'text-muted');?>
                                      <i class="fav_icon fas fa-heart <?php echo xss_clean($like_or_not);?>" data-quiz_id="<?php echo xss_clean($quiz_array->id);?>"></i> 
                                      <?php $total_like = (!empty($quiz_array->total_like) && $quiz_array->total_like > 0 ? $quiz_array->total_like : "");?>
                                      <span class="like-quiz-count-<?php echo xss_clean($quiz_array->id);?>"><?php echo xss_clean($total_like); ?></span>
                                    </a>
                                    <div class="clearfix"></div>
                                  </div>
                                  <div class="quiz-end">
                                     <div class="d-flex align-items-center card-footer">
                                         <div><a href="<?php echo xss_clean($quiz_url); ?>" id="quiz_<?php echo xss_clean($quiz_array->id);?>" class="btn btn-primary btn-sm mt-3 <?php echo xss_clean($quiz_running_btn); ?>" data-quiz_id="<?php echo xss_clean($quiz_array->id); ?>"><?php echo xss_clean($quiz_btn_name); ?></a></div>
                                         <small class="d-block text-muted">
                                           <?php
                                             if( $quiz_array->leader_board == 1)
                                             {
                                           ?>
                                             <a href="<?php echo base_url("quiz/leader-board/$quiz_id") ?>" class="btn btn-secondary btn-sm ml-2 mt-3 <?php echo xss_clean($quiz_running); ?>"><?php echo lang("leader_board"); ?></a>
                                           <?php  } ?>    
                                         </small>
                                       
                                     </div>
                                  </div>
                                 </div>
                              </div>
                           <?php } } ?>   
                        </div>
                     </div>
                  </div>
               </div>
            </div>
            <!-- card premium-->
         </div>
         <!-- <col-12> -->
      </div>
      <!---row-->
   </div>
</div>

<?php
  if ($this->session->userdata('logged_in')) : 
    if(uri_string() == 'user/register')
      {
        return redirect(base_url('profile'));
      }
  endif;
?>