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
 * Class HubspotSocialLeftSidebarBuilder
 * @package EzPlatform\HubSpot\Repository\Menu\Builder
 */
class HubspotSocialLeftSidebarBuilder extends AbstractBuilder implements TranslationContainerInterface
{
    /**
     *
     */
    public const ITEM_SOCIAL_BROADCASTS = 'hubspot__menu__social__broadcasts';
    /**
     *
     */
    public const ITEM_SOCIAL_INFO = 'hubspot__menu__social__info';

    /**
     * @return string
     */
    protected function getConfigureEventName(): string
    {
        return HubspotConfigureMenuEventName::HUBSPOT_MENU_SOCIAL_SIDEBAR;
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
            self::ITEM_SOCIAL_BROADCASTS => $this->createMenuItem(
                self::ITEM_SOCIAL_BROADCASTS,
                [
                    'route' => 'ez_platform_hubspot_social_broadcasts',
                    'extras' => ['icon' => 'erp'],
                ]
            ),
            self::ITEM_SOCIAL_INFO => $this->createMenuItem(
                self::ITEM_SOCIAL_INFO,
                [
                    'route' => 'ez_platform_hubspot_social_info',
                    'extras' => ['icon' => 'system-information'],
                ]
            ),
            //others
        ]);

        return $menu;
    }

    /**
     * @return \JMS\TranslationBundle\Model\Message[]
     */
    public static function getTranslationMessages(): array
    {
        return [
            (new Message(self::ITEM_SOCIAL_INFO, 'menu'))->setDesc('Info'),
        ];
    }
}
