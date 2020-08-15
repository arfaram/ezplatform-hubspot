<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\Repository\Helper;

use eZ\Publish\API\Repository\ContentService;
use eZ\Publish\API\Repository\LocationService;
use eZ\Publish\API\Repository\Values\Content\Location;
use EzPlatform\HubSpot\Repository\Resolver\ContentTypesBroadcastsResolver;

/**
 * Class ContentMapping
 * @package EzPlatform\HubSpot\Repository\Helper
 */
class ContentMapping
{
    /** @var \eZ\Publish\API\Repository\LocationService */
    private $locationService;

    /** @var \EzPlatform\HubSpot\Repository\Resolver\ContentTypesBroadcastsResolver  */
    private $contentTypesBroadcastsResolver;

    /** @var \eZ\Publish\API\Repository\ContentService */
    private $contentService;

    /**
     * ContentMapping constructor.
     * @param \eZ\Publish\API\Repository\LocationService $locationService
     * @param \eZ\Publish\API\Repository\ContentService $contentService
     * @param \EzPlatform\HubSpot\Repository\Resolver\ContentTypesBroadcastsResolver $contentTypesBroadcastsResolver
     */
    public function __construct(
        LocationService $locationService,
        ContentService $contentService,
        ContentTypesBroadcastsResolver $contentTypesBroadcastsResolver
    ) {
        $this->locationService = $locationService;
        $this->contentTypesBroadcastsResolver = $contentTypesBroadcastsResolver;
        $this->contentService = $contentService;
    }

    /**
     * @return \eZ\Publish\API\Repository\Values\Content\Location
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    public function loadLocation($locationId): Location
    {
        return $this->locationService->loadLocation((int) $locationId);
    }

    /**
     * @param $content
     * @param $baseUrl
     * @return iterable|null
     */
    public function broadcastMappingConfiguration($content, $baseUrl): ?iterable
    {
        $broadcasts =  $this->contentTypesBroadcastsResolver->resolver($content);
        if (!$broadcasts) {
            return false;
        }

        return $this->mapFields($broadcasts, $content, $baseUrl);
    }

    /**
     * @todo move it to its own iterface
     * @param $broadcasts
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     * @param $baseUrl
     * @return mixed
     */
    private function mapFields($broadcasts, $content, $baseUrl): iterable
    {
        foreach ($broadcasts as $key => $value) {
            /** @var \eZ\Publish\Core\FieldType\TextLine\Value $content */
            $value['body'] = $content->getFieldValue($value['body'])->text ?? null; // not ezstring
            if (empty($value['body'])) { //ezstring & empty
                $value['body'] = null;
            }
            $value['photoUrl'] = $baseUrl . $this->getImageField($content, $value['photoUrl']);
            unset($value['enabled']);

            $broadcasts[$key] = $value;
        }

        return $broadcasts;
    }

    /**
     * @todo support image variation on next step
     * @param \eZ\Publish\API\Repository\Values\Content\Content$content
     * @param $fieldIdentifier
     * @return string|null
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    private function getImageField($content, $fieldIdentifier): ?string
    {
        $fieldTypeIdentifier = $content->getContentType()->getFieldDefinition($fieldIdentifier)->fieldTypeIdentifier;

        if ($fieldTypeIdentifier === 'ezimageasset') {

            /** @var \eZ\Publish\Core\FieldType\ImageAsset\Value $imageField */
            $imageField = $content->getFieldValue($fieldIdentifier);
            $imageContentId = $imageField->destinationContentId;
            if (!$imageContentId) {
                return null;
            }
            /** @var \eZ\Publish\Core\FieldType\Image\Value $imageField */
            $imageField = $this->contentService->loadContent($imageContentId)->getFieldValue($fieldIdentifier);
            return $imageField->uri;
        }

        if ($fieldTypeIdentifier === 'ezimage') {
            /** @var \eZ\Publish\Core\FieldType\Image\Value $imageField */
            $imageField = $content->getFieldValue($fieldIdentifier);
            return $imageField->uri ?? null;
        }
    }
}
