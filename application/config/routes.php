<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/



$route['default_controller']   					= 'Home_Controller';
$route['404_override']         					= 'errors/error404';
$route['translate_uri_dashes'] 					= TRUE;

$route['login']                					= 'user/login';
$route['logout']               					= 'user/logout';
//=======================================================================================
//================================= Admin Routes ========================================
//=======================================================================================
$route['admin']                					= 'admin/dashboard';

//================================= Quiz Routes ========================================
$route['admin/quiz']                			= 'admin/QuizController/index';
$route['admin/quiz/add']                		= 'admin/QuizController/add';
$route['admin/quiz/update/(:any)']           	= 'admin/QuizController/update/$1';
$route['admin/quiz/copy/(:any)']           		= 'admin/QuizController/copy/$1';
$route['admin/quiz/delete/(:any)']           	= 'admin/QuizController/delete/$1';
$route['admin/quiz/dropzone-file']    			= 'admin/QuizController/quiz_upload_file';
$route['admin/quiz/dropzone-file-remove']    	= 'admin/QuizController/dropzone_quiz_file_remove';
$route['admin/quiz/delete-image/(:any)']    	= 'admin/QuizController/delete_featured_image/$1';
$route['admin/quiz/image-resize/(:any)']    	= 'admin/QuizController/image_resize_library/$1';
$route['admin/quiz/question-list/(:any)']    	= 'admin/QuizController/question_list/$1';
$route['admin/quiz/questions/(:any)'] 			= 'admin/QuizController/questions/$1';

$route['admin/sp/get-sp-list'] 					= 'admin/sponsors/admin_sp_list';
$route['admin/report/(:any)'] 					= 'admin/ReportController/index/$1';
$route['admin/report/(:any)'] 					= 'admin/ReportController/index/$1';
$route['admin/report/summary/(:any)'] 			= 'admin/ReportController/summary/$1';


//================================= Company Routes ========================================

$route['admin/questions']                				= 'admin/QuestionController/index';
$route['admin/questions/add/(:any)']                	= 'admin/QuestionController/add/$1';
$route['admin/questions/update/(:any)/(:any)']          = 'admin/QuestionController/update/$1/$2';
$route['admin/questions/copy/(:any)']           		= 'admin/QuestionController/copy/$1';
$route['admin/questions/delete/(:any)']           		= 'admin/QuestionController/delete/$1';
$route['admin/questions/dropzone-file']    				= 'admin/QuestionController/questions_upload_file';
$route['admin/questions/dropzone-file-remove']    		= 'admin/QuestionController/dropzone_questions_file_remove';
$route['admin/questions/delete-image/(:any)']    		= 'admin/QuestionController/delete_questions_image/$1';
$route['admin/questions/get-questions-field/(:any)'] 	= 'admin/QuestionController/get_questions_fields/$1';


$route['my/history'] 									= 'History_Controller/history';
$route['my/history/(:num)'] 							= 'History_Controller/history/$1';

$route['quiz/leader-board/(:num)'] 						= 'History_Controller/leader_board/$1';

$route['my/test/summary/(:num)'] 						= 'Test_Controller/test_summary/$1';


$route['admin/quiz/import'] 							= 'admin/Excel_import/index';
$route['admin/quiz/import/(:num)'] 						= 'admin/Excel_import/index/$1';


//================================= Other Routes ======================================== 
$route['sitemap\.xml']         					= 'sitemap';

$route['instruction/(:any)'] 		        	= 'Quiz_Controller/instruction/$1';
$route['test/(:num)/(:num)']		 		    = 'Test_Controller/test/$1/$2';
$route['result/(:num)']		 		    		= 'Test_Controller/test_result/$1';
$route['test-submit-request']		 		    = 'Test_Controller/test_submit_request';
$route['category/(:any)/(:any)']		 		= 'Quiz_Controller/category/$1/$2';
$route['category/(:any)']		 		    	= 'Quiz_Controller/category/$1';
$route['like/(:any)']							= 'Quiz_Controller/like_quiz'; 
$route['dislike/(:any)']						= 'Quiz_Controller/like_quiz_delete'; 
$route['quiz-detail/(:any)']					= 'Quiz_Controller/quiz_detail/$1'; 
$route['rating']								= 'Quiz_Controller/submit_rating'; 

$route['search/(:any)']         				= 'Filter_Controller/search/$1';
$route['product/(:any)/(:any)']         		= 'Product_detail_Controller/index/$1/$2';
$route['compare/(:any)']         				= 'Compare_Controller/index/$1';
$route['compare-product/(:any)/(:any)']         = 'Compare_Controller/compare_product/$1/$2';
$route['compare-product-remove/(:any)/(:any)']  = 'Compare_Controller/compare_product_remove/$1/$2';
$route['compare-product-nav-data']  			= 'Compare_Controller/compare_product_nav_data';
$route['admin/admin-setting']  					= 'Admin_setting_Controller/index';

$route['remove-from-favourite/(:num)']  		= 'Profile/remove_from_favourite/$1';
$route['add-to-fav-product/(:num)']  			= 'Home_Controller/add_to_fav_product/$1';
$route['page/(:any)']         					= 'Page_Controller/index/$1';
$route['pages/(:any)']         					= 'Contant_Controller/index/$1';

$route['stripe/checkout/quiz/(:any)']         			= 'Stripe/index/$1';
$route['stripe/checkout/quiz-pay/(:any)']         		= 'Stripe/check/$1';
$route['stripe/quiz-pay/payment-success/(:any)/(:any)'] = 'Stripe/payment_success/$1/$2';
$route['stripe/quiz-pay/payment-fail/(:any)']         	= 'Stripe/payment_error/$1';


$route['quiz-pay/payment-mode/(:any)']         			= 'payment/payment_mode/$1';

$route['paypal/checkout/quiz/(:any)']         			= 'payment/index/$1';
$route['paypal/checkout/quiz-pay/(:any)']         		= 'payment/subscribe/$1';
$route['paypal/payment/quiz-pay/(:any)']         		= 'payment/create_payment/$1';
$route['paypal/quiz-pay/payment-success/(:any)'] 		= 'payment/success/$1';
$route['paypal/quiz-pay/pay-successfuly/(:any)'] 		= 'payment/success_payment/$1';
$route['paypal/quiz-pay/payment-fail/(:any)']         	= 'payment/cancel/$1';

$route['paypal/quiz-pay/payment-status/(:any)/(:any)']         	= 'payment/paypal_payment_view/$1/$2';
