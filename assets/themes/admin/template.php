<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
* Admin Template
*/  
?>
<!DOCTYPE html>
<?php 
$is_rtl = '';
$rtl_dir = '';
$margin_auto = 'mr-auto'; 
$order_two = NULL; 
if ($this->session->is_rtl) 
{
   ?>
   <html lang="en" dir="rtl">
   <?php  
   $is_rtl = 'rtl_language';
   $rtl_dir = 'rtl';
   $margin_auto = 'ml-auto';
   $order_two = 'order-2';
}
else
{ 
   ?>
   <html lang="en">
   <?php 
} 
?>
<head>
   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <!-- CSRF Token -->
   <meta name="csrf-token" content="">
   <link rel="icon" type="image/x-icon" sizes="32x32" href="<?php echo base_url('/assets/images/logo/'); ?><?php echo get_admin_setting('site_favicon'); ?>" />
   <title><?php echo xss_clean($page_title); ?> - <?php echo xss_clean($this->settings->site_name); ?></title>

   <?php 
   if ($this->session->is_rtl) 
   {
      ?>
      <link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/themes/admin/css/");?>/rtl.bootstrap.min.css">
      <?php  
   }
   ?>

   <?php // CSS files ?>
   <?php if (isset($css_files) && is_array($css_files)) : ?>
   <?php foreach ($css_files as $css) : ?>
      <?php if ( ! is_null($css)) : ?>
         <?php $separator = (strstr($css, '?')) ? '&' : '?'; ?>
         <link rel="stylesheet" href="<?php echo xss_clean($css); ?><?php echo xss_clean($separator); ?>v=<?php echo xss_clean($this->settings->site_version); ?>">
         <?php echo "\n"; ?>
      <?php endif; ?>
   <?php endforeach; ?>
<?php endif; ?>
<script> 
   var BASE_URL = '<?php echo base_url(); ?>'; 
   var csrf_Name = '<?php echo $this->security->get_csrf_token_name() ?>'; 
   var csrf_Hash = '<?php echo $this->security->get_csrf_hash(); ?>'; 
   var rtl_dir = "<?php echo xss_clean($rtl_dir); ?>";
   var are_you_sure = "<?php echo lang('are_you_sure'); ?>";
   var permanently_deleted = "<?php echo lang('it_will_permanently_deleted'); ?>";
   var yes_delere_it = "<?php echo lang('yes_delere_it'); ?>";
   var table_search = "<?php echo lang('table_search'); ?>";
   var table_show = "<?php echo lang('table_show'); ?>";
   var table_entries = "<?php echo lang('table_entries'); ?>";
   var table_showing = "<?php echo lang('table_showing'); ?>";
   var table_to = "<?php echo lang('table_to'); ?>";
   var table_of = "<?php echo lang('table_of'); ?>";
   var java_error_msg = "<?php echo lang('java_error_msg'); ?>";
   var table_previous = "<?php echo lang('table_previous'); ?>";
   var table_next = "<?php echo lang('table_next'); ?>";
   var update_company_also = "<?php echo lang('you_need_to_update_also'); ?>";
   var yes_add_more_field = "<?php echo lang('yes_add_more_field'); ?>";
   var yes_remove_it = "<?php echo lang('yes_delere_it'); ?>";
   var remove_from_company = "<?php echo lang('it_will_remove_from_quiz_also'); ?>";

   var flash_message = '<?php echo $this->session->flashdata('message'); ?>';
   var flash_error = '<?php echo $this->session->flashdata('error'); ?>';

   var error_report = '<?php echo xss_clean($this->error); ?>';


