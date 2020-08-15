<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\Repository\EventListener;

use eZ\Publish\API\Repository\PermissionResolver;
use EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent;
use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;

/**
 * Class HubspotMenuListener
 * @package EzPlatform\HubSpot\Repository\EventListener
 */
class HubspotMenuListener implements TranslationContainerInterface
{
    const HUBSPOT_MENU_ITEM = 'hubspot__menu__item';
    const HUBSPOT_MENU_DASHBOARD = 'hubspot__menu__dashboard';
    const HUBSPOT_MENU_SOCIAL = 'hubspot__menu__social';
    const HUBSPOT_MENU_SETTINGS = 'hubspot__menu__settings';

    /** @var \eZ\Publish\API\Repository\PermissionResolver */
    private $permissionResolver;

    /**
     * ConfigureMenuListener constructor.
     * @param \eZ\Publish\API\Repository\PermissionResolver $permissionResolver
     */
    public function __construct(
        PermissionResolver $permissionResolver
    ) {
        $this->permissionResolver = $permissionResolver;
    }

    /**
     * @param \EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent $event
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function onMenuConfigure(ConfigureMenuEvent $event)
    {
        $root = $event->getMenu();
        if ($this->permissionResolver->hasAccess('hubspot', 'view')) {
            $root->addChild(
                self::HUBSPOT_MENU_ITEM,
                [
                    'extras' => ['translation_domain' => 'menu'],
                ]
            );
        }
        // activate when we need a dashboard
//        if ($this->permissionResolver->hasAccess('hubspot', 'dashboard')) {
//            $root[self::HUBSPOT_MENU_ITEM]->addChild(
//                self::HUBSPOT_MENU_DASHBOARD,
//                [
//                    'route' => 'ez_platform_hubspot_dashboard',
//                    'extras' => ['translation_domain' => 'menu'],
//                ]
//            );
//        }
        if ($this->permissionResolver->hasAccess('hubspot', 'social')) {
            $root[self::HUBSPOT_MENU_ITEM]->addChild(
                self::HUBSPOT_MENU_SOCIAL,
                [
                    //'route' => 'ez_platform_hubspot_social',  // activate when we need a social dashboard
                    'route' => 'ez_platform_hubspot_social_broadcasts',
                    'extras' => [
                        'translation_domain' => 'menu',
                        'routes' => [
                            'broadcasts' => 'ez_platform_hubspot_social_broadcasts',
                        ],
                    ],
                ]
            );
        }
        if ($this->permissionResolver->hasAccess('hubspot', 'settings')) {
            $root[self::HUBSPOT_MENU_ITEM]->addChild(
                self::HUBSPOT_MENU_SETTINGS,
                [
                    'route' => 'ez_platform_hubspot_settings',
                    'extras' => [
                        'translation_domain' => 'menu',
                        'routes' => [
                            'apikey' => 'ez_platform_hubspot_settings_api_key',
                            'accountInfo' => 'ez_platform_hubspot_settings_account_info',
                            'info' => 'ez_platform_hubspot_settings_info',
                            'usage' => 'ez_platform_hubspot_settings_account_usage',
                        ],
                    ],
                ]
            );
        }
    }

    /** @return array  */
    public static function getTranslationMessages()
    {
        return [
            (new Message(self::HUBSPOT_MENU_ITEM, 'menu'))->setDesc('Hubspot'),
            (new Message(self::HUBSPOT_MENU_DASHBOARD, 'menu'))->setDesc('Dashboard'),
            (new Message(self::HUBSPOT_MENU_SOCIAL, 'menu'))->setDesc('Social'),
            (new Message(self::HUBSPOT_MENU_SETTINGS, 'menu'))->setDesc('Settings'),
        ];
    }
}
