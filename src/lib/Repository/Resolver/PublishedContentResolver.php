<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\Repository\Resolver;

/**
 * Class PublishedContentResolver
 * @package EzPlatform\HubSpot\Repository\Resolver
 */
class PublishedContentResolver
{
    /**
     *  content already shared will not shared again. This method check the channels saved in the DB and remove them if they are already shared. $sharedContentInfo is comming from the "hubspot_broadcast_info" table per contentId
     *
     * @param iterable $apiChannelsData
     * @param array $sharedContentInfo
     */
    public function filterSharedContent(iterable $apiChannelsData, array $sharedContentInfo)
    {
        foreach ($sharedContentInfo as $sharedContent) {
            $uChannelId = $sharedContent->uChannelId;
            foreach ($apiChannelsData as $key => $apiChannel) {
                if ($uChannelId === $apiChannel['channelId']) {
                    unset($apiChannelsData[$key]);
                }
            }
        }
        return $apiChannelsData;
    }
}
