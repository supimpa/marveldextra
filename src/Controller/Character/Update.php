<?php

declare(strict_types=1);

namespace App\Controller\Character;

use Slim\Http\Request;
use Slim\Http\Response;

final class Update extends Base
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $input = (array) $request->getParsedBody();
        $character = $this->getCharacterService()->update($input, (int) $args['id']);

        return $this->jsonResponse($response, 'success', $character, 200);
    }
}