</script>
</head>
<body class="<?php echo xss_clean($is_rtl); ?>">
   <div id="app">
      <div class="main-wrapper main-wrapper-1">
         <div class="navbar-bg"></div>
         <nav class="navbar navbar-expand-lg main-navbar">
            <form class="form-inline mr-auto">
               <ul class="navbar-nav mr-3">
                  <li>
                     <a href="<?php echo base_url(''); ?>" data-toggle="sidebar" class="nav-link nav-link-lg">
                        <i class="fas fa-bars"></i>
                     </a>
                  </li>
                  <li>
                     <a href="<?php echo base_url(''); ?>" data-toggle="search" class="nav-link nav-link-lg d-sm-none">
                        <i class="fas fa-search"></i>
                     </a>
                  </li>
               </ul>
            </form>
            <ul class="navbar-nav navbar-right">


               <li>
                  <span class="dropdown">
                     <button id="session-language" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn btn-default">
                        <i class="fa fa-language"></i>
                        <span class="caret"></span>
                     </button>
                     <ul id="session-language-dropdown" class="dropdown-menu" role="menu" aria-labelledby="session-language">
                        <?php foreach ($this->languages as $key=>$name) : ?>
                           <li>
                              <a href="#" rel="<?php echo xss_clean($key); ?>">
                                 <?php if ($key == $this->session->language) : ?>
                                    <i class="fa fa-check selected-session-language"></i>
                                 <?php endif; ?>
                                 <?php echo xss_clean($name); ?>
                              </a>
                           </li>
                        <?php endforeach; ?>
                     </ul>
                  </span>
               </li>

               <?php 
               if($this->session->logged_in && $this->session->logged_in['is_admin'])
               {
                  ?>
                  <li class="dropdown">
                     <a href="<?php echo base_url('admin/users/edit/').$this->session->logged_in['id'];?>" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                        <?php $loginImage = ($this->session->logged_in['image'] ? base_url('assets/images/user_image/'.$this->session->logged_in['image']) : base_url('assets/images/user_image/avatar-1.png'))?>
                        <img alt="" src="<?php echo xss_clean($loginImage);?>" class="rounded-circle mr-1">
                        <div class="d-sm-none d-lg-inline-block">Hi, <?php echo substr($this->session->logged_in['username'],0,10)."..";?>
                     </div>
                  </a>
                  <div class="dropdown-menu dropdown-menu-right">
                     <a href="<?php echo base_url('admin/users/edit/').$this->session->logged_in['id'];?>" class="dropdown-item has-icon">
                        <i class="far fa-user"></i><?php echo lang('admin_profile'); ?> 
                     </a>
                     <a href="<?php echo base_url('admin/settings'); ?>" class="dropdown-item has-icon">
                        <i class="fas fa-cog"></i><?php echo lang('admin_admin_settings'); ?>
                     </a>
                     <div class="dropdown-divider"></div>
                     <a href="<?php echo base_url('logout'); ?>" class="dropdown-item has-icon"><i class="fas fa-sign-out-alt"></i><?php echo lang('core_button_logout'); ?>
                  </a>
               </div>
            </li>
         <?php } ?>
      </ul>
   </nav>
   <?php // Fixed navbar ?>
   <div class="main-sidebar sidebar-style-2" tabindex="1" >
      <aside id="sidebar-wrapper">
         <div class="sidebar-brand">
            <a href="<?php echo base_url(); ?>"><?php echo xss_clean($this->settings->site_name); ?></a>
         </div>
         <div class="sidebar-brand sidebar-brand-sm">
            <?php 
            $words = explode(" ", $this->settings->site_name);
            $acronym = "";
            foreach ($words as $w) 
            {
               $acronym .= $w[0];
            }
            ?>
            <a class="text-uppercase" href="<?php echo base_url(); ?>"><?php echo xss_clean($acronym); ?></a>
         </div>
         <ul class="sidebar-menu">
            <li class="dropdown <?php echo $this->uri->segment(2) == '' || $this->uri->segment(2) == 'index' || $this->uri->segment(2) == 'index_0' ? 'active' : ''; ?>">
               <a href="<?php echo base_url('admin/dashboard'); ?>" class="nav-link">
                  <i class="fas fa-fire"></i>
                  <span><?php echo lang('admin_dashboard');?></span>
               </a>
            </li>

            <li class="dropdown<?php echo (strstr(uri_string(), 'admin/category')) ? ' active' : ''; ?>">
               <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
                  <i class="fas fa fa-list-alt" aria-hidden="true"></i>
                  <span><?php echo lang('admin_category'); ?></span>
               </a>
               <ul class="dropdown-menu">
                  <li class="<?php echo (uri_string() == 'admin/category') ? 'active' : ''; ?>">
                     <a href="<?php echo base_url('admin/category'); ?>" class="nav-link">
                        <?php echo lang('category_list'); ?>
                     </a>
                  </li>
                  <li class="<?php echo (uri_string() == 'admin/category/form') ? 'active' : ''; ?>">
                     <a href="<?php echo base_url('admin/category/form'); ?>" class="nav-link"><?php echo lang('add_category'); ?>
                  </a>
               </li>
            </ul>
         </li>

         <li class="dropdown<?php echo (strstr(uri_string(), 'admin/quiz')) ? ' active' : ''; ?>">
            <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
               <i class="fas fa-newspaper"></i>
               <span><?php echo lang('dashboard_quiz'); ?></span>
            </a>
            <ul class="dropdown-menu">
               <li class="<?php echo (uri_string() == 'admin/quiz') ? 'active' : ''; ?>">
                  <a href="<?php echo base_url('admin/quiz'); ?>" class="nav-link"><?php echo lang('quiz_list'); ?> 
               </a>
            </li>
            <li class="<?php echo (uri_string() == 'admin/article/add') ? 'active' : ''; ?>">
               <a href="<?php echo base_url('admin/quiz/add'); ?>" class="nav-link"><?php echo lang('admin_add_quiz'); ?> 
            </a>
         </li>
      </ul>
   </li>

   

