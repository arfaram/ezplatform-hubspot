<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\HubSpot\Services\API;

use eZ\Publish\API\Repository\Values\Content\Location;

/**
 * Interface SocialMediaService
 * @package EzPlatform\HubSpot\HubSpot\Services\API
 */
interface SocialMediaService
{
    /**
     * @param array $messages
     * @param \eZ\Publish\API\Repository\Values\Content\Location $location
     * @return array
     */
    public function createBroadcastMessage(array $messages, Location $location): array ;
}
