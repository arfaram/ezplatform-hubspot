<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\Repository\Event;

use eZ\Publish\API\Repository\Values\Content\Location;
use EzPlatform\HubSpot\HubSpot\Repository\Form\Data\DateBasedHubspotOptionsData;
use Symfony\Contracts\EventDispatcher\Event;

class ContentMappingEvent extends Event
{
    const MESSAGE_DATA_MAP = 'hubspot.message.data.map';

    /** @var Location $location */
    public $location;

    /** @var string $baseUrl */
    private $baseUrl;

    /** @var \EzPlatform\HubSpot\HubSpot\Repository\Form\Data\DateBasedHubspotOptionsData */
    public $formSubmittedData;

    /**
     * ContentMappingEvent constructor.
     * @param \eZ\Publish\API\Repository\Values\Content\Location $location
     * @param string $baseUrl
     * @param \EzPlatform\HubSpot\HubSpot\Repository\Form\Data\DateBasedHubspotOptionsData $formSubmittedData
     */
    public function __construct(
        Location $location,
        string $baseUrl,
        DateBasedHubspotOptionsData $formSubmittedData
    ) {
        $this->location = $location;
        $this->baseUrl = $baseUrl;
        $this->formSubmittedData = $formSubmittedData;
    }

    /**
     * @return \eZ\Publish\API\Repository\Values\Content\Location
     */
    public function getLocation(): Location
    {
        return $this->location;
    }

    /**
     * @return string
     */
    public function getBaseUrl() :string
    {
        return $this->baseUrl;
    }
}
