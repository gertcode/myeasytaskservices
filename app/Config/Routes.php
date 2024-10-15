<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

$routes->group('api', function($routes) {
    // Rutas de autenticación
    $routes->post('login', 'AuthController::login');
    $routes->post('register', 'AuthController::register');
    $routes->post('recuperar-contrasena', 'AuthController::recuperarContrasena');

    // Rutas protegidas
    $routes->group('', ['filter' => 'auth'], function($routes) {
        // Equipos
        $routes->post('equipos', 'EquipoController::create');
        $routes->get('equipos', 'EquipoController::index');
        $routes->get('equipos/(:num)', 'EquipoController::show/$1');
        $routes->post('equipos/(:num)/invitacion', 'EquipoController::generarInvitacion/$1');
        $routes->post('equipos/mis-equipos', 'EquipoController::getTeamsByUser');

        // Invitaciones
        $routes->post('invitaciones/usar', 'InvitacionController::usarCodigo');

        // Tareas
        $routes->post('tareas', 'TareaController::create');
        $routes->get('tareas', 'TareaController::index');
        $routes->get('tareas/(:num)', 'TareaController::show/$1');
        $routes->put('tareas/(:num)', 'TareaController::update/$1');
        $routes->delete('tareas/(:num)', 'TareaController::delete/$1');
        $routes->post('tareas/(:num)/asignar', 'TareaController::asignarUsuarios/$1');

        // Historial de Tareas
        $routes->get('tareas/(:num)/historial', 'HistorialTareaController::index/$1');

        // Notificaciones
        $routes->get('notificaciones', 'NotificacionController::index');
        $routes->put('notificaciones/(:num)', 'NotificacionController::marcarComoLeido/$1');

        // Otros endpoints...
    });
});
