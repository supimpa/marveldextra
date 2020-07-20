<?php

declare(strict_types=1);

namespace App\Service\Character;

use App\Exception\Character;
use App\Repository\CharacterRepository;
use App\Service\BaseService;
use App\Service\RedisService;
use Respect\Validation\Validator as v;

abstract class Base extends BaseService
{
    private const REDIS_KEY = 'character:%s:user:%s';

    protected $characterRepository;

    protected $redisService;

    public function __construct(
        CharacterRepository $characterRepository,
        RedisService $redisService
    ) {
        $this->characterRepository = $characterRepository;
        $this->redisService = $redisService;
    }

    protected function getCharacterRepository(): CharacterRepository
    {
        return $this->characterRepository;
    }

    protected static function validateCharacterName(string $name): string
    {
        if (! v::length(1, 100)->validate($name)) {
            throw new Character('Invalid name.', 400);
        }

        return $name;
    }

    protected static function validateCharacterStatus(int $status): int
    {
        if (! v::numeric()->between(0, 1)->validate($status)) {
            throw new Character('Invalid status', 400);
        }

        return $status;
    }

    protected function getCharacterFromCache(int $characterId, int $userId): object
    {
        $redisKey = sprintf(self::REDIS_KEY, $characterId, $userId);
        $key = $this->redisService->generateKey($redisKey);
        if ($this->redisService->exists($key)) {
            $character = $this->redisService->get($key);
        } else {
            $character = $this->getCharacterFromDb($characterId, $userId);
            $this->redisService->setex($key, $character);
        }

        return $character;
    }

    protected function getCharacterFromDb(int $characterId, int $userId): object
    {
        return $this->getCharacterRepository()->checkAndGetCharacter($characterId, $userId);
    }

    protected function saveInCache(int $characterId, int $userId, object $character): void
    {
        $redisKey = sprintf(self::REDIS_KEY, $characterId, $userId);
        $key = $this->redisService->generateKey($redisKey);
        $this->redisService->setex($key, $character);
    }

    protected function deleteFromCache(int $characterId, int $userId): void
    {
        $redisKey = sprintf(self::REDIS_KEY, $characterId, $userId);
        $key = $this->redisService->generateKey($redisKey);
        $this->redisService->del([$key]);
    }
}
