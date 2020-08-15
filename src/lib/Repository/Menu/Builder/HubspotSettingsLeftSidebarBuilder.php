<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\Repository\Menu\Builder;

use EzPlatform\HubSpot\Repository\Menu\Event\HubspotConfigureMenuEventName;
use EzSystems\EzPlatformAdminUi\Menu\AbstractBuilder;
use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use Knp\Menu\ItemInterface;

/**
 * Class HubspotSettingsLeftSidebarBuilder
 * @package EzPlatform\HubSpot\Repository\Menu\Builder
 */
class HubspotSettingsLeftSidebarBuilder extends AbstractBuilder implements TranslationContainerInterface
{
    /**
     *
     */
    public const ITEM_SETTINGS_API_KEY = 'hubspot__menu__settings__api__key';
    /**
     *
     */
    public const ITEM_SETTINGS_ACCOUNT_INFO = 'hubspot__menu__settings__account_info';
    /**
     *
     */
    public const ITEM_SETTINGS_ACCOUNT_USAGE = 'hubspot__menu__settings__account_usage';
    /**
     *
     */
    public const ITEM_SOCIAL_INFO = 'hubspot__menu__social__info';

    /**
     * @return string
     */
    protected function getConfigureEventName(): string
    {
        return HubspotConfigureMenuEventName::HUBSPOT_MENU_SETTINGS_SIDEBAR;
    }

    /**
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    protected function createStructure(array $options): ItemInterface
    {
        /** @var ItemInterface $menu */
        $menu = $this->factory->createItem('root');

        $menu->setChildren([
            self::ITEM_SETTINGS_API_KEY => $this->createMenuItem(
                self::ITEM_SETTINGS_API_KEY,
                [
                    'route' => 'ez_platform_hubspot_settings_api_key',
                    'extras' => ['icon' => 'edit'],
                ]
            ),
            self::ITEM_SETTINGS_ACCOUNT_INFO => $this->createMenuItem(
                self::ITEM_SETTINGS_ACCOUNT_INFO,
                [
                    'route' => 'ez_platform_hubspot_settings_account_info',
                    'extras' => ['icon' => 'profile'],
                ]
            ),
            self::ITEM_SOCIAL_INFO => $this->createMenuItem(
                self::ITEM_SOCIAL_INFO,
                [
                    'route' => 'ez_platform_hubspot_settings_info',
                    'extras' => ['icon' => 'system-information'],
                ]
            ),
            self::ITEM_SETTINGS_ACCOUNT_USAGE => $this->createMenuItem(
                self::ITEM_SETTINGS_ACCOUNT_USAGE,
                [
                    'route' => 'ez_platform_hubspot_settings_account_usage',
                    'extras' => ['icon' => 'stats'],
                ]
            ),
        ]);

        return $menu;
    }

    /**
     * @return \JMS\TranslationBundle\Model\Message[]
     */
    public static function getTranslationMessages(): array
    {
        return [
            (new Message(self::ITEM_SETTINGS_API_KEY, 'menu'))->setDesc('API Key'),
            (new Message(self::ITEM_SETTINGS_ACCOUNT_INFO, 'menu'))->setDesc('Account Info'),
            (new Message(self::ITEM_SOCIAL_INFO, 'menu'))->setDesc('Info'),
        ];
    }
}
