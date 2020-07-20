<?php

declare(strict_types=1);

namespace App\Controller\Character;

use Slim\Http\Request;
use Slim\Http\Response;

final class GetComics extends Base
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $input = $request->getParsedBody();
        $characterId = (int) $args['id'];
        $comics = $this->getCharacterService()->getComics($characterId);

        return $this->jsonResponse($response, 'success', $comics, 200);
    }
}