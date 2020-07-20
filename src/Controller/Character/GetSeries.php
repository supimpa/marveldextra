<?php

declare(strict_types=1);

namespace App\Controller\Character;

use Slim\Http\Request;
use Slim\Http\Response;

final class GetSeries extends Base
{
    public function __invoke(Request $request, Response $response, array $args): Response
    {
        $input = $request->getParsedBody();
        $characterId = (int) $args['id'];
        $series = $this->getCharacterService()->getSeries($characterId);

        return $this->jsonResponse($response, 'success', $series, 200);
    }
}