<li class="dropdown<?php echo (strstr(uri_string(), 'admin/pages')) ? ' active' : ''; ?>">
   <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
      <i class="fas fa-file"></i>
      <span><?php echo lang('dashboard_pages'); ?> </span>
   </a>
   <ul class="dropdown-menu">
      <li class="<?php echo (uri_string() == 'admin/pages') ? 'active' : ''; ?>">
         <a href="<?php echo base_url('admin/pages'); ?>" class="nav-link"> <?php echo lang('admin_page_list'); ?> 
      </a>
   </li>
   <li class="<?php echo (uri_string() == 'admin/pages/add') ? 'active' : ''; ?>">
      <a href="<?php echo base_url('admin/pages/add'); ?>" class="nav-link"><?php echo lang('admin_add_page'); ?> 
   </a>
</li>
</ul>
</li>


<li class="dropdown<?php echo (strstr(uri_string(), 'admin/testimonial')) ? ' active' : ''; ?>">
   <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
      <i class="fas fa-star-half-alt"></i>
      <span><?php echo lang('admin_testimonial'); ?></span>
   </a>
   <ul class="dropdown-menu">
      <li class="<?php echo (uri_string() == 'admin/testimonial') ? 'active' : ''; ?>">
         <a href="<?php echo base_url('admin/testimonial'); ?>" class="nav-link"> <?php echo lang('admin_testimonial'); ?> 
      </a>
   </li>
   <li class="<?php echo (uri_string() == 'admin/testimonial/add') ? 'active' : ''; ?>">
      <a href="<?php echo base_url('admin/testimonial/add'); ?>" class="nav-link"> <?php echo lang('admin_testimonial_add'); ?> 
   </a>
</li>
</ul>
</li>

<li class="dropdown<?php echo (strstr(uri_string(), 'admin/sponsors')) ? ' active' : ''; ?>">
   <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
      <i class="fas fa-allergies"></i>
      <span><?php echo lang('admin_sponsors'); ?></span>
   </a>
   <ul class="dropdown-menu">
      <li class="<?php echo (uri_string() == 'admin/sponsors') ? 'active' : ''; ?>">
         <a href="<?php echo base_url('admin/sponsors'); ?>" class="nav-link"> <?php echo lang('admin_sponsors'); ?> 
      </a>
   </li>
   <li class="<?php echo (uri_string() == 'admin/sponsors/add') ? 'active' : ''; ?>">
      <a href="<?php echo base_url('admin/sponsors/add'); ?>" class="nav-link"> <?php echo lang('admin_add_sponsors'); ?>
   </a>
</li>
</ul>
</li>

<li class="<?php echo (uri_string() == 'admin/footer') ? 'active' : ''; ?>">
   <a href="<?php echo base_url('/admin/footer'); ?>" class="nav-link">
      <i class="fab fa-foursquare"></i>
      <span><?php echo lang('footers_section'); ?></span>
   </a>
</li>

<li class="<?php echo (uri_string() == 'admin/contact') ? 'active' : ''; ?>">
   <a href="<?php echo base_url('/admin/contact'); ?>" class="nav-link">
      <i class="fas fa-headset"></i>
      <span><?php echo lang('contacts'); ?></span>
   </a>
</li>

<li class="dropdown<?php echo (strstr(uri_string(), 'admin/language')) ? ' active' : ''; ?>">
   <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
      <i class="fas fa-language"></i>
      <span><?php echo 'language'; ?></span>
   </a>
   <ul class="dropdown-menu">
      <li class="<?php echo (uri_string() == 'admin/language') ? 'active' : ''; ?>">
         <a href="<?php echo base_url('admin/language'); ?>" class="nav-link"><?php echo lang('language_list'); ?>
      </a>
   </li>
   <li class="<?php echo (uri_string() == 'admin/language/add') ? 'active' : ''; ?>">
      <a href="<?php echo base_url('admin/language/add'); ?>" class="nav-link"><?php echo lang('admin_add_language'); ?>
   </a>
</li>
<li class="<?php echo (uri_string() == 'admin/language/add_token') ? 'active' : ''; ?>">
   <a href="<?php echo base_url('admin/language/add_token'); ?>" class="nav-link"><?php echo lang('add_new_token') ?>
