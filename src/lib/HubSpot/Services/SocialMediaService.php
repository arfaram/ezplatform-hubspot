<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace EzPlatform\HubSpot\HubSpot\Services;

use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\API\Repository\Values\Content\Location;
use EzPlatform\HubSpot\HubSpot\Services\API\SocialMediaService as SocialMediaServiceInterface;
use EzPlatform\HubSpot\Repository\Value\BroadcastStorageData;
use SevenShores\Hubspot\Http\Response;

/**
 * Class SocialMediaService
 * @package EzPlatform\HubSpot\HubSpot\Services
 */
class SocialMediaService extends HubSpotService implements SocialMediaServiceInterface
{
    /**
     * @return \SevenShores\Hubspot\Http\Response|null
     */
    public function getPublishingChannelsFromStorage()
    {
        try {
            return $this->getHubSpotApiAccess()->socialMedia()->channels();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return null;
    }

    /**
     * @param string $apiKey
     * @return \SevenShores\Hubspot\Http\Response|null
     */
    public function getPublishingChannelsFromHubspotApi(string $apiKey)
    {
        try {
            $hubspotAccess = $this->hubSpotInterface->createAPIAccessFromHubspotService($apiKey);
            return $hubspotAccess->socialMedia()->channels();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return null;
    }

    /**
     * @todo add to calendar (eZPlatform EE)
     *
     * Example request that creates a draft message:.
     * {
     *  "channelGuid": "xxxxx-xxxx-xxxxx-xxxxx-xxxxxxx",
     *  "status": "DRAFT",
     *  "content" : {
     *  "body": "I will be saved as a draft."
     *  }
     *
     * @param array $messages
     * @param \eZ\Publish\API\Repository\Values\Content\Location $location
     */
    public function createBroadcastMessage(array $messages, Location $location):array
    {
        $storageMapperValues = [];
        foreach ($messages as $message) {
            try {
                //fields for DB only
                $dbMessage = $message;
                unset($message['uChannelName']);
                unset($message['uChannelType']);
                unset($message['uChannelId']);
                unset($message['uScheduleTime']);
                unset($message['uOption']);

                $responseData = $this->socialMediaHelper->create($this->getHubSpotApiAccess(), $message);
                if ($responseData instanceof Response && $responseData->data) {
                    $storageMapperValue = $this->createBroadcastStorageMapperValue($dbMessage, $responseData->data, $location);
                    $storageMapperValues[] = $storageMapperValue;
                    $this->socialMediaHelper->save($storageMapperValue);
                    //@todo add to calendar (eZPlatform EE)
                }
            } catch (\Exception $e) {
                $this->logger->error($e->getMessage());
            }
        }

        return $storageMapperValues;
    }

    /**
     * @param null $guid
     * @return \SevenShores\Hubspot\Http\Response|null
     */
    public function getBroadcastMessage($guid = null)
    {
        try {
            return $this->getHubSpotApiAccess()->socialMedia()->getBroadcastById($guid);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return null;
    }

    /**
     * @param null $parameters
     * @return \SevenShores\Hubspot\Http\Response|null
     */
    public function getBroadcastMessages($parameters = null)
    {
        try {
            return $this->getHubSpotApiAccess()->socialMedia()->broadcasts($parameters);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }

        return null;
    }

    /**
     * @param int $contentId
     * @return int|mixed|string
     */
    public function getSharedContentInfo(int $contentId)
    {
        return $this->socialMediaHelper->getContentStorageInfo($contentId);
    }

    /**
     * yaml configuration mapper
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     * @return iterable|null
     */
    public function contentTypeConfigResolver(Content $content): ?iterable
    {
        return $this->contentTypesBroadcastsResolver->resolver($content);
    }

    /**
     * DB saved channels and configuration mapper
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     * @return iterable|null
     */
    public function channelsConfigResolver(Content $content): ?iterable
    {
        return $this->hubspotChannelsAndConfigResolver->resolver($content);
    }

    /**
     * @param iterable|null $apiChannelsData
     * @param array $sharedContentInfo
     * @return iterable|null
     */
    public function filterSharedContent(?iterable $apiChannelsData, array $sharedContentInfo)
    {
        if (!$apiChannelsData || !$sharedContentInfo) {
            return $apiChannelsData;
        }
        return $this->publishedContentResolver->filterSharedContent($apiChannelsData, $sharedContentInfo);
    }

    /**
     * @param array $dbMessage
     * @param \stdClass $data
     * @param \eZ\Publish\API\Repository\Values\Content\Location $location
     * @return \EzPlatform\HubSpot\Repository\Value\BroadcastStorageData
     */
    private function createBroadcastStorageMapperValue(array $dbMessage, \stdClass $data, Location $location): BroadcastStorageData
    {
        //null is stored if property for any reason will be not be available
        return new BroadcastStorageData(
            [
                'locationId' => $location->id,
                'contentId' => $location->contentId,
                'version' => $location->getContentInfo()->currentVersionNo,
                'uChannelName' => $dbMessage['uChannelName'],
                'uChannelType' => $dbMessage['uChannelType'],
                'uChannelId' => $dbMessage['uChannelId'],
                'uScheduleTime' => $dbMessage['uScheduleTime'],
                'uOption' => $dbMessage['uOption'],
                'timestamp' => time(),
                'broadcastGuid' => $data->broadcastGuid ?? null,
                'createdAt' => $data->createdAt ? (int) substr($data->createdAt, 0, -3) : null,
                'userUpdatedAt' => $data->userUpdatedAt ? (int) substr($data->userUpdatedAt, 0, -3) : null,
                'triggerAt' => $data->triggerAt ? (int) substr($data->triggerAt, 0, -3) : null,
                'status' => $data->status ?? null,
                'content' => $data->content ?? null,
                'channelKey' => $data->channelKey ?? null,
                'portalId' => $data->portalId ?? null,
                'isPending' => $data->isPending ?? null,
                'isPublished' => $data->isPublished ?? null,
                'isRetry' => $data->isRetry ?? null,
                'isFailed' => $data->isFailed ?? null,
                'wasDraft' => $data->wasDraft ?? null,
                'createdBy' => $data->createdBy ?? null,
                'taskQueueId' => $data->taskQueueId ?? null,
            ]
        );
    }
}
