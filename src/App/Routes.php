<?php

declare(strict_types=1);

use App\Controller\Character;
use App\Controller\User;
use App\Middleware\Auth;

$app->get('/', 'App\Controller\DefaultController:getHelp');
$app->get('/status', 'App\Controller\DefaultController:getStatus');
$app->post('/login', \App\Controller\User\Login::class);

$app->group('/api/v1', function () use ($app): void {
    $app->group('/public/characters', function () use ($app): void {
        $app->get('', Character\GetAll::class);
        $app->post('', Character\Create::class);
        $app->get('/search/[{query}]', Character\Search::class);
        $app->get('/{id}', Character\GetOne::class);
        $app->put('/{id}', Character\Update::class);
        $app->delete('/{id}', Character\Delete::class);
        $app->get('/{id}/comics', Character\GetComics::class);
        $app->get('/{id}/events', Character\GetEvents::class);
        $app->get('/{id}/series', Character\GetSeries::class);
        $app->get('/{id}/stories', Character\GetStories::class);
    })->add(new Auth());

    $app->group('/users', function () use ($app): void {
        $app->get('', User\GetAll::class)->add(new Auth());
        $app->post('', User\Create::class);
        $app->get('/search/[{query}]', User\Search::class)->add(new Auth());
        $app->get('/{id}', User\GetOne::class)->add(new Auth());
        $app->put('/{id}', User\Update::class)->add(new Auth());
        $app->delete('/{id}', User\Delete::class)->add(new Auth());
    });
});
