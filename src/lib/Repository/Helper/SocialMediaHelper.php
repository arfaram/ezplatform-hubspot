<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\Repository\Helper;

use EzPlatform\HubSpot\HubSpot\Repository\Storage\Handler\HubspotBroadcastInfoRepositoryHandler;
use EzPlatform\HubSpot\Repository\Value\BroadcastStorageData;
use SevenShores\Hubspot\Factory;
use SevenShores\Hubspot\Http\Response;

/**
 * @todo move to its own interface
 * Class SocialMediaCreateBroadcast
 * @package EzPlatform\HubSpot\Repository\Helper
 */
class SocialMediaHelper
{
    /** @var \EzPlatform\HubSpot\HubSpot\Repository\Storage\Handler\HubspotBroadcastInfoRepositoryHandler */
    private $broadcastInfoRepositoryHandler;

    /**
     * SocialMediaHelper constructor.
     * @param \EzPlatform\HubSpot\HubSpot\Repository\Storage\Handler\HubspotBroadcastInfoRepositoryHandler $broadcastInfoRepositoryHandler
     */
    public function __construct(
        HubspotBroadcastInfoRepositoryHandler $broadcastInfoRepositoryHandler
    ) {
        $this->broadcastInfoRepositoryHandler = $broadcastInfoRepositoryHandler;
    }

    /**
     * @param \SevenShores\Hubspot\Factory $HubSpotApiAccess
     * @param $message
     * @return \SevenShores\Hubspot\Http\Response
     */
    public function create(Factory $HubSpotApiAccess, $message): Response
    {
        return $HubSpotApiAccess->socialMedia()->createBroadcast($message);
    }

    /**
     * @param \EzPlatform\HubSpot\Repository\Value\BroadcastStorageData $storageMapperValue
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function save(BroadcastStorageData $storageMapperValue)
    {
        $this->broadcastInfoRepositoryHandler->addRecord($storageMapperValue);
    }

    /**
     * @param int $contentId
     * @return int|mixed|string
     */
    public function getContentStorageInfo(int $contentId)
    {
        return $this->broadcastInfoRepositoryHandler->getRecords($contentId);
    }
}
