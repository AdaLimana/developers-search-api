<?php

declare(strict_types=1);

use App\Handler\CandidatoCreateHandler;
use App\Handler\CandidatoDeleteHandler;
use App\Handler\CandidatoUpdateHandler;
use App\Handler\LoginHandler;
use App\Handler\LogoutHandler;
use App\Handler\RecrutadorCreateHandler;
use App\Handler\RecrutadorDeleteHandler;
use App\Handler\RecrutadorHandler;
use App\Handler\RecrutadorSessionHandler;
use App\Handler\RecrutadorUpdateHandler;
use App\Handler\WithoutSessionHandler;
use Mezzio\Application;
use Mezzio\Authentication\AuthenticationMiddleware;
use Mezzio\MiddlewareFactory;
use Psr\Container\ContainerInterface;

/**
 * FastRoute route configuration
 *
 * @see https://github.com/nikic/FastRoute
 *
 * Setup routes with a single request method:
 *
 * $app->get('/', App\Handler\HomePageHandler::class, 'home');
 * $app->post('/album', App\Handler\AlbumCreateHandler::class, 'album.create');
 * $app->put('/album/{id:\d+}', App\Handler\AlbumUpdateHandler::class, 'album.put');
 * $app->patch('/album/{id:\d+}', App\Handler\AlbumUpdateHandler::class, 'album.patch');
 * $app->delete('/album/{id:\d+}', App\Handler\AlbumDeleteHandler::class, 'album.delete');
 *
 * Or with multiple request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class, ['GET', 'POST', ...], 'contact');
 *
 * Or handling all request methods:
 *
 * $app->route('/contact', App\Handler\ContactHandler::class)->setName('contact');
 *
 * or:
 *
 * $app->route(
 *     '/contact',
 *     App\Handler\ContactHandler::class,
 *     Mezzio\Router\Route::HTTP_METHOD_ANY,
 *     'contact'
 * );
 */
return static function (Application $app, MiddlewareFactory $factory, ContainerInterface $container): void {

    //AUTHENTICATION
    $app->get(
        '/api/without-session', 
        WithoutSessionHandler::class, 
        'without-session-get'
    );

    $app->get(
        '/api/logout',
        [
            AuthenticationMiddleware::class,
            LogoutHandler::class
        ],
        'ogout.get'
    );

    $app->post(
        '/api/login',
        LoginHandler::class,
        'login.post'
    );

    $app->get(
        '/api/recrutador-session',
        [
            AuthenticationMiddleware::class,
            RecrutadorSessionHandler::class,
        ],
        'recrutador-session.get'
    );

    //RECRUTADORES
    $app->get(
        '/api/recrutadores[/{id:\d+}]', 
        [
            AuthenticationMiddleware::class,
            RecrutadorHandler::class
        ], 
        'recrutador-get'
    );

    $app->post(
        '/api/recrutadores', 
        [
            AuthenticationMiddleware::class,
            RecrutadorCreateHandler::class
        ], 
        'recrutador-post'
    );

    $app->put(
        '/api/recrutadores/{id:\d+}', 
        [
            AuthenticationMiddleware::class,
            RecrutadorUpdateHandler::class
        ], 
        'recrutador-put'
    );
    
    $app->delete(
        '/api/recrutadores/{id:\d+}', 
        [
            AuthenticationMiddleware::class,
            RecrutadorDeleteHandler::class
        ], 
        'recrutador-delete'
    );

    //CANDIDATOS
    $app->get(
        '/api/candidatos[/{id:\d+}]', 
        [
            AuthenticationMiddleware::class,
            CandidatoHandler::class
        ], 
        'candidato-get'
    );

    $app->post(
        '/api/candidatos', 
        [
            AuthenticationMiddleware::class,
            CandidatoCreateHandler::class
        ],
         'candidato-post'
    );

    $app->put(
        '/api/candidatos/{id:\d+}', 
        [
            AuthenticationMiddleware::class,
            CandidatoUpdateHandler::class
        ], 
        'candidato-put'
    );

    $app->delete(
        '/api/candidatos/{id:\d+}', 
        [
            AuthenticationMiddleware::class,
            CandidatoDeleteHandler::class
        ], 
        'candidato-delete'
    );
};