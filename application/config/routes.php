<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// root route of apply controller
$route['applicant'] = 'Apply/index';

// get applicant by vacancy
$route['applicant/(:any)'] = 'Apply/index/$1';
$route['applicant/list/(:any)/(:any)'] = 'Apply/index/$1/$2';

// get applicant details
$route['applicant/id/(:any)'] = 'Apply/applicantDetails/$1';

$route['applicant/get/session'] = 'ApplyApplicant/index';

// testing
$route['testing'] = 'Apply/testing';

// get latest vacancies
$route['latest'] = 'Vacancies/latest_vacancies';

// sync latest vacancies
$route['latest/sync'] = 'Vacancies/synchronize';

// search latest vacancies
$route['latest/search'] = 'Vacancies/search_vacancy';

// get divisi
$route['divisi'] = 'Vacancies/list_divisi';
// get divisi by id
$route['divisi/id/(:any)'] = 'Vacancies/get_divisi_by_id/$1';
// sort vacancy by divisi
$route['divisi/sort/(:any)'] = 'Vacancies/sort_by_divisi/$1';

// get vacancies by divisi
$route['vacancies/divisi/(:any)/(:any)'] = 'Vacancies/vacancies_per_divisi/$1/$2';

// sort vacancies by divisi
$route['vacancies/filter/divisi/(:any)'] = 'Vacancies/vacancies_sort_per_divisi/$1';

// get detail vacancy
$route['detail/id/(:any)'] = 'Vacancies/detail_vacancy/$1';

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
|	https://codeigniter.com/user_guide/general/routing.html
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
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
