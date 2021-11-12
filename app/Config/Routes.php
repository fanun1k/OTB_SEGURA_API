<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index');

$routes->resource('restAlertType',['controller'=>'RestAlertType']);
$routes->get('restAlert/alertsbyuser/(:num)/(:num)/(:num)/(:num)','RestAlerts::alertsByUser/$1/$2/$3/$4');
$routes->resource('restAlert',['controller'=>'RestAlerts']);
$routes->resource('restAlarm',['controller'=>'RestAlarms']);
$routes->resource('restCamera',['controller' => 'RestCameras']);
$routes->post("restUser/login","RestUsers::login");
$routes->post('restUser/recoverypass','RestUsers::recoveryPassword');
$routes->resource('restUser', ['placeholder' => '(:num)', 'controller'=>'RestUsers']);
$routes->get('restUser/byotb/(:num)/(:num)/(:num)', 'RestUsers::listusersbyotb/$1/$2/$3', );
$routes->post('restUser/setadmin','RestUsers::SetAdmin');
$routes->post('restUser/removeadmin','RestUsers::RemoveAdmin');
$routes->post('restUser/removeotb','RestUsers::RemoveOTB');
$routes->POST('restOtb/joinOtb','RestOtbs::joinOtb');
$routes->resource('restOtb',['placeholder' => '(:num)','controller'=>'RestOtbs']);
$routes->post('restUser/upload', 'RestUsers::uploadfile');
$routes->get('restUser/download/(:num)', 'RestUsers::downloadFile/$1');
$routes->post('restUser/bytesToImage', 'RestUsers::GetBytesToImage');
$routes->post('restUser/verifyEmail', 'RestUsers::verifyEmailNewUser');
$routes->get('restUser/(:any)/(:num)', 'RestUsers::index/$1/$2');
$routes->get('restAlert/(:num)/(:num)/(:num)', 'RestAlerts::index/$1/$2/$3');
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
