<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\Repository\Helper;

use EzPlatform\HubSpot\HubSpot\Repository\Form\Data\DateBasedHubspotOptionsData;

/**
 * Class BroadcastMapping
 * @package EzPlatform\HubSpot\Repository\Helper
 */
class BroadcastMapping
{
    /**
     * @param $confMapping
     * @param $formSubmittedData
     * @return array
     *
     * Message Example:
     * 0 => array
     *      "channelGuid" => "XXX-XXXX"
     *      "triggerAt" => "XXX-XXXX"
     *      "content" => array
     *          "body" => "content name"
     *          "image" => "http://www...."
     *      ]
     * ]
     */
    public function loadSocialMapping($confMapping, DateBasedHubspotOptionsData $formSubmittedData): ?array
    {
        $getApiKeyRecordData = $formSubmittedData->getApiKeyRecordData();
        $apiChannelsData = $getApiKeyRecordData['apiChannelsData'];//from DB
        $submittedChannels = $getApiKeyRecordData['values'];//what user have selected

        $messages = null;
        $channelsToShare = [];
        //Select only what the user have selected in UI
        foreach ($submittedChannels as $channelId) {
            $key = array_search($channelId, array_column($apiChannelsData, 'channelId'));
            $channelsToShare[] = $apiChannelsData[$key];
        }

        //match with the configuration
        foreach ($channelsToShare as $key => $enabledChannel) {
            $channelType = $enabledChannel['channelType'];
            $channelName = $enabledChannel['channelName'];
            $channelId = $enabledChannel['channelId'];
            $channelGuid = $enabledChannel['channelGuid'];

            //every u* entries are needed for the DB and will be removed from the message later
            if (array_key_exists(strtolower($channelType), $confMapping)) {
                $messages[$key] = [
                    'channelGuid' => $channelGuid,
                    'content' => $confMapping[strtolower($channelType)],
                    'uChannelType' => $channelType,
                    'uChannelName' => $channelName,
                    'uChannelId' => $channelId,
                    'uScheduleTime' => $formSubmittedData->timestamp ?? null,
                    'uOption' => $formSubmittedData->schedulePublishingOption,

                ];
            }

            if ($formSubmittedData->schedulePublishingOption === 'draft') {
                $messages[$key] +=  ['status' => strtoupper($formSubmittedData->schedulePublishingOption)];
            }

            if ($formSubmittedData->timestamp) {
                $messages[$key] += ['triggerAt' => $formSubmittedData->timestamp * 1000];
            }
        }
        return $messages;
    }
}
