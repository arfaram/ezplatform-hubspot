<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\HubSpot\Social\Core;

use EzPlatform\HubSpot\HubSpot\Social\API\CreateBroadcastMessageInterface;

/**
 * Class CreateBroadcastMessage
 * @package EzPlatform\HubSpot\HubSpot\Social\Core
 */
abstract class CreateBroadcastMessage implements CreateBroadcastMessageInterface
{
    /**
     * @return mixed|void
     */
    public function createMessage()
    {
        // TODO: Implement createMessage() method.
    }
}
