<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Users Language File
 */

// Titles
$lang['users title forgot']                   = "Forgot Password";
$lang['users title login']                    = "Login";
$lang['users title profile']                  = "Profile";
$lang['users title register']                 = "Register";
$lang['users title user_add']                 = "Add User";
$lang['users title user_delete']              = "Confirm Delete User";
$lang['users title user_edit']                = "Edit User";
$lang['users title user_list']                = "User List";

$lang['category title category_list']         = "Category List";
$lang['category title category_add']          = "Add Category";
$lang['category title category_edit']         = "Edit Category";

$lang['customfield title custom_list']        = "Custom Field List";
$lang['customfield title customfield_add']    = "Add Custom Field";
$lang['customfield title customfield_edit']   = "Edit Custom Field";

$lang['customfield title catcustom_list']     = "Custom Field Categories List";
$lang['customfield title catcustomfield_add'] = "Add Custom Field Categories";
$lang['customfield title catcustomfield_edit']= "Edit Custom Field Categories";

$lang['category title custom label']          = "Set Label";

$lang['brand title brand_list']            	  = "Brand List";
$lang['brand title brand_add']                = "Add Brand";
$lang['brand title brand_edit']               = "Edit Brand";

$lang['market title market_list']             = "Market List";
$lang['market title market_add']              = "Add Market";
$lang['market title market_edit']             = "Edit Market";

$lang['product title product_list']           = "Product List";
$lang['product title product_add']            = "Add Product";
$lang['product title product_edit']           = "Edit Product";

$lang['product title variant_list']           = "Variant List";
$lang['product title variant_add']            = "Add Variant";
$lang['product title variant_edit']           = "Edit Variant";

// Buttons
$lang['users button add_new_user']            = "Add New User";
$lang['users button register']                = "Create Account";
$lang['users button reset_password']          = "Reset Password";
$lang['users button login_try_again']         = "Try Again";

// Tooltips
$lang['users tooltip add_new_user']           = "Create a brand new user.";

// Links
$lang['users link forgot_password']           = "Forgot your password?";
$lang['users link register_account']          = "Register for an account.";

// Table Columns
$lang['users col first_name']                 = "First Name";
$lang['users col is_admin']                   = "Admin";
$lang['users col last_name']                  = "Last Name";
$lang['users col user_id']                    = "ID";
$lang['users col username']                   = "Username";

// Form Inputs
$lang['users input email']                    = "Email";
$lang['users input first_name']               = "First Name";
$lang['users input is_admin']                 = "Is Admin";
$lang['users input language']                 = "Language";
$lang['users input last_name']                = "Last Name";
$lang['users input password']                 = "Password";
$lang['users input password_repeat']          = "Repeat Password";
$lang['users input user_image']               = "User Image";
$lang['users input status']                   = "Status";
$lang['users input username']                 = "Username";
$lang['users input username_email']           = "Username or Email";

$lang['category input title']                 = "Title";
$lang['category input parentcategory']        = "Parent Category";
$lang['category input categorydesc']          = "Description";
$lang['category input categoryicon']          = "Icon";
$lang['category input categoryimage']         = "Upload";

$lang['custom input displayname']             = "Name On Front";
$lang['custom input customlabel']			  = "Name On Admin Panel";
$lang['custom input categorycustomfield']	  = "Custom Field Group";
$lang['custom input customtype']			  = "Input Field Type";
$lang['custom input isnum']			          = "Is Numeric";
$lang['custom input isreq']			          = "Is Required";
$lang['custom input isvariant']			      = "Is Variant";
$lang['custom input is_date']			      = "Is Date";
$lang['custom input isforfront']			  = "Display On Front";
$lang['custom input isforlist']			  	  = "Display On List Page";
$lang['custom input isforfilter']		      = "Available For Filter";
$lang['custom input custommin']			      = "Min Value";
$lang['custom input custommax']			      = "Max Value";
$lang['custom input customhint']			  = "Hint For This Field";
$lang['custom input customoption']			  = "Options"; 
$lang['custom input validation']			  = "Field Validation"; 


$lang['custom input catcustomname']           = "Title";
$lang['custom input catcustomicon']			  = "Icon";


$lang['brand input name']                     = "Title";
$lang['brand input description']              = "Description";
$lang['brand input upload']                   = "Upload";
$lang['market input name']                    = "Title";
$lang['market input description']             = "Description";
$lang['market input upload']                  = "Upload";
$lang['product input name']                   = "Title";
$lang['product input description']            = "Description";
$lang['product input upload']                 = "Upload";
$lang['product input sku']					  = "Product Name";
$lang['product input metakeyword']			  = "Meta Keyword";
$lang['product input metadescription']		  = "Meta Description";	
$lang['product input category']			      = "Select Category";
$lang['product input brand']			  	  = "Select Brand";
$lang['product input customfield']			  = "Custom Field";
$lang['product input marketprice']			  = "Market Price";

// Help
$lang['users help passwords']                 = "Only enter passwords if you want to change it.";

// Messages
$lang['users msg add_user_success']           = "%s was successfully added!";
$lang['users msg delete_confirm']             = "Are you sure you want to delete <strong>%s</strong>? This can not be undone.";
$lang['users msg delete_user']                = "You have succesfully deleted <strong>%s</strong>!";
$lang['users msg edit_profile_success']       = "Your profile was successfully modified!";
$lang['users msg edit_user_success']          = "%s was successfully modified!";
$lang['users msg register_success']           = "Thanks for registering, %s! Check your email for a confirmation message. Once
                                                 your account has been verified, you will be able to log in with the credentials
                                                 you provided.";
$lang['users msg password_reset_success']     = "Your password has been reset, %s! Please check your email for your new temporary password.";
$lang['users msg validate_success']           = "Your account has been verified. You may now log in to your account.";
$lang['users msg email_new_account']          = "<p>Thank you for creating an account at %s. Click the link below to validate your
                                                 email address and activate your account.<br /><br /><a href=\"%s\">%s</a></p>";
$lang['users msg email_new_account_title']    = "New Account for %s";
$lang['users msg email_password_reset']       = "<p>Your password at %s has been reset. Click the link below to log in with your
                                                 new password:<br /><br /><strong>%s</strong><br /><br /><a href=\"%s\">%s</a>
                                                 Once logged in, be sure to change your password to something you can
                                                 remember.</p>";
$lang['users msg email_password_reset_title'] = "Password Reset for %s";

// Errors
$lang['users error add_user_failed']          = "%s could not be added!";
$lang['users error delete_user']              = "<strong>%s</strong> could not be deleted!";
$lang['users error edit_profile_failed']      = "Your profile could not be modified!";
$lang['users error edit_user_failed']         = "%s could not be modified!";
$lang['users error email_exists']             = "The email <strong>%s</strong> already exists!";
$lang['users error email_not_exists']         = "That email does not exists!";
$lang['users error invalid_login']            = "Invalid username or password";
$lang['users error password_reset_failed']    = "There was a problem resetting your password. Please try again.";
$lang['users error register_failed']          = "Your account could not be created at this time. Please try again.";
$lang['users error user_id_required']         = "A numeric user ID is required!";
$lang['users error user_not_exist']           = "That user does not exist!";
$lang['users error username_exists']          = "The username <strong>%s</strong> already exists!";
$lang['users error validate_failed']          = "There was a problem validating your account. Please try again.";
$lang['users error too_many_login_attempts']  = "You've made too many attempts to log in too quickly. Please wait %s seconds and try again.";
