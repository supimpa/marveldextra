<?php

declare(strict_types=1);

use App\Service\Note;
use App\Service\Character\CharacterService;
use App\Service\User\UserService;
use Psr\Container\ContainerInterface;

$container['user_service'] = static function (
    ContainerInterface $container
): UserService {
    return new UserService(
        $container->get('user_repository'),
        $container->get('redis_service')
    );
};

$container['character_service'] = static function (
    ContainerInterface $container
): CharacterService {
    return new CharacterService(
        $container->get('character_repository'),
        $container->get('redis_service')
    );
};