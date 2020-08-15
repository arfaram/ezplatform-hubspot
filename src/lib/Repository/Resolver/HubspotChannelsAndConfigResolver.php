<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\Repository\Resolver;

use eZ\Publish\API\Repository\Values\Content\Content;
use eZ\Publish\Core\MVC\ConfigResolverInterface;
use EzPlatform\HubSpot\HubSpot\Repository\Storage\Handler\ApiKeyStorageRepositoryHandler;

/**
 * Class HubspotChannelsAndConfigResolver
 * @package EzPlatform\HubSpot\Repository\Resolver
 */
class HubspotChannelsAndConfigResolver extends ContentTypesBroadcastsResolver
{

    /** @var \EzPlatform\HubSpot\HubSpot\Repository\Storage\Handler\ApiKeyStorageRepositoryHandler */
    private $apiKeyStorageRepositoryHandler;

    /**
     * HubspotChannelsAndConfigResolver constructor.
     * @param \eZ\Publish\Core\MVC\ConfigResolverInterface $configResolver
     * @param \EzPlatform\HubSpot\HubSpot\Repository\Storage\Handler\ApiKeyStorageRepositoryHandler $apiKeyStorageRepositoryHandler
     */
    public function __construct(
        ConfigResolverInterface $configResolver,
        ApiKeyStorageRepositoryHandler $apiKeyStorageRepositoryHandler
    ) {
        parent::__construct($configResolver);
        $this->apiKeyStorageRepositoryHandler = $apiKeyStorageRepositoryHandler;
    }


    /**
     *  Resolver to map channels in DB with those enabled in configuration
     *  if enabled for this content(configuration) and can publish then it will be displayed in the side menu
     *
     * @param \eZ\Publish\API\Repository\Values\Content\Content $content
     * @return iterable|null
     */
    public function resolver(Content $content): ?iterable
    {
        $contentTypeConfigurationResolver =  parent::resolver($content);

        if ($contentTypeConfigurationResolver) {
            $apiChannelsData = $this->getConfiguredApiChannels();
            if (!$apiChannelsData) {
                return null;
            }

            $apiChannelsData = array_filter(
                $apiChannelsData,
                function ($apiChannel) use ($contentTypeConfigurationResolver) {
                    if (array_key_exists(strtolower($apiChannel['channelType']), (array) $contentTypeConfigurationResolver) && $apiChannel['canPublish']) {
                        return $apiChannel;
                    }
                    return false;
                }
            );
            return $apiChannelsData;
        }

        return $contentTypeConfigurationResolver; //null
    }

    /**
     * @return array|null
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getConfiguredApiChannels(): ?array
    {
        return $this->apiKeyStorageRepositoryHandler->getAPIKeyRecord()->data ?? null;
    }
}
