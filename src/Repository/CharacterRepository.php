<?php

declare(strict_types=1);

namespace App\Repository;

use App\Exception\Character;

final class CharacterRepository extends BaseRepository
{
    public function checkAndGetCharacter(int $characterId, int $userId): object
    {
        $query = '
            SELECT * FROM `characters` WHERE `id` = :id AND `userId` = :userId
        ';
        $statement = $this->getDb()->prepare($query);
        $statement->bindParam('id', $characterId);
        $statement->bindParam('userId', $userId);
        $statement->execute();
        $character = $statement->fetchObject();
        if (! $character) {
            throw new Character('Character not found.', 404);
        }

        return $character;
    }

    public function getAllCharacters(): array
    {
        $query = 'SELECT * FROM `characters` ORDER BY `id`';
        $statement = $this->getDb()->prepare($query);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getAll(int $userId): array
    {
        $query = 'SELECT * FROM `characters` WHERE `userId` = :userId ORDER BY `id`';
        $statement = $this->getDb()->prepare($query);
        $statement->bindParam('userId', $userId);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getComics(int $characterId): array
    {
        $query = 'SELECT comics.id, comics.name, comics.description FROM comics RIGHT JOIN char_comics ON char_comics.id_comic = comics.id AND char_comics.id_character = :characterId WHERE comics.id IS NOT NULL ORDER BY id';
        $statement = $this->getDb()->prepare($query);
        $statement->bindParam('characterId', $characterId);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getEvents(int $characterId): array
    {
        $query = 'SELECT events.id, events.name, events.description FROM events RIGHT JOIN char_events ON char_events.id_event = events.id AND char_events.id_character = :characterId WHERE events.id IS NOT NULL ORDER BY id';
        $statement = $this->getDb()->prepare($query);
        $statement->bindParam('characterId', $characterId);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getSeries(int $characterId): array
    {
        $query = 'SELECT series.id, series.name, series.description FROM series RIGHT JOIN char_series ON char_series.id_serie = series.id AND char_series.id_character = :characterId WHERE series.id IS NOT NULL ORDER BY id';
        $statement = $this->getDb()->prepare($query);
        $statement->bindParam('characterId', $characterId);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function getStories(int $characterId): array
    {
        $query = 'SELECT stories.id, stories.name, stories.description FROM stories RIGHT JOIN char_stories ON char_stories.id_story = stories.id AND char_stories.id_character = :characterId WHERE stories.id IS NOT NULL ORDER BY id';
        $statement = $this->getDb()->prepare($query);
        $statement->bindParam('characterId', $characterId);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function search(string $charactersName, int $userId, ?int $status): array
    {
        $query = $this->getSearchCharactersQuery($status);
        $name = '%' . $charactersName . '%';
        $statement = $this->getDb()->prepare($query);
        $statement->bindParam('name', $name);
        $statement->bindParam('userId', $userId);
        if ($status === 0 || $status === 1) {
            $statement->bindParam('status', $status);
        }
        $statement->execute();

        return $statement->fetchAll();
    }

    public function create(object $character): object
    {
        $query = '
            INSERT INTO `characters`
                (`name`, `description`, `modified`, `resourceURI`, `urls`, `thumbnail`, `userId`)
            VALUES
                (:name, :description, :modified, :resourceURI, :urls, :thumbnail, :userId)
        ';
        $statement = $this->getDb()->prepare($query);
        $statement->bindParam('name', $character->name);
        $statement->bindParam('description', $character->description);
        $statement->bindParam('modified', $character->modified);
        $statement->bindParam('resourceURI', $character->resourceURI);
        $statement->bindParam('urls', $character->urls);
        $statement->bindParam('thumbnail', $character->thumbnail);
        $statement->bindParam('userId', $character->userId);
        $statement->execute();

        $characterId = (int) $this->database->lastInsertId();

        return $this->checkAndGetCharacter($characterId, (int) $character->userId);
    }

    public function update(object $character): object
    {
        $query = '
            UPDATE `characters`
            SET `name` = :name, `description` = :description, `modified` = :modified, `resourceURI` = :resourceURI, `urls` = :urls, `thumbnail` = :thumbnail
            WHERE `id` = :id AND `userId` = :userId
        ';
        $statement = $this->getDb()->prepare($query);
        $statement->bindParam('id', $character->id);
        $statement->bindParam('name', $character->name);
        $statement->bindParam('description', $character->description);
        $statement->bindParam('modified', $character->modified);
        $statement->bindParam('resourceURI', $character->resourceURI);
        $statement->bindParam('urls', $character->urls);
        $statement->bindParam('thumbnail', $character->thumbnail);
        $statement->bindParam('userId', $character->userId);
        $statement->execute();

        return $this->checkAndGetCharacter((int) $character->id, (int) $character->userId);
    }

    public function delete(int $characterId, int $userId): void
    {
        $query = 'DELETE FROM `characters` WHERE `id` = :id AND `userId` = :userId';
        $statement = $this->getDb()->prepare($query);
        $statement->bindParam('id', $characterId);
        $statement->bindParam('userId', $userId);
        $statement->execute();
    }

    private function getSearchCharactersQuery(?int $status): string
    {
        $statusQuery = '';
        if ($status === 0 || $status === 1) {
            $statusQuery = 'AND `status` = :status';
        }

        return "
            SELECT * FROM `characters`
            WHERE `name` LIKE :name AND `userId` = :userId ${statusQuery}
            ORDER BY `id`
        ";
    }
}
