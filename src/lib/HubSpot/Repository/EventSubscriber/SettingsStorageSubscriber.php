<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\HubSpot\Repository\EventSubscriber;

use EzPlatform\HubSpot\HubSpot\Factory\Core\HubSpot;
use EzPlatform\HubSpot\HubSpot\Repository\Event\ApiKeyFormEvent;
use EzPlatform\HubSpot\HubSpot\Services\SettingsService;
use EzPlatform\HubSpot\HubSpot\Services\API\SocialMediaService;
use EzPlatform\HubSpotBundle\Entity\HubSpot\ApiKey;
use EzSystems\EzPlatformAdminUi\Notification\TranslatableNotificationHandlerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class SettingsStorageSubscriber
 * @package EzPlatform\HubSpot\HubSpot\Repository\EventSubscriber
 */
class SettingsStorageSubscriber implements EventSubscriberInterface
{
    /** @var \EzPlatform\HubSpot\HubSpot\Services\SettingsService */
    private $settingsService;

    /** @var \EzPlatform\HubSpotBundle\Entity\HubSpot\ApiKey */
    private $apiKey;

    /** @var \EzPlatform\HubSpot\HubSpot\Factory\Core\HubSpot */
    private $hubSpot;

    /** @var \Psr\Log\LoggerInterface */
    private $logger;

    /** @var \EzSystems\EzPlatformAdminUi\Notification\TranslatableNotificationHandlerInterface */
    private $notificationHandler;

    /** @var \EzPlatform\HubSpot\HubSpot\Services\API\SocialMediaService */
    private $socialMediaService;

    /**
     * SettingsStorageSubscriber constructor.
     * @param \EzPlatform\HubSpot\HubSpot\Repository\Storage\Handler\ApiKeyStorageRepositoryHandler $apiKeyStorageRepositoryHandler
     */
    public function __construct(
        SettingsService $settingsService,
        SocialMediaService $socialMediaService,
        ApiKey $apiKey,
        HubSpot $hubSpot,
        LoggerInterface $logger,
        TranslatableNotificationHandlerInterface $notificationHandler
    ) {
        $this->settingsService = $settingsService;
        $this->apiKey = $apiKey;
        $this->hubSpot = $hubSpot;
        $this->logger = $logger;
        $this->notificationHandler = $notificationHandler;
        $this->socialMediaService = $socialMediaService;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            ApiKeyFormEvent::UPDATE_APIKEY => [
                    ['updateApiKey', 10],
                ],
        ];
    }

    /**
     * @param \EzPlatform\HubSpot\HubSpot\Repository\Event\ApiKeyFormEvent $apiKeyFormEvent
     */
    public function updateApiKey(ApiKeyFormEvent $apiKeyFormEvent)
    {
        $this->apiKey->setApiKey($apiKeyFormEvent->getFormData()->getApiKey());
        $this->apiKey->setTimestamp(new \DateTime());
        $this->apiKey->setUserId($apiKeyFormEvent->userId);
        $this->apiKey->setStatus(0);

        $this->updateData($apiKeyFormEvent->getFormData()->getApiKey());
        $this->settingsService->addRecord($this->apiKey);
    }

    /**
     * @param string $apiKey
     * @return \EzPlatform\HubSpotBundle\Entity\HubSpot\ApiKey
     */
    public function updateData(string $apiKey)
    {
        try {
            $channels = $this->socialMediaService->getPublishingChannelsFromHubspotApi($apiKey);
            if (isset($channels->data) && count($channels->data) > 0) {
                $storageData = [];
                foreach ($channels->data as $channel) {
                    $storageData[]=[
                        'channelId'  => $channel->channelId,
                        'channelName'  => $channel->name,
                        'channelAvatar'  => $channel->avatarUrl ?? null,
                        'channelGuid'  => $channel->channelGuid,
                        'channelKey'  => $channel->channelKey,
                        'channelType'  => $channel->channelType,
                        'active'  => $channel->active,
                        'canPublish'  => $channel->canPublish,
                        'accountType'  => $channel->accountType,
                    ] ;
                }
                $this->apiKey->setStatus(1);
                return $this->apiKey->setData($storageData);
            } else {
                return $this->apiKey->setData(null);
            }
        } catch (\Exception $e) {
            $this->notificationHandler->error(
            /** @Desc("An error is occured when trying to retrieve the list of social channels. For more information check the application logs") */
                'hubspot.social.channels.error',
                [],
                'hubspot'
            );
            $this->logger->error($e->getMessage());

            return $this->apiKey->setData(null);
        }
    }
}
