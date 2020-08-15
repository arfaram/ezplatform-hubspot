<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\HubSpot\Repository\Storage\Mapper;

use EzPlatform\HubSpot\Repository\Value\BroadcastStorageData;
use EzPlatform\HubSpotBundle\Entity\HubSpot\HubspotBroadcastInfo;

/**
 * Class HubspotBroadcastInfoRepositoryMapper
 * @package EzPlatform\HubSpot\HubSpot\Repository\Storage\Mapper
 */
class HubspotBroadcastInfoRepositoryMapper
{
    /** @var \EzPlatform\HubSpotBundle\Entity\HubSpot\HubspotBroadcastInfo */
    private $hubspotBroadcastInfo;

    /**
     * HubspotBroadcastInfoRepositoryMapper constructor.
     * @param \EzPlatform\HubSpotBundle\Entity\HubSpot\HubspotBroadcastInfo $hubspotBroadcastInfo
     */
    public function __construct(
        HubspotBroadcastInfo $hubspotBroadcastInfo
    ) {
        $this->hubspotBroadcastInfo = $hubspotBroadcastInfo;
    }

    /**
     * @param \EzPlatform\HubSpot\Repository\Value\BroadcastStorageData $broadcastStorageData
     * @return \EzPlatform\HubSpotBundle\Entity\HubSpot\HubspotBroadcastInfo
     */
    public function addRecordMapper(BroadcastStorageData $broadcastStorageData): HubspotBroadcastInfo
    {
        $this->hubspotBroadcastInfo->setLocationId($broadcastStorageData->locationId);
        $this->hubspotBroadcastInfo->setContentId($broadcastStorageData->contentId);
        $this->hubspotBroadcastInfo->setversion($broadcastStorageData->version);
        $this->hubspotBroadcastInfo->setUChannelName($broadcastStorageData->uChannelName);
        $this->hubspotBroadcastInfo->setUChannelType($broadcastStorageData->uChannelType);
        $this->hubspotBroadcastInfo->setUChannelId($broadcastStorageData->uChannelId);
        $this->hubspotBroadcastInfo->setUScheduleTime($broadcastStorageData->uScheduleTime);
        $this->hubspotBroadcastInfo->setUOption($broadcastStorageData->uOption);
        $this->hubspotBroadcastInfo->setTimestamp($broadcastStorageData->timestamp);
        $this->hubspotBroadcastInfo->setbroadcastGuid($broadcastStorageData->broadcastGuid);
        $this->hubspotBroadcastInfo->setStatus($broadcastStorageData->status);
        $this->hubspotBroadcastInfo->setIsPublished($broadcastStorageData->isPublished);
        $this->hubspotBroadcastInfo->setTriggerAt($broadcastStorageData->triggerAt);
        $this->hubspotBroadcastInfo->setIsFailed($broadcastStorageData->isFailed);
        $this->hubspotBroadcastInfo->setData(json_encode($broadcastStorageData));

        return $this->hubspotBroadcastInfo;
    }
}
