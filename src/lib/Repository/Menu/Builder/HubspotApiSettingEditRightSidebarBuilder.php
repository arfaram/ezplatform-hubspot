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
use EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent;
use EzSystems\EzPlatformAdminUi\Menu\MenuItemFactory;
use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class HubspotApiSettingEditRightSidebarBuilder
 * @package EzPlatform\HubSpot\Repository\Menu\Builder
 */
final class HubspotApiSettingEditRightSidebarBuilder extends AbstractBuilder implements TranslationContainerInterface
{
    const ITEM_SETTINGS_API_KEY_SAVE = 'hubspot_api_setting_edit__sidebar_right__save';

    const ITEM_SETTINGS_API_KEY__CANCEL = 'hubspot_api_setting_edit__sidebar_right__cancel';

    /** @var \Symfony\Contracts\Translation\TranslatorInterface */
    private $translator;

    /**
     * @param \EzSystems\EzPlatformAdminUi\Menu\MenuItemFactory $menuItemFactory
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \Symfony\Contracts\Translation\TranslatorInterface $translator
     */
    public function __construct(
        MenuItemFactory $menuItemFactory,
        EventDispatcherInterface $eventDispatcher,
        TranslatorInterface $translator
    ) {
        parent::__construct($menuItemFactory, $eventDispatcher);

        $this->translator = $translator;
    }


    /**
     * @return string
     */
    protected function getConfigureEventName(): string
    {
        return HubspotConfigureMenuEventName::HUBSPOT_MENU_API_SETTING_EDIT_SIDEBAR_RIGHT;
    }

    /**
     * @param array $options
     * @return \Knp\Menu\ItemInterface
     */
    protected function createStructure(array $options): ItemInterface
    {
        /** @var \Knp\Menu\ItemInterface $menu */
        $menu = $this->factory->createItem('root');

        $menu->setChildren([
            self::ITEM_SETTINGS_API_KEY_SAVE => $this->createMenuItem(
                self::ITEM_SETTINGS_API_KEY_SAVE,
                [
                    'attributes' => [
                        'class' => 'btn--trigger',
                        'data-click' => $options['submit_selector'],
                    ],
                    'extras' => ['icon' => 'save'],
                ]
            ),
            self::ITEM_SETTINGS_API_KEY__CANCEL => $this->createMenuItem(
                self::ITEM_SETTINGS_API_KEY__CANCEL,
                [
                    'route' => 'ez_platform_hubspot_settings',
                    'extras' => ['icon' => 'circle-close'],
                ]
            ),
        ]);

        return $menu;
    }

    /**
     * @return array
     */
    public static function getTranslationMessages(): array
    {
        return [
            (new Message(self::ITEM_SETTINGS_API_KEY_SAVE, 'menu'))->setDesc('Save'),
            (new Message(self::ITEM_SETTINGS_API_KEY__CANCEL, 'menu'))->setDesc('Discard changes'),
        ];
    }
}
