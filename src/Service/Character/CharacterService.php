<?php

declare(strict_types=1);

namespace App\Service\Character;

use App\Exception\Character;

final class CharacterService extends Base
{
    public function getAllCharacters(): array
    {
        return $this->getCharacterRepository()->getAllCharacters();
    }

    public function getAll(int $userId): array
    {
        return $this->getCharacterRepository()->getAll($userId);
    }

    public function getComics(int $characterId): array
    {
        return $this->getCharacterRepository()->getComics($characterId);
    }

    public function getEvents(int $characterId): array
    {
        return $this->getCharacterRepository()->getEvents($characterId);
    }

    public function getSeries(int $characterId): array
    {
        return $this->getCharacterRepository()->getSeries($characterId);
    }

    public function getStories(int $characterId): array
    {
        return $this->getCharacterRepository()->getStories($characterId);
    }

    public function getOne(int $characterId, int $userId): object
    {
        if (self::isRedisEnabled() === true) {
            $character = $this->getCharacterFromCache($characterId, $userId);
        } else {
            $character = $this->getCharacterFromDb($characterId, $userId);
        }

        return $character;
    }

    public function search(string $charactersName, int $userId, $status): array
    {
        if ($status !== null) {
            $status = (int) $status;
        }

        return $this->getCharacterRepository()->search($charactersName, $userId, $status);
    }

    public function create(array $input): object
    {
        $data = json_decode(json_encode($input), false);
        if (! isset($data->name)) {
            throw new Character('The field "name" is required.', 400);
        }
        self::validateCharacterName($data->name);
        $data->description = $data->description ?? null;
        $data->modified = date('Y-m-d H:i:s');
        $data->userId = (int) $data->decoded->sub;
        $character = $this->getCharacterRepository()->create($data);
        if (self::isRedisEnabled() === true) {
            $this->saveInCache($character->id, $character->userId, $character);
        }

        return $character;
    }

    public function update(array $input, int $characterId): object
    {
        $data = $this->validateCharacter($input, $characterId);
        $character = $this->getCharacterRepository()->update($data);
        if (self::isRedisEnabled() === true) {
            $this->saveInCache($character->id, (int) $data->userId, $character);
        }

        return $character;
    }

    public function delete(int $characterId, int $userId): void
    {
        $this->getCharacterFromDb($characterId, $userId);
        $this->getCharacterRepository()->delete($characterId, $userId);
        if (self::isRedisEnabled() === true) {
            $this->deleteFromCache($characterId, $userId);
        }
    }

    private function validateCharacter(array $input, int $characterId): object
    {
        $character = $this->getCharacterFromDb($characterId, (int) $input['decoded']->sub);
        $data = json_decode(json_encode($input), false);
        if (! isset($data->name) && ! isset($data->status)) {
            throw new Character('Enter the data to update the character.', 400);
        }
        if (isset($data->name)) {
            $character->name = self::validateCharacterName($data->name);
        }
        if (isset($data->description)) {
            $character->description = $data->description;
        }
        if (isset($data->status)) {
            $character->status = self::validateCharacterStatus($data->status);
        }
        $character->userId = (int) $data->decoded->sub;

        return $character;
    }
}
