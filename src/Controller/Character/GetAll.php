<?php

declare(strict_types=1);

namespace App\Controller\Character;

use Slim\Http\Request;
use Slim\Http\Response;

final class GetAll extends Base
{
    public function __invoke(Request $request, Response $response): Response
    {
        $characters = $this->getCharacterService()->getAllCharacters();

        return $this->jsonResponse($response, 'success', $characters, 200);
    }
}
