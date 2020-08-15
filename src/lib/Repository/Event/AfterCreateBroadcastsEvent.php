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
use Symfony\Contracts\EventDispatcher\Event;

/**
 * You can use this Event to access stored data in database after sharing
 *
 * Class AfterCreateBroadcastsEvent
 * @package EzPlatform\HubSpot\Repository\Event
 */
class AfterCreateBroadcastsEvent extends Event
{
    /**
     *
     */
    const AFTER_CREATE_BROADCASTS_STORAGE_DATA = 'after.create.broadcasts.storage.data';

    /** @var array */
    private $storageMapperValue;

    /** @var \eZ\Publish\API\Repository\Values\Content\Location */
    private $location;

    /**
     * AfterCreateBroadcastsEvent constructor.
     * @param array $storageMapperValue
     * @param \eZ\Publish\API\Repository\Values\Content\Location $location
     */
    public function __construct(
        array $storageMapperValue,
        Location $location
    ) {
        $this->storageMapperValue = $storageMapperValue;
        $this->location = $location;
    }

    /**
     * @return array
     */
    public function getStorageMapperValue(): array
    {
        return $this->storageMapperValue;
    }

    /**
     * @return \eZ\Publish\API\Repository\Values\Content\Location
     */
    public function getLocation(): Location
    {
        return $this->location;
    }
}
