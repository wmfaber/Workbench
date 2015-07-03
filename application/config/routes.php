<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require_once(getcwd().'/application/assets/function/function.php');

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

$active_modules = get_active_modules();

foreach($active_modules as $module => $setting){
	if($setting['module']['status']){
		$route['(?i)('.$module.')'] 									= $module.'/'.$module.'/'.$setting['module']['default_method'];
		$route['(?i)('.$module.')/add'] 							= $module.'/'.$module.'/form';
		$route['(?i)('.$module.')/edit/(:any)'] 			= $module.'/'.$module.'/form/$i';
		$route['(?i)('.$module.')/update/(:any)'] 		= $module.'/'.$module.'/form/update/$i';
		$route['(?i)('.$module.')/insert'] 						= $module.'/'.$module.'/form/insert';
		$route['(?i)('.$module.')/ajax/(:any)'] 			= $module.'/'.$module.'/ajax/$i';
		$route['(?i)('.$module.')/ajax'] 			= $module.'/'.$module.'/ajax/';
		$route['(?i)('.$module.')/upload'] 						= $module.'/'.$module.'/upload';
		$route['(?i)('.$module.')/upload_form'] 			= $module.'/'.$module.'/upload_form';
		$route['(?i)('.$module.')/detail/(:any)'] 		= $module.'/'.$module.'/detail/$i';
	}else{
		continue;
	}
}
		$route['(?i)(User)/login'] 								= 'User/Login/login';
		$route['(?i)(User)/registreer'] 					= 'User/User/register';
		$route['(?i)(login)'] 										= 'Login/Login';
		$route['(?i)(Register)'] 									= 'Register/Register/form';
		$route['(?i)(Register)/verify/(:any)'] 		= 'Register/Register/verify/$i';
		$route['(?i)(langswitch)/switchLanguage/(:any)'] 		= 'LangSwitch/switchLanguage/$2';
		

/*
$route['(?i)(Judoka)'] 									= 'Judoka/Judoka/view';
$route['(?i)(Judoka)/(:any)'] 					= 'Judoka/Judoka/form/$i';
$route['(?i)(Judoka)/add'] 							= 'Judoka/Judoka/form';
$route['(?i)(Judoka)/update/(:any)'] 		= 'Judoka/Judoka/update/$i';
$route['(?i)(Judoka)/insert'] 					= 'Judoka/Judoka/insert';
*/

//$route['Judoka/(:any)'] = 'judoka/judoka';
$route['default_controller'] = 'Home';	
//$route['(:any)'] = 'entry/load/$1';

//$route['default_controller'] = 'welcome';