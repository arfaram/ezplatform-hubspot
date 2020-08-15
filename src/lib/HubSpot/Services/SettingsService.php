<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\HubSpot\Services;

use EzPlatform\HubSpot\HubSpot\Repository\Storage\Handler\ApiKeyStorageRepositoryHandler;
use EzPlatform\HubSpotBundle\Entity\HubSpot\ApiKey;
use Psr\Log\LoggerInterface;

/**
 * Class SettingsService
 * @package EzPlatform\HubSpot\HubSpot\Services
 */
class SettingsService
{
    /** @var \EzPlatform\HubSpot\HubSpot\Repository\Storage\Handler\ApiKeyStorageRepositoryHandler */
    private $apiKeyStorageRepositoryHandler;

    /** @var \Psr\Log\LoggerInterface */
    private $logger;

    /**
     * SettingsService constructor.
     * @param \EzPlatform\HubSpot\HubSpot\Repository\Storage\Handler\ApiKeyStorageRepositoryHandler $apiKeyStorageRepositoryHandler
     * @param \Psr\Log\LoggerInterface $logger
     */
    public function __construct(
        ApiKeyStorageRepositoryHandler $apiKeyStorageRepositoryHandler,
        LoggerInterface $logger
    ) {
        $this->apiKeyStorageRepositoryHandler = $apiKeyStorageRepositoryHandler;
        $this->logger = $logger;
    }


    /**
     * @param \EzPlatform\HubSpotBundle\Entity\HubSpot\ApiKey $apiKey
     * @throws \Doctrine\ORM\ORMException
     */
    public function addRecord(ApiKey $apiKey)
    {
        $this->apiKeyStorageRepositoryHandler->addRecord($apiKey);
    }
}