</a>
</li>
</ul>
</li>

<li class="<?php echo (uri_string() == 'admin/settings') ? 'active' : ''; ?>">
   <a href="<?php echo base_url('/admin/settings'); ?>" class="nav-link">
      <i class="fas fa-cog"></i>
      <span><?php echo lang('admin_settings'); ?></span>
   </a>
</li>
<li class="dropdown <?php echo (strstr(uri_string(), 'admin/users')) ? ' active' : ''; ?>">
   <a href="#" class="nav-link has-dropdown" data-toggle="dropdown">
      <i class="fas fa-columns"></i>
      <span><?php echo lang('dashboard_user'); ?></span>
   </a>
   <ul class="dropdown-menu">
      <li class="<?php echo (uri_string() == 'admin/users') ? 'active' : ''; ?>">
         <a href="<?php echo base_url('/admin/users'); ?>" class="nav-link"><?php echo lang('users_list'); ?>
      </a>
   </li>
   <li class="<?php echo (uri_string() == 'admin/users/add') ? 'active' : ''; ?>"><a href="<?php echo base_url('/admin/users/add'); ?>" class="nav-link"><?php echo lang('add_user'); ?></a></li>
</ul>
</li>
</ul>
</aside>
</div>
<?php // Main body ?>
<div class="main-content">
   <section class="section">
      <?php // Page title ?>
      <div class="section-header">
         <h1><?php echo xss_clean($page_header); ?></h1>
      </div>
      <div class="section-body"></div>

      <?php // Main content ?>
      <?php echo ($content); ?>
   </section>
</div>
<?php // Footer ?>
<footer class="main-footer">
   <div class="footer-left">
      <p class="text-muted">
         <?php echo lang('core_text_page_rendered'); ?>
         | PHP v<?php echo phpversion(); ?>
         | MySQL v<?php echo mysqli_get_client_version(); ?>
         | CodeIgniter v<?php echo xss_clean(CI_VERSION); ?>
         | <?php echo xss_clean($this->settings->site_name); ?> v<?php echo xss_clean($this->settings->site_version); ?>
      </p>
   </div>
   <div class="footer-right">
   </div>
</footer>

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

</div>
</div>
<?php if (isset($model_box)){
   echo xss_clean($model_box);
} ?>
<div class="modal fade" id="imagemodal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
   <div class="modal-dialog model_1000">
      <div class="modal-content">
         <div class="modal-header">
            <h4 class="modal-title" id="myModalLabel"><?php echo lang('model_image_preview') ?></h4>
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo lang('close') ?></span></button>
         </div>
         <div class="modal-body text-center">
            <img src="" id="imagepreview" >
         </div>
         <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo lang('close') ?></button>
         </div>
      </div>
   </div>
</div>
<!-- Button trigger modal -->
<div class="modal fade" tabindex="-1" role="dialog" id="model_box_content">
   <div class="modal-dialog " role="document">
      <div class="modal-content">
         <div class="modal-header">
            <h5 class="modal-title"><?php echo lang('model_box') ?></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
               <span aria-hidden="true">&times;</span>
            </button>
         </div>
         <div class="modal-body">
            <p><?php echo lang('no_data') ?></p>
         </div>
         <div class="modal-footer bg-whitesmoke br">
            <button type="button" class="btn btn-secondary" data-dismiss="modal"><?php echo lang('close') ?></button>
            <button type="button" class="btn btn-primary"><?php echo lang('welcome') ?></button>
         </div>
      </div>
   </div>
</div>
</div>
<!-- Button trigger modal -->


<script type="text/javascript">
   if(flash_message == 'undefined'){ var flash_message = ''; }
   if(flash_error == 'undefined'){ var flash_error = ''; }

   if(error_report == 'undefined'){ var error_report = ''; }

   if(flash_message )
   {
      new Noty({
         type: 'success',
         layout: 'topRight',
         text: flash_message,
         timeout : 5000,
         progressBar : true,
         theme    : 'metroui ',
         closeWith: ['click', 'button'],

      }).show();
   }

   if(flash_error)
   {
      new Noty({
         type: 'error',
         layout: 'topRight',
         text: flash_error,
         timeout : 5000,
         progressBar : true,
         theme    : 'mint',
         closeWith: ['click', 'button'],

      }).show();
   }       

   if(error_report)
   {
      new Noty({
         type: 'error',
         layout: 'topRight',
         text: error_report,
         timeout : 5000,
         progressBar : true,
         theme    : 'mint',
         closeWith: ['click', 'button'],

      }).show();
   }
</script>
</body>
</html>