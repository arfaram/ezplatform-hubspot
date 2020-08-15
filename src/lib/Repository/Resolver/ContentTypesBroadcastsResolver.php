<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\Repository\Resolver;

use eZ\Publish\API\Repository\Values\Content\Content;
use EzPlatform\HubSpot\Repository\Resolver\ContentTypesBroadcasts\ContentTypesBroadcastsConfig;

/**
 * Class ContentTypesBroadcastsResolver
 * @package EzPlatform\HubSpot\Repository\Resolver
 */
class ContentTypesBroadcastsResolver extends ContentTypesBroadcastsConfig
{
    /**
     *  Resolver for enabled channels from the configuration
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     * @return iterable|null
     */
    public function resolver(Content $content): ?iterable
    {
        $contentTypeIdentifier = $content->getContentType()->identifier;

        if (count(iterator_to_array($this->iterate(), false)) === 0) {
            return null;
        }

        return $this->getEnabledBroadcasts($this->iterate(), $contentTypeIdentifier);
    }
}
