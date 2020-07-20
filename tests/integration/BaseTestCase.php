<?php

declare(strict_types=1);

namespace Tests\integration;

use Slim\App;
use Slim\Http\Request;
use Slim\Http\Response;
use Slim\Http\Environment;
use Psr\Http\Message\ResponseInterface;

class BaseTestCase extends \PHPUnit\Framework\TestCase
{
    public static $jwt = 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJzdWIiOjksImVtYWlsIjoidmljdG9yc2FudGlhZ29Ab3V0bG9vay5jb20iLCJuYW1lIjoiVmljdG9yIFNhbnRpYWdvIiwiaWF0IjoxNTk1MTg4NDQ3LCJleHAiOjE1OTU3OTMyNDd9.FcAoWYMjws6VdjUC86xNDGlObwhfdBkKF-O0qs10mdg';

    public function runApp(
        string $requestMethod,
        string $requestUri,
        array $requestData = null
    ): ResponseInterface {

        $environment = Environment::mock(
            [
                'REQUEST_METHOD' => $requestMethod,
                'REQUEST_URI' => $requestUri
            ]
        );

        $request = Request::createFromEnvironment($environment);
        $request = $request->withHeader('Authorization', self::$jwt);

        if (isset($requestData)) {
            $request = $request->withParsedBody($requestData);
        }

        $baseDir = __DIR__ . '/../../';
        $dotenv = new \Dotenv\Dotenv($baseDir);
        $envFile = $baseDir . '.env';
        if (file_exists($envFile)) {
            $dotenv->load();
        }

        $settings = require __DIR__ . '/../../src/App/Settings.php';

        $app = new App($settings);

        $container = $app->getContainer();

        require __DIR__ . '/../../src/App/Dependencies.php';
        require __DIR__ . '/../../src/App/Services.php';
        require __DIR__ . '/../../src/App/Repositories.php';
        require __DIR__ . '/../../src/App/Routes.php';

        return $app->process($request, new Response());
    }
}
