<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\Repository\Value;

use eZ\Publish\API\Repository\Values\ValueObject;

/**
 * Class BroadcastStorageData
 * @package EzPlatform\HubSpot\Repository\Value
 */
class BroadcastStorageData extends ValueObject
{
    /** @var int */
    public $locationId;

    /** @var int */
    public $contentId;

    /** @var int */
    public $version;

    /** @var string */
    public $uChannelName;

    /** @var string */
    public $uChannelType;

    /** @var string */
    public $uChannelId;

    /** @var int */
    public $uScheduleTime;

    /** @var string */
    public $uOption;

    /** @var int */
    public $timestamp;

    /** @var string */
    public $broadcastGuid;

    /** @var int */
    public $createdAt;

    /** @var int */
    public $userUpdatedAt;

    /** @var int */
    public $triggerAt;

    /** @var string */
    public $status;

    /** @var object */
    public $content;

    /** @var string */
    public $channelKey;

    /** @var int */
    public $portalId;

    /** @var bool */
    public $isPending;

    /** @var bool */
    public $isPublished;

    /** @var bool */
    public $isRetry;

    /** @var bool */
    public $isFailed;

    /** @var bool */
    public $wasDraft;

    /** @var int */
    public $createdBy;

    /** @var string */
    public $taskQueueId;
}
