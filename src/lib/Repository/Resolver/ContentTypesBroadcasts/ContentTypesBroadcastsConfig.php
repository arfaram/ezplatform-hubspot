<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\Repository\Resolver\ContentTypesBroadcasts;

use eZ\Publish\Core\MVC\ConfigResolverInterface;
use EzPlatform\HubSpot\Repository\Resolver\ContentTypesBroadcasts;
use Generator;

/**
 * Class ContentTypesBroadcastsConfig
 * @package EzPlatform\HubSpot\Repository\Resolver\ContentTypesBroadcasts
 */
abstract class ContentTypesBroadcastsConfig implements ContentTypesBroadcasts\Iterator
{
    /**
     *
     */
    protected const ARG = 'HubspotConfig';

    /**
     *
     */
    private const CONTENT_TYPES_PARAMETER_NAME = 'hubspot_config.content_types_map';

    /** @var \eZ\Publish\Core\MVC\ConfigResolverInterface */
    private $configResolver;

    /**
     * ContentTypesBroadcastsConfig constructor.
     * @param \eZ\Publish\Core\MVC\ConfigResolverInterface $configResolver
     */
    public function __construct(ConfigResolverInterface $configResolver)
    {
        $this->configResolver = $configResolver;
    }

    /**
     * @return \Generator
     */
    public function iterate(): Generator
    {
        foreach ($this->configResolver->getParameter(self::CONTENT_TYPES_PARAMETER_NAME) as $identifier => $broadcasts) {
            yield [self::ARG => ['identifier' => $identifier, 'broadcasts' => $broadcasts]];
        }
    }

    /**
     * @param $broadcasts
     * @param $contentTypeIdentifier
     * @return array|false
     */
    public function getEnabledBroadcasts($broadcasts, $contentTypeIdentifier)
    {
        foreach ($broadcasts as $key => $broadcast) {
            // contentTypeIdentifier not defined
            if ($broadcast[self::ARG]['identifier'] !== $contentTypeIdentifier) {
                continue;
            }

            // missing broadcast configuration
            if (empty($broadcast[self::ARG]['broadcasts'])) {
                return false;
            }

            // Show or hide the hubspot button on content view level, if one of the broadcasts is enabled then show button
            $enabledBroadcasts= array_filter(
                $broadcast[self::ARG]['broadcasts'],
                function ($broadcast) {
                    return $broadcast['enabled'] ?? false;
                }
            );

            return $enabledBroadcasts;
        }
    }
}
