<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\HubSpot\Repository\Event;

use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\API\Repository\Values\Content\Location;
use EzPlatform\HubSpot\Repository\Event\AfterCreateBroadcastsEvent;
use EzPlatform\HubSpot\Repository\Event\BeforeCreateBroadcastsEvent;
use Symfony\Contracts\EventDispatcher\EventDispatcherInterface;
use EzPlatform\HubSpot\HubSpot\Services\API\SocialMediaService as SocialMediaServiceInterface;

class SocialMediaService implements SocialMediaServiceInterface
{
    /** @var \Symfony\Contracts\EventDispatcher\EventDispatcherInterface */
    private $eventDispatcher;

    /** @var \EzPlatform\HubSpot\HubSpot\Services\API\SocialMediaService */
    private $innerService;

    public function __construct(
        SocialMediaServiceInterface $innerService,
        EventDispatcherInterface $eventDispatcher
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->innerService = $innerService;
    }
    public function getPublishingChannelsFromStorage()
    {
        return $this->innerService->getPublishingChannelsFromStorage();
    }

    public function getPublishingChannelsFromHubspotApi(string $apiKey)
    {
        return $this->innerService->getPublishingChannelsFromHubspotApi($apiKey);
    }

    /**
     *
     * @param array $messages
     * @param \eZ\Publish\API\Repository\Values\Content\Location $location
     */
    public function createBroadcastMessage(array $messages, Location $location): array
    {
        $beforeEventData = [
            $messages,
            $location,
        ];
        $beforeEvent = new BeforeCreateBroadcastsEvent(...$beforeEventData);

        $this->eventDispatcher->dispatch($beforeEvent);
        if ($beforeEvent->isPropagationStopped()) {
            return [
                $beforeEvent->getLocation(),
                $beforeEvent->getMessages()
            ];
        }

        $storageMapperValues = $this->innerService->createBroadcastMessage($messages, $location);

        $afterEventData = [
            $storageMapperValues,
            $location,
        ];

        $this->eventDispatcher->dispatch(
            new AfterCreateBroadcastsEvent(...$afterEventData)
        );

        return $storageMapperValues;
    }

    public function getBroadcastMessage($guid = null)
    {
        return $this->innerService->getBroadcastMessage($guid);
    }

    public function getBroadcastMessages($parameters = null)
    {
        return $this->innerService->getBroadcastMessages($parameters);
    }

    public function getSharedContentInfo(int $contentId)
    {
        return $this->innerService->getSharedContentInfo($contentId);
    }

    public function contentTypeConfigResolver(Content $content)
    {
        return $this->innerService->contentTypeConfigResolver($content);
    }

    /**
     * DB saved channels and configuration mapper
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     */
    public function channelsConfigResolver(Content $content)
    {
        return $this->innerService->channelsConfigResolver($content);
    }

    public function filterSharedContent(?iterable $apiChannelsData, array $sharedContentInfo)
    {
        return $this->innerService->filterSharedContent($apiChannelsData, $sharedContentInfo);
    }
}
