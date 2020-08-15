<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\HubSpot\Factory\API;

/**
 * Interface HubSpotInterface
 * @package EzPlatform\HubSpot\HubSpot\Factory\API
 */
interface HubSpotInterface
{
    /**
     * @return mixed
     */
    public function getAPIAccessFromStorage();

    /**
     * @param string $apiKey
     * @return mixed
     */
    public function createAPIAccessFromHubspotService(string $apiKey);
}
