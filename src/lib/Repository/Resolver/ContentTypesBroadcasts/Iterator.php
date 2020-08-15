<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\Repository\Resolver\ContentTypesBroadcasts;

use Generator;

/**
 * Interface Iterator
 * @package EzPlatform\HubSpot\Repository\Resolver\ContentTypesBroadcasts
 */
interface Iterator
{
    /**
     * @return \Generator
     */
    public function iterate(): Generator;
}
