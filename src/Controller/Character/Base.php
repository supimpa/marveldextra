<?php

declare(strict_types=1);

namespace App\Controller\Character;

use App\Controller\BaseController;
use App\Service\Character\CharacterService;

abstract class Base extends BaseController
{
    protected function getCharacterService(): CharacterService
    {
        return $this->container->get('character_service');
    }
}
