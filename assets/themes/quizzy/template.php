<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* Default Public Template 
*/
?><!doctype html>
<?php 
$is_rtl = '';
$rtl_dir = '';
if ($this->session->is_rtl) 
{
  ?>
  <html lang="en" dir="rtl">
  <?php  
  $is_rtl = 'rtl_language';
  $rtl_dir = 'rtl';
}
else
{
  ?>
  <html lang="en" dir="ltr">
  <?php 
}
?>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta http-equiv="Content-Language" content="en" />
  <meta name="msapplication-TileColor" content="#2d89ef">
  <meta name="theme-color" content="#4188c9">
  <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent"/>
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="HandheldFriendly" content="True">
  <meta name="MobileOptimized" content="320">

  <link rel="icon" type="image/x-icon" sizes="32x32" href="<?php echo base_url('/assets/images/logo/'); ?><?php echo get_admin_setting('site_favicon'); ?>" />
  <!-- Generated: 2018-04-16 09:29:05 +0200 -->

  <meta name="keywords" content="<?php echo xss_clean($this->settings->meta_keywords); ?>">
  <meta name="description" content="<?php echo xss_clean($this->settings->meta_description); ?>">
  <link href="https://fonts.googleapis.com/css?family=Quicksand:300,500,700|Work+Sans:400,700" rel="stylesheet">


  <?php 
  if ($this->session->is_rtl) 
  {
    ?>
    <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/themes/admin/css/");?>/rtl.bootstrap.min.css">
    <?php  
  }
  ?>
  <title><?php echo xss_clean($page_title); ?> - <?php echo xss_clean($this->settings->site_name); ?></title>

  <?php if (isset($css_files) && is_array($css_files)) : ?>
  <?php foreach ($css_files as $css) : ?>
    <?php if ( ! is_null($css)) : ?>
      <?php $separator = (strstr($css, '?')) ? '&' : '?'; ?>
      <link rel="stylesheet" href="<?php echo xss_clean($css); ?><?php echo xss_clean($separator); ?>v=<?php echo xss_clean($this->settings->site_version); ?>"><?php echo "\n"; ?>
    <?php endif; ?>
  <?php endforeach; ?>
<?php endif; ?>


<?php
$login_user_id = isset($this->user['id']) ? $this->user['id'] : 0;
$ad_left_time = 0;
$ad_active_quiz = '';
$test_page = 'quiz';
if( $this->session->quiz_session)
{
  $ad_added_time = $this->session->quiz_session['participants_content']['started']; 

  $ad_dt = new DateTime($ad_added_time);
  $ad_minutes_to_add = $this->session->quiz_session['quiz_data']['duration_min'];
  $ad_time = new DateTime($ad_added_time);
  $ad_time->add(new DateInterval('PT' . $ad_minutes_to_add . 'M'));
  $ad_expire_time = $this->session->quiz_session['participants_content']['end_time'];

  $ad_expire_time = strtotime($ad_expire_time);
  $ad_current_time = strtotime(date('Y-m-d H:i:s'));


  $ad_session_quiz_id = $this->session->quiz_session['quiz_data']['id'];

  $ad_active_quiz = base_url("test/$ad_session_quiz_id/1");

  $ad_page_is_quiz = strstr(uri_string(), "test/$ad_session_quiz_id/") ? 'YES' : '';
  if(empty($ad_page_is_quiz))
  {
    $ad_left_time = $ad_expire_time - $ad_current_time;
    $test_page = 'other';
  }
}

?>

