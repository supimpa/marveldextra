<?php

declare(strict_types=1);

namespace App\Controller;

use Slim\Http\Request;
use Slim\Http\Response;

final class DefaultController extends BaseController
{
    public const API_VERSION = '1.0';

    public function getHelp(Request $request, Response $response): Response
    {
        $url = getenv('APP_DOMAIN');
        $endpoints = [
            'users' => $url . '/api/v1/users',
            'characters' => $url . '/api/v1/public/characters',
            'docs' => $url . '/docs/index.html',
            'status' => $url . '/status',
            'this help' => $url . '',
        ];
        $message = [
            'endpoints' => $endpoints,
            'version' => self::API_VERSION,
            'timestamp' => time(),
        ];

        return $this->jsonResponse($response, 'success', $message, 200);
    }

    public function getStatus(Request $request, Response $response): Response
    {
        $status = [
            'stats' => $this->getDbStats(),
            'MySQL' => 'OK',
            'Redis' => $this->checkRedisConnection(),
            'version' => self::API_VERSION,
            'timestamp' => time(),
        ];

        return $this->jsonResponse($response, 'success', $status, 200);
    }

    private function getDbStats(): array
    {
        $userService = $this->container->get('user_service');
        $characterService = $this->container->get('character_service');

        return [
            'users' => count($userService->getAll()),
            'characters' => count($characterService->getAllCharacters())
        ];
    }

    private function checkRedisConnection(): string
    {
        $redis = 'Disabled';
        if (self::isRedisEnabled() === true) {
            $redisService = $this->container->get('redis_service');
            $key = $redisService->generateKey('test:status');
            $redisService->set($key, new \stdClass());
            $redis = 'OK';
        }

        return $redis;
    }
}
