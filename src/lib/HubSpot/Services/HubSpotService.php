<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\HubSpot\Services;

use EzPlatform\HubSpot\HubSpot\Factory\Core\HubSpot;
use EzPlatform\HubSpot\Repository\Helper\SocialMediaHelper;
use EzPlatform\HubSpot\Repository\Resolver\ContentTypesBroadcastsResolver;
use EzPlatform\HubSpot\Repository\Resolver\HubspotChannelsAndConfigResolver;
use EzPlatform\HubSpot\Repository\Resolver\PublishedContentResolver;
use Psr\Log\LoggerInterface;

/**
 * Class HubSpotService
 * @package EzPlatform\HubSpot\HubSpot\Services
 */
abstract class HubSpotService
{
    /** @var \EzPlatform\HubSpot\Hubspot\Factory\Core\HubSpot */
    public $hubSpotInterface;

    /** @var \Psr\Log\LoggerInterface */
    protected $logger;

    /** @var \EzPlatform\HubSpot\Repository\Helper\SocialMediaHelper */
    protected $socialMediaHelper;

    /** @var \EzPlatform\HubSpot\Repository\Resolver\ContentTypesBroadcastsResolver */
    protected $contentTypesBroadcastsResolver;

    /** @var \EzPlatform\HubSpot\Repository\Resolver\HubspotChannelsAndConfigResolver */
    protected $hubspotChannelsAndConfigResolver;

    /** @var \EzPlatform\HubSpot\Repository\Resolver\PublishedContentResolver */
    protected $publishedContentResolver;

    /**
     * HubSpotService constructor.
     * @param \EzPlatform\HubSpot\HubSpot\Factory\Core\HubSpot $hubSpotInterface
     * @param \Psr\Log\LoggerInterface $logger
     * @param \EzPlatform\HubSpot\Repository\Helper\SocialMediaHelper $socialMediaHelper
     * @param \EzPlatform\HubSpot\Repository\Resolver\ContentTypesBroadcastsResolver $contentTypesBroadcastsResolver
     * @param \EzPlatform\HubSpot\Repository\Resolver\HubspotChannelsAndConfigResolver $hubspotChannelsAndConfigResolver
     * @param \EzPlatform\HubSpot\Repository\Resolver\PublishedContentResolver $publishedContentResolver
     */
    public function __construct(
        HubSpot $hubSpotInterface,
        LoggerInterface $logger,
        SocialMediaHelper $socialMediaHelper,
        ContentTypesBroadcastsResolver $contentTypesBroadcastsResolver,
        HubspotChannelsAndConfigResolver $hubspotChannelsAndConfigResolver,
        PublishedContentResolver $publishedContentResolver
    ) {
        $this->hubSpotInterface = $hubSpotInterface;
        $this->logger = $logger;
        $this->socialMediaHelper = $socialMediaHelper;
        $this->contentTypesBroadcastsResolver = $contentTypesBroadcastsResolver;
        $this->hubspotChannelsAndConfigResolver = $hubspotChannelsAndConfigResolver;
        $this->publishedContentResolver = $publishedContentResolver;
    }

    /**
     * @return \SevenShores\Hubspot\Factory
     * @throws \Exception
     */
    public function getHubSpotApiAccess()
    {
        if (!$this->hubSpotInterface->getHubSpotServiceAccess()) {
            throw new \LogicException('You have to add an API key first in your application.');
        }

        return $this->hubSpotInterface->getHubSpotServiceAccess();
    }
}
