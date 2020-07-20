<?php

declare(strict_types=1);

namespace App\Controller\Character;

use Slim\Http\Request;
use Slim\Http\Response;

final class GetOne extends Base
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $input = $request->getParsedBody();
        $characterId = (int) $args['id'];
        $userId = (int) $input['decoded']->sub;
        $character = $this->getCharacterService()->getOne($characterId, $userId);

        return $this->jsonResponse($response, 'success', $character, 200);
    }
}