<script> 
  var BASE_URL = '<?php echo base_url(); ?>'; 
  var csrf_Name = '<?php echo $this->security->get_csrf_token_name() ?>'; 
  var csrf_Hash = '<?php echo $this->security->get_csrf_hash(); ?>'; 
  var rtl_dir = "<?php echo xss_clean($rtl_dir); ?>";
  var are_you_sure = "<?php echo lang('are_you_sure'); ?>";
  var permanently_deleted = "<?php echo lang('it_will_permanently_deleted'); ?>";
  var yes_delere_it = "<?php echo lang('yes_delere_it'); ?>";
  var resume_quiz_lang = "<?php echo lang('resume_quiz'); ?>";
  var quiz_result_lang = "<?php echo lang('quiz_result'); ?>";
  var check_quiz_result = "<?php echo lang('check_quiz_result'); ?>";
  var table_search = "<?php echo lang('table_search'); ?>";
  var table_show = "<?php echo lang('table_show'); ?>";
  var table_entries = "<?php echo lang('table_entries'); ?>";
  var table_showing = "<?php echo lang('table_showing'); ?>";
  var table_to = "<?php echo lang('table_to'); ?>";
  var table_of = "<?php echo lang('table_of'); ?>";
  var java_error_msg = "<?php echo lang('java_error_msg'); ?>";
  var table_previous = "<?php echo lang('table_previous'); ?>";
  var table_next = "<?php echo lang('table_next'); ?>";
  var total_attemp = "<?php echo lang('your_total_attemp_is'); ?>";
  var yes_submit_now = "<?php echo lang('submit_test'); ?>";
  var quiz_already_running = "<?php echo lang('quiz_already_running'); ?>";
  var stop_running_quiz_msg = "<?php echo lang('plz_complete_or_stop_running_quiz'); ?>";
  var resume_quiz = "<?php echo lang('resume_quiz'); ?>";
  var stop_quiz = "<?php echo lang('stop_quiz'); ?>";
  <?php if(isset($stripe_key['publishable_key'])) { ?>
    var stripe_publishable_key = "<?php echo xss_clean($stripe_key['publishable_key']); ?>";
  <?php } ?>

  var flash_message = '<?php echo $this->session->flashdata('message'); ?>';
  var flash_error = '<?php echo $this->session->flashdata('error'); ?>';

  var error_report = '<?php echo xss_clean($this->error); ?>';
  var ad_left_time = '<?php echo xss_clean($ad_left_time); ?>';
  var ad_active_quiz = '<?php echo xss_clean($ad_active_quiz); ?>';
  var test_page = '<?php echo xss_clean($test_page); ?>';
  var login_user_id = <?php echo xss_clean($login_user_id); ?>;
  login_user_id = parseInt(login_user_id); 



</script>

</head>

