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
 * Class IntegrationService
 * @package EzPlatform\HubSpot\HubSpot\Services
 */
class IntegrationService extends HubSpotService
{
    /**
     * {@inheritdoc}
     * @return \SevenShores\Hubspot\Http\Response
     * @throws \Exception
     */
    public function getAccountDetails()
    {
        try {
            return $this->getHubSpotApiAccess()->integration()->getAccountDetails();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }

    /**
     * @return \SevenShores\Hubspot\Http\Response
     */
    public function getDailyUsageDetails()
    {
        try {
            return $this->getHubSpotApiAccess()->integration()->getDailyUsage();
        } catch (\Exception $e) {
            $this->logger->error($e->getMessage());
        }
    }
}
