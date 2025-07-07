<?php

use CodeIgniter\Router\RouteCollection;

$routes->get('/', 'DatabaseSwitcher::menu');
//login controlelr
$routes->get('/load/mysql', 'DatabaseSwitcher::loadMysql');
$routes->get('/load/mongodb', 'DatabaseSwitcher::loadMongo');

$routes->post('/registration','RegistrationController::registration' );
$routes->get('/registration','RegistrationController::registration' );

$routes->get('/login-form', 'LoginController::loginForm');  
$routes->post('/login', 'LoginController::login');        


$routes->get('/admin', "AdminController::adminPage");
$routes->post('/admin/change-role', "AdminController::changeRole");
$routes->post('/admin/add-category', "AdminController::addCategory");
$routes->post('/admin/add-product', "AdminController::addProduct");
$routes->post('/admin/add-table', "AdminController::addTable");


$routes->get('/gost', "GostController::index");
$routes->get('/meni/kategorija/', 'GostController::index');

$routes->get('/meni/kategorija/(:any)', 'GostController::PrikaziProizvode/$1');
$routes->post('/korpa/dodaj', 'CartController::dodaj');
$routes->post('/korpa/azuriraj', 'CartController::azuriraj');
$routes->post('/korpa/obrisi', 'CartController::obrisi');


$routes->post('/izabranSto','GostController::izaberiSto');
$routes->get('/stolovi', 'GostController::prikaziStolove');

$routes->post('/naruci', 'GostController::naruci');

$routes->get('/konobar', 'KonobarController::prikaziNarudzbine');
$routes->post('/konobar/promeni-status', 'KonobarController::promeniStatus');

$routes->get('/logout-user', 'LogoutController::userLogout');
$routes->get('/logout-all', 'LogoutController::fullLogout');



/**
 * @var RouteCollection $routes
 */
//$routes->get('/', 'Home::index');
