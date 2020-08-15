<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\HubSpot\Services;

/**
 * Class ContactsService
 * @package EzPlatform\HubSpot\HubSpot\Services
 */
class ContactsService extends HubSpotService
{
    /**
     * @param $data
     * @return \SevenShores\Hubspot\Http\Response
     * @throws \Exception
     */
    public function getContactsData($data)
    {
        try {
            $this->getHubSpotApiAccess()->contacts()->all($data);
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