<body class="h-100 <?php echo xss_clean($is_rtl); ?>">
  <!-- Back to top button -->
  <a id="back-to-top-button"><i class="fas fa-angle-double-up"></i></a>
  <div class="page">
    <div class="page-main">
      <div class="header py-3 navigation-wrap start-header start-style">
        <div class="container">
          <div class="navbar navbar-expand-lg py-1">
            <a class="header-brand" href="<?php echo base_url()?>">
              <img class="header-brand-img" src="<?php echo base_url('/assets/images/logo/'); ?><?php echo get_admin_setting('site_logo'); ?>">
            </a>

            <div class="collapse navbar-collapse text-right" id="headerMenuCollapse">

              <ul class="navbar-nav nav nav-tabs navbar-right  border-0">
                <?php  

                $menu_category_array =  get_header_menu_item_helper(); 
                if($menu_category_array)
                {
                  foreach ($menu_category_array as $menu_array) 
                  {
                    ?>

                    <li class="nav-item">
                      <a href="<?php echo base_url('pages/').$menu_array->page_slug; ?>" class='nav-link <?php echo (uri_string() == "pages/$menu_array->page_slug") ? "active" : ""; ?>'>
                        <?php echo ucfirst($menu_array->title); ?></a>
                      </li>
                      <?php
                    }
                  }
                  ?>

                  <?php

                  if(isset($this->user['id']))
                  {
                    ?>

                    <li class="nav-item">
                      <a href="<?php echo base_url('my/history'); ?>" class="nav-link  <?php echo (uri_string() == "my/history") ? "active" : ""; ?>"><i class="fas fa-history mr-2"></i> <?php echo lang('quiz_history'); ?></a>
                    </li>
                    <?php
                  }
                  ?>

                  <li class="nav-item">
                    <a class="nav-link" href="<?php echo base_url("contact");?>">
                      <i class="dropdown-icon fe fe-help-circle"></i><?php echo lang('contact_title') ?>
                    </a>
                  </li>

                  <li class="nav-item">
                    <a href="javascript:void(0)" class="nav-link" data-toggle="dropdown"> <i class=" mr-2 fa fa-language"></i><?php echo lang('front_language') ?></a>
                    <div class="dropdown-menu dropdown-menu-arrow" id="session-language-dropdown">
                      <?php 
                      foreach ($this->languages as $key=>$name) : ?>
                        <a href="<?php echo base_url('change-language'); ?>" rel="<?php echo xss_clean($key); ?>" class="dropdown-item ">
                          <?php if ($key == $this->session->language) : ?>
                            <i class="fa fa-check selected-session-language"></i>
                          <?php endif; ?>
                          <?php echo xss_clean($name); ?>
                        </a>
                      <?php endforeach; ?>
                    </div>
                  </li>


                  <?php
                  $user_id = isset($this->user['id']) ? $this->user['id'] : NULL; 
                  $full_name_of_user = isset($this->user['first_name']) ? $this->user['first_name']. ' '.$this->user['last_name'] : '';
                  $is_admin = (isset($this->user['is_admin']) && $this->user['is_admin']==1) ? "Administrator" : "User";
                  $name_of_user = (strlen($full_name_of_user) > 15) ? substr($full_name_of_user, 0, 10).'...' : $full_name_of_user ;

                  $profile_url = "profile";

                  if ($user_id) 
                    { ?>

                      <li class="nav-item">
                        <a href="javascript:void(0)" class="nav-link" data-toggle="dropdown" title="<?php echo xss_clean($full_name_of_user); ?>"><span class="avatar" ></span>

                          <span class="ml-2 d-none d-lg-block">
                            <span class="text-defaultt"> <?php echo xss_clean($name_of_user); ?></span>
                          </span>

                        </a>
                        <div class="dropdown-menu dropdown-menu-arrow">

                          <a class="dropdown-item" href="<?php echo base_url($profile_url);?>">
                            <i class="dropdown-icon fe fe-user"></i><?php echo lang('user_profile') ?> 
                          </a>
                          <?php if ($is_admin == 'Administrator') 
                          { ?>
                            <a class="dropdown-item" href="<?php echo base_url("admin/settings");?>">
                              <i class="dropdown-icon fe fe-settings"></i><?php echo lang('admin_admin_settings') ?>
                            </a>
                            <?php
                          } ?>

                          <div class="dropdown-divider"></div>

                          <a class="dropdown-item" href="<?php echo base_url("logout");?>">
                            <i class="dropdown-icon fe fe-log-out"></i><?php echo lang('sign_out') ?>
                          </a>

                        </div>
                      </li>
                      <?php
                    }
                    else
                    {
                      ?>
                      <li class="nav-item">
                        <a href="<?php echo base_url('login'); ?>" class="nav-link">
                          <i class="fas fa-sign-in-alt mr-3"></i><?php echo lang('login') ?>
                        </a>
                      </li>
                      <?php
                    }
                    ?>

                    <li class="nav-item">
                      <div class="nav-item d-md-flex">
                        <label id="switch" class="switch mt-2">
                          <input type="checkbox" class="toggleTheme" id="slider">
                          <span class="slider round"></span>
                        </label>
                      </div>                     
                    </li>

                  </ul>

                </div>


                <button class="navbar-toggler" id="menu_togle_btn" type="button" data-toggle="collapse" data-target="#headerMenuCollapse" aria-controls="headerMenuCollapse" aria-expanded="false" aria-label="Toggle navigation">
                  <i class="fas fa-bars"></i>
                </button>

              </div>
            </div>
          </div>   

          <div class="my-3 my-md-5 header_margin_padding">
            <?php echo ($content) ?>
          </div>

          <div class="footer mt-5">
            <div class="container">
              <div class="row">

                <?php 
                $footer_sec_1 =  get_footer_section_helper(1); 
                $footer_sec_2 =  get_footer_section_helper(2); 
                $footer_sec_3 =  get_footer_section_helper(3); 
                $footer_sec_4 =  get_footer_section_helper(4); 
                ?>

                <div class="col-lg-3 Footer_section_1">

                  <div class="row">
                    <?php
                    if($footer_sec_1)
                    {                          
                      foreach ($footer_sec_1 as  $first_section_array) 
                      {
                        if($first_section_array->type =='text')
                        {
                          ?>

                          <div class="col-12">
                            <h4 class="text_heading"><?php echo xss_clean($first_section_array->title); ?> </h4>
                            <p class="footer_text_1"><?php echo xss_clean($first_section_array->value); ?> </p>
                          </div>

                          <?php
                        }
                        elseif($first_section_array->type =='link')
                        {

                          ?>

                          <div class="col-12 colum_link_section_1">
                            <a class="link_section_1" href="<?php echo xss_clean($first_section_array->value); ?>"> <?php echo xss_clean($first_section_array->title); ?></a>                              
                          </div>

                          <?php
                        }

                        elseif($first_section_array->type =='editor')
                        {

                          ?>

                          <div class="col-12">
                            <h6><?php echo xss_clean($first_section_array->title); ?> </h6>
                            <?php echo xss_clean($first_section_array->value); ?>
                          </div>

                          <?php
                        }

                        elseif($first_section_array->type =='image')
                        {
                          ?>
                          <div class="col-12 column_1">
                            <h6><?php echo xss_clean($first_section_array->title); ?> </h6>
                            <div class="img_content"> 
                              <?php $img = $first_section_array->value ? $first_section_array->value : 'default.png'; ?>
                              <img src="<?php echo base_url('/assets/images/footer/section/').$img; ?>" class="field_img">
                            </div>
                          </div>
                          <?php
                        }
                      }
                    }
                    ?>
                  </div>

                </div>





                <div class="col-lg-3 Footer_section_2">

                  <div class="row">
                    <?php
                    if($footer_sec_2)
                    {                          
                      foreach ($footer_sec_2 as  $second_section_array) 
                      {
                        if($second_section_array->type =='text')
                        {
                          ?>

                          <div class="col-12">
                            <h4 class="text_heading"><?php echo xss_clean($second_section_array->title); ?> </h4>
                            <?php echo xss_clean($second_section_array->value); ?>
                          </div>

                          <?php
                        }
                        elseif($second_section_array->type =='link')
                        {

                          ?>

                          <div class="col-12 colum_link_section_2">
                            <a class="link_section_2" href="<?php echo xss_clean($second_section_array->value); ?>"> <?php echo xss_clean($second_section_array->title); ?></a>                              
                          </div>

                          <?php
                        }

                        elseif($second_section_array->type =='editor')
                        {

                          ?>

                          <div class="col-12">
                            <h6><?php echo xss_clean($second_section_array->title); ?> </h6>
                            <?php echo xss_clean($second_section_array->value); ?>
                          </div>

                          <?php
                        }

                        elseif($second_section_array->type =='image')
                        {
                          ?>
                          <div class="col-12">
                            <h6><?php echo xss_clean($second_section_array->title); ?> </h6>
                            <div class="img_content"> 
                              <?php $img = $second_section_array->value ? $second_section_array->value : 'default.png'; ?>
                              <img src="<?php echo base_url('/assets/images/footer/section/').$img; ?>" class="field_img">
                            </div>
                          </div>
                          <?php
                        }
                      }
                    }
                    ?>
                  </div>


                </div>


                <div class="col-lg-3 Footer_section_3">

                  <div class="row">
                    <?php
                    if($footer_sec_3)
                    {                          
                      foreach ($footer_sec_3 as  $third_section_array) 
                      {
                        if($third_section_array->type =='text')
                        {
                          ?>

                          <div class="col-12">
                            <h4 class="text_heading"><?php echo xss_clean($third_section_array->title); ?> </h4>
                            <?php echo xss_clean($third_section_array->value); ?>
                          </div>

                          <?php
                        }
                        elseif($third_section_array->type =='link')
                        {

                          ?>

                          <div class="col-12 colum_link_section_3">
                            <a class="link_section_3" href="<?php echo xss_clean($third_section_array->value); ?>"> <?php echo xss_clean($third_section_array->title); ?></a>                              
                          </div>

                          <?php
                        }

                        elseif($third_section_array->type =='editor')
                        {

                          ?>

                          <div class="col-12">
                            <h6><?php echo xss_clean($third_section_array->title); ?> </h6>
                            <?php echo xss_clean($third_section_array->value); ?>
                          </div>

                          <?php
                        }

                        elseif($third_section_array->type =='image')
                        {
                          ?>
                          <div class="col-12">
                            <h6><?php echo xss_clean($third_section_array->title); ?> </h6>
                            <div class="img_content"> 
                              <?php $img = $third_section_array->value ? $third_section_array->value : 'default.png'; ?>
                              <img src="<?php echo base_url('/assets/images/footer/section/').$img; ?>" class="field_img">
                            </div>
                          </div>
                          <?php
                        }
                      }
                    }
                    ?>
                  </div>

                </div>


                <div class="col-lg-3 Footer_section_4">

                  <div class="row">
                    <?php
                    if($footer_sec_4)
                    {                          
                      foreach ($footer_sec_4 as  $fourth_section_array) 
                      {
                        if($fourth_section_array->type =='text')
                        {
                          ?>

                          <div class="col-12">
                            <h4 class="text_heading"><?php echo xss_clean($fourth_section_array->title); ?> </h4>
                            <?php echo xss_clean($fourth_section_array->value); ?>
                          </div>

                          <?php
                        }
                        elseif($fourth_section_array->type =='link')
                        {

                          ?>

                          <div class="col-12">
                            <a href="<?php echo xss_clean($fourth_section_array->value); ?>"> <?php echo xss_clean($fourth_section_array->title); ?></a>                              
                          </div>

                          <?php
                        }

                        elseif($fourth_section_array->type =='editor')
                        {

                          ?>

                          <div class="col-12">
                            <h6><?php echo xss_clean($fourth_section_array->title); ?> </h6>
                            <?php echo xss_clean($fourth_section_array->value); ?>
                          </div>

                          <?php
                        }

                        elseif($fourth_section_array->type =='image')
                        {
                          ?>
                          <div class="col-12">
                            <h6><?php echo xss_clean($fourth_section_array->title); ?> </h6>
                            <div class="img_content"> 
                              <?php $img = $fourth_section_array->value ? $fourth_section_array->value : 'default.png'; ?>
                              <img src="<?php echo base_url('/assets/images/footer/section/').$img; ?>" class="field_img">
                            </div>
                          </div>
                          <?php
                        }
                      }
                    }
                    ?>
                  </div>

                </div>
              </div>

            </div>
          </div>


          <footer class="footer">
            <div class="container">
              <div class="row">
                <div class="col-12 text-center copyright_footer">
                  <?php echo lang('copyright'); ?> © <?php echo date('Y');?> <?php echo xss_clean($this->settings->site_name); ?> v<?php echo xss_clean($this->settings->site_version); ?>. © <?php echo date('Y')?> <?php echo lang('copyright_all_rights_reserved'); ?>
                </div>
              </div>
            </div>

            <!-- START Bootstrap-Cookie-Alert -->
            <div class="alert text-center cookiealert" role="alert">
              <b><?php echo 'Do you like cookies' ?></b> &#x1F36A; <?php echo 'We use cookies'; ?> <a href="https://cookiesandyou.com/" target="_blank"><?php echo 'Learn more'; ?></a>

              <button type="button" class="btn btn-primary btn-sm acceptcookies" aria-label="Close">
                I agree
              </button>
            </div>
            <!-- END Bootstrap-Cookie-Alert -->

          </footer>
        </div>
      </div>

      <?php // Javascript files ?>
      <?php if (isset($js_files) && is_array($js_files)) : ?>
      <?php foreach ($js_files as $js) : ?>
        <?php if ( ! is_null($js)) : ?>
          <?php $separator = (strstr($js, '?')) ? '&' : '?'; ?>
          <?php echo "\n"; ?><script type="text/javascript" src="<?php echo xss_clean($js); ?><?php echo xss_clean($separator); ?>v=<?php echo xss_clean($this->settings->site_version); ?>"></script><?php echo "\n"; ?>
        <?php endif; ?>
      <?php endforeach; ?>
    <?php endif; ?>
    <?php if (isset($js_files_i18n) && is_array($js_files_i18n)) : ?>
    <?php foreach ($js_files_i18n as $js) : ?>
      <?php if ( ! is_null($js)) : ?>
        <?php echo "\n"; ?><script type="text/javascript"><?php echo "\n" . $js . "\n"; ?></script><?php echo "\n"; ?>
      <?php endif; ?>
    <?php endforeach; ?>
  <?php endif; ?>

</body>
</html>