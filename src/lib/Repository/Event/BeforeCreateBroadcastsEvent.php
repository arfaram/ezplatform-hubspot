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
 * You can use this Event to modify messages before sharing in the social channels
 *
 * Class BeforeSharingDataMessagesEvent
 * @package EzPlatform\HubSpot\Repository\Event
 */
class BeforeCreateBroadcastsEvent extends Event
{
    const BEFORE_CREATE_BROADCASTS_MESSAGES = 'before.create.broadcasts.messages';

    /** @var array */
    private $messages;

    /** @var \eZ\Publish\API\Repository\Values\Content\Location */
    private $location;

    /**
     * BeforeCreateBroadcastsEvent constructor.
     * @param array $messages
     * @param \eZ\Publish\API\Repository\Values\Content\Location $location
     */
    public function __construct(
        array $messages,
        Location $location
    ) {
        $this->messages = $messages;
        $this->location = $location;
    }

    /**
     * @return array
     */
    public function getMessages(): array
    {
        return $this->messages;
    }

    /**
     * @return \eZ\Publish\API\Repository\Values\Content\Location
     */
    public function getLocation(): Location
    {
        return $this->location;
    }
}
