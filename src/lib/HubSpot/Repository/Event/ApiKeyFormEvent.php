<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\HubSpot\Repository\Event;

use EzPlatform\HubSpotBundle\Entity\HubSpot\ApiKey;
use Symfony\Contracts\EventDispatcher\Event;

/**
 * Class ApiKeyFormEvent
 * @package EzPlatform\HubSpot\HubSpot\Repository\Event
 */
class ApiKeyFormEvent extends Event
{
    const UPDATE_APIKEY = 'settings.update_apiKey';

    /** @var \EzPlatform\HubSpotBundle\Entity\HubSpot\ApiKey $apiKey */
    private $apiKey;

    /** @var */
    public $userId;

    /**
     * ApiKeyFormEvent constructor.
     * @param \EzPlatform\HubSpotBundle\Entity\HubSpot\ApiKey $apiKey
     */
    public function __construct(ApiKey $apiKey, $userId)
    {
        $this->apiKey = $apiKey;
        $this->userId = $userId;
    }

    /**
     * @return \EzPlatform\HubSpotBundle\Entity\HubSpot\ApiKey
     */
    public function getFormData(): ApiKey
    {
        return $this->apiKey;
    }
}
