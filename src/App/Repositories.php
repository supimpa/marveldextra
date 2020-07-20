<?php

declare(strict_types=1);

use App\Repository\NoteRepository;
use App\Repository\CharacterRepository;
use App\Repository\UserRepository;
use Psr\Container\ContainerInterface;

$container['user_repository'] = static function (
    ContainerInterface $container
): UserRepository {
    return new UserRepository($container->get('db'));
};

$container['character_repository'] = static function (
    ContainerInterface $container
): CharacterRepository {
    return new CharacterRepository($container->get('db'));
};