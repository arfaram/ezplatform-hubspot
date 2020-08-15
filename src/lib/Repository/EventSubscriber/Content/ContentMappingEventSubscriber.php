<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\Repository\EventSubscriber\Content;

use EzPlatform\HubSpot\HubSpot\Services\API\SocialMediaService;
use EzPlatform\HubSpot\Repository\Event\ContentMappingEvent;
use EzPlatform\HubSpot\Repository\Helper\BroadcastMapping;
use EzPlatform\HubSpot\Repository\Helper\ContentMapping;

/**
 * Class ContentMappingEventSubscriber
 * @package EzPlatform\HubSpot\Repository\EventSubscriber\Content
 */
final class ContentMappingEventSubscriber extends AbstractSubscriber
{
    /** @var \EzPlatform\HubSpot\Repository\Helper\ContentMapping */
    private $contentMapping;

    /** @var \EzPlatform\HubSpot\Repository\Helper\BroadcastMapping */
    private $broadcastMapping;

    /** @var \EzPlatform\HubSpot\HubSpot\Services\API\SocialMediaService */
    private $socialMediaService;

    /**
     * ContentMappingEventSubscriber constructor.
     * @param \EzPlatform\HubSpot\Repository\Helper\ContentMapping $contentMapping
     * @param \EzPlatform\HubSpot\Repository\Helper\BroadcastMapping $broadcastMapping
     * @param \EzPlatform\HubSpot\HubSpot\Services\API\SocialMediaService $socialMediaService
     */
    public function __construct(
        ContentMapping $contentMapping,
        BroadcastMapping $broadcastMapping,
        SocialMediaService $socialMediaService
    ) {
        $this->contentMapping = $contentMapping;
        $this->broadcastMapping = $broadcastMapping;
        $this->socialMediaService = $socialMediaService;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ContentMappingEvent::MESSAGE_DATA_MAP =>
            [
                ['loadContentMapping', 100]
            ]

        ];
    }

    /**
     * @param \EzPlatform\HubSpot\Repository\Event\ContentMappingEvent $event
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    public function loadContentMapping(ContentMappingEvent $event): void
    {
        $confMapping = $this->contentMapping->broadcastMappingConfiguration($event->location->getContent(), $event->getBaseUrl());

        if (!$confMapping) {
            return;
        }

        $messages = $this->broadcastMapping->loadSocialMapping($confMapping, $event->formSubmittedData);

        if (!isset($messages)) {
            return;
        }
        $this->socialMediaService->createBroadcastMessage($messages, $event->location);
    }
}
