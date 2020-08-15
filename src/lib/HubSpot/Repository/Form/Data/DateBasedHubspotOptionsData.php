<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\HubSpot\Repository\Form\Data;

use eZ\Publish\API\Repository\Values\Content\Location;

/**
 * Class DateBasedHubspotOptionsData
 * @package EzPlatform\HubSpot\HubSpot\Repository\Form\Data
 */
class DateBasedHubspotOptionsData
{
    /** @var $schedulePublishingOption*/
    public $schedulePublishingOption;

    /** @var $timestamp*/
    public $timestamp;

    /** @var $flatpickr*/
    private $flatpickr;

    /** @var \eZ\Publish\API\Repository\Values\Content\Location|null */
    private $location;

    /** @var array|null */
    public $apiKeyRecordData;

    /** @var array|null */
    public $apiChannelsData;

    /**
     * DateBasedHubspotOptionsData constructor.
     * @param \eZ\Publish\API\Repository\Values\Content\Location|null $location
     * @param array|null $apiChannelsData
     */
    public function __construct(
        ?Location $location = null,
        ?array $apiChannelsData = null
    ) {
        $this->location = $location;
        $this->apiChannelsData = $apiChannelsData;
    }

    /**
     * @return mixed
     */
    public function getSchedulePublishingOption()
    {
        return $this->schedulePublishingOption;
    }

    /**
     * @param mixed $schedulePublishingOption
     */
    public function setSchedulePublishingOption($schedulePublishingOption): void
    {
        $this->schedulePublishingOption = $schedulePublishingOption;
    }

    /**
     * @return int|null
     */
    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }

    /**
     * @param int|null $timestamp
     */
    public function setTimestamp(?int $timestamp): void
    {
        $this->timestamp = $timestamp;
    }

    /**
     * @return mixed
     */
    public function getFlatpickr()
    {
        return $this->flatpickr;
    }

    /**
     * @param mixed $flatpickr
     */
    public function setFlatpickr($flatpickr): void
    {
        $this->flatpickr = $flatpickr;
    }

    /**
     * @return \eZ\Publish\API\Repository\Values\Content\Location|null
     */
    public function getLocation(): ?Location
    {
        return $this->location;
    }

    /**
     * @param \eZ\Publish\API\Repository\Values\Content\Location|null $location
     */
    public function setLocation(?Location $location): void
    {
        $this->location = $location;
    }

    /**
     * @return array|null
     */
    public function getApiKeyRecordData(): ?array
    {
        return $this->apiKeyRecordData;
    }

    /**
     * @param array|null $apiKeyRecordData
     */
    public function setApiKeyRecordData(?array $apiKeyRecordData): void
    {
        $this->apiKeyRecordData = $apiKeyRecordData;
    }
}
