<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\HubSpot\Factory\Core;

use EzPlatform\HubSpot\HubSpot\Factory\API\HubSpotInterface;
use EzPlatform\HubSpot\HubSpot\Repository\Storage\Handler\ApiKeyStorageRepositoryHandler;
use SevenShores\Hubspot\Factory;

/**
 * Class HubSpot
 * @package EzPlatform\HubSpot\HubSpot\Factory\Core
 */
class HubSpot implements HubSpotInterface
{
    /** @var \SevenShores\Hubspot\Factory */
    public $factory;

    /** @var */
    public $hubspot;

    /** @var \EzPlatform\HubSpot\HubSpot\Repository\Storage\Handler\ApiKeyStorageRepositoryHandler */
    private $apiKeyStorageRepositoryHandler;

    /**
     * HubSpot constructor.
     * @param \EzPlatform\HubSpot\HubSpot\Repository\Storage\Handler\ApiKeyStorageRepositoryHandler $apiKeyStorageRepositoryHandler
     */
    public function __construct(
        ApiKeyStorageRepositoryHandler $apiKeyStorageRepositoryHandler
    ) {
        $this->factory = new Factory();
        $this->apiKeyStorageRepositoryHandler = $apiKeyStorageRepositoryHandler;
    }

    /**
     * @param null $apiKey
     * @return mixed|void
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getAPIAccessFromStorage($apiKey = null)
    {
        if (isset($this->apiKeyStorageRepositoryHandler->getAPIKeyRecord()->apiKey)) {
            $apiKeyRecord = $this->apiKeyStorageRepositoryHandler->getAPIKeyRecord()->apiKey;

            $this->hubspot = $this->factory::create($apiKeyRecord);
        }
    }

    /**
     * @param string $apiKey
     * @return mixed|\SevenShores\Hubspot\Factory
     */
    public function createAPIAccessFromHubspotService(string $apiKey)
    {
        if ($apiKey !== null) {
            return $this->factory::create($apiKey);
        }
    }

    /**
     * @return mixed
     */
    public function getHubSpotServiceAccess()
    {
        return $this->hubspot;
    }
}
