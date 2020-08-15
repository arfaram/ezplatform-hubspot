<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\HubSpot\Repository\Exception;

use Exception;

/**
 * Class ApiKeyNotValidException
 * @package EzPlatform\HubSpot\HubSpot\Repository\Exception
 */
class ApiKeyNotValidException extends Exception
{
    /**
     * ApiKeyNotValidException constructor.
     */
    public function __construct()
    {
        parent::__construct(sprintf('Hubspot API Key not valid'));
    }
}
