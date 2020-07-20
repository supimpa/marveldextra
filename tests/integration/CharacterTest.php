<?php

declare(strict_types=1);

namespace Tests\integration;

class CharacterTest extends BaseTestCase
{
    /**
     * @var int
     */
    private static $id;

    /**
     * Test Get All Characters.
     */
    public function testGetCharacters(): void
    {
        $response = $this->runApp('GET', '/api/v1/public/characters');

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('name', $result);        
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Get One Character.
     */
    public function testGetCharacter(): void
    {
        $response = $this->runApp('GET', '/api/v1/public/characters/1');

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('name', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Get Character Not Found.
     */
    public function testGetCharacterNotFound(): void
    {
        $response = $this->runApp('GET', '/api/v1/public/characters/123456789');

        $result = (string) $response->getBody();

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringNotContainsString('id', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Create Character.
     */
    public function testCreateCharacter(): void
    {
        $response = $this->runApp(
            'POST', '/api/v1/public/characters', ['name' => 'HERO', 'description' => 'Good hero']
        );

        $result = (string) $response->getBody();

        self::$id = json_decode($result)->message->id;

        $this->assertEquals(201, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('name', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Get Character Created.
     */
    public function testGetCharacterCreated(): void
    {
        $response = $this->runApp('GET', '/api/v1/public/characters/' . self::$id);

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('name', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Create Character Without Name.
     */
    public function testCreateCharacterWithOutCharacterName(): void
    {
        $response = $this->runApp('POST', '/api/v1/public/characters');

        $result = (string) $response->getBody();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringNotContainsString('id', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Create Character With Invalid Character Name.
     */
    public function testCreateCharacterWithInvalidCharacterame(): void
    {
        $response = $this->runApp(
            'POST', '/api/v1/public/characters', ['name' => '']
        );

        $result = (string) $response->getBody();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Create Character Without Authorization Bearer JWT.
     */
    public function testCreateCharacterWithoutBearerJWT(): void
    {
        $auth = self::$jwt;
        self::$jwt = '';
        $response = $this->runApp(
            'POST', '/api/v1/publics/characters', ['name' => 'my hero']
        );
        self::$jwt = $auth;

        $result = (string) $response->getBody();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Create Character With Invalid JWT.
     */
    public function testCreateCharacterWithInvalidJWT(): void
    {
        $auth = self::$jwt;
        self::$jwt = 'invalidToken';
        $response = $this->runApp(
            'POST', '/api/v1/public/characters', ['name' => 'my hero']
        );
        self::$jwt = $auth;

        $result = (string) $response->getBody();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Create Character With Forbidden JWT.
     */
    public function testCreateCharacterWithForbiddenJWT(): void
    {
        $auth = self::$jwt;
        self::$jwt = 'Bearer eyJ0eXAiOiJK1NiJ9.eyJzdWIiOiI4Ii';
        $response = $this->runApp(
            'POST', '/api/v1/public/characters', ['name' => 'my hero']
        );
        self::$jwt = $auth;

        $result = (string) $response->getBody();

        $this->assertEquals(403, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Update Character.
     */
    public function testUpdateCharacter(): void
    {
        $response = $this->runApp(
            'PUT', '/api/v1/public/characters/' . self::$id,
            ['name' => 'Update Hero', 'description' => 'Update Desc']
        );

        $result = (string) $response->getBody();

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringContainsString('id', $result);
        $this->assertStringContainsString('name', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Update Character Without Send Data.
     */
    public function testUpdateCharacterWithOutSendData(): void
    {
        $response = $this->runApp('PUT', '/api/v1/public/characters/' . self::$id);

        $result = (string) $response->getBody();

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringNotContainsString('id', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Update Character Not Found.
     */
    public function testUpdateCharacterNotFound(): void
    {
        $response = $this->runApp(
            'PUT', '/api/v1/public/characters/123456789', ['name' => 'Hero']
        );

        $result = (string) $response->getBody();

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringNotContainsString('id', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Update Character of Another User.
     */
    public function testUpdateCharacterOfAnotherUser(): void
    {
        $response = $this->runApp(
            'PUT', '/api/v1/public/characters/6', ['name' => 'Hero']
        );

        $result = (string) $response->getBody();

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringNotContainsString('id', $result);
        $this->assertStringContainsString('error', $result);
    }

    /**
     * Test Delete Character.
     */
    public function testDeleteCharacter(): void
    {
        $response = $this->runApp('DELETE', '/api/v1/public/characters/' . self::$id);

        $result = (string) $response->getBody();

        $this->assertEquals(204, $response->getStatusCode());
        $this->assertEquals('application/json', $response->getHeaderLine('Content-Type'));
        $this->assertStringContainsString('success', $result);
        $this->assertStringNotContainsString('error', $result);
    }

    /**
     * Test Delete Character Not Found.
     */
    public function testDeleteCharacterNotFound(): void
    {
        $response = $this->runApp('DELETE', '/api/v1/public/characters/123456789');

        $result = (string) $response->getBody();

        $this->assertEquals(404, $response->getStatusCode());
        $this->assertEquals('application/problem+json', $response->getHeaderLine('Content-Type'));
        $this->assertStringNotContainsString('success', $result);
        $this->assertStringNotContainsString('id', $result);
        $this->assertStringContainsString('error', $result);
    }
}
