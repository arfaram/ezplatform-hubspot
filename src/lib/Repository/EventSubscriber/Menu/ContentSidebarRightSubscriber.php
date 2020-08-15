<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\Repository\EventSubscriber\Menu;

use eZ\Publish\API\Repository\PermissionResolver;
use EzPlatform\HubSpot\HubSpot\Repository\Form\Factory\FormFactory;
use EzPlatform\HubSpot\HubSpot\Services\API\SocialMediaService;
use EzPlatform\HubSpot\Repository\Resolver\HubspotChannelsAndConfigResolver;
use EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent;
use JMS\TranslationBundle\Model\Message;
use JMS\TranslationBundle\Translation\TranslationContainerInterface;
use Knp\Menu\MenuItem;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Class ContentSidebarRightSubscriber
 * @package EzPlatform\HubSpot\Repository\EventSubscriber\Menu
 */
final class ContentSidebarRightSubscriber implements EventSubscriberInterface, TranslationContainerInterface
{
    const HUBSPOT_CONTENT_SIDEBAR_RIGHT_MENU_ITEM = 'hubspot__content__sidebar__right__menu__item';

    /** @var \eZ\Publish\API\Repository\PermissionResolver*/
    private $permissionResolver;

    /** @var \EzPlatform\HubSpot\HubSpot\Repository\Form\Factory\FormFactory */
    private $formFactory;

    /** @var \EzPlatform\HubSpot\Repository\Resolver\HubspotChannelsAndConfigResolver */
    private $hubspotChannelsAndConfigResolver;

    /** @var \EzPlatform\HubSpot\HubSpot\Services\API\SocialMediaService */
    private $socialMediaService;

    /**
     * ContentSidebarRightSubscriber constructor.
     * @param \eZ\Publish\API\Repository\PermissionResolver $permissionResolver
     * @param \EzPlatform\HubSpot\Repository\Resolver\HubspotChannelsAndConfigResolver $hubspotChannelsAndConfigResolver
     * @param \EzPlatform\HubSpot\HubSpot\Repository\Form\Factory\FormFactory $formFactory
     * @param \EzPlatform\HubSpot\HubSpot\Services\API\SocialMediaService $socialMediaService
     */
    public function __construct(
        PermissionResolver $permissionResolver,
        HubspotChannelsAndConfigResolver $hubspotChannelsAndConfigResolver,
        FormFactory $formFactory,
        SocialMediaService $socialMediaService
    ) {
        $this->permissionResolver = $permissionResolver;
        $this->formFactory = $formFactory;
        $this->hubspotChannelsAndConfigResolver = $hubspotChannelsAndConfigResolver;
        $this->socialMediaService = $socialMediaService;
    }

    /**
     * @return \string[][][]
     */
    public static function getSubscribedEvents(): array
    {
        return [
            ConfigureMenuEvent::CONTENT_SIDEBAR_RIGHT => [
                ['onContentSidebarRightMenuConfigure']
            ],
        ];
    }

    /**
     * @param \EzSystems\EzPlatformAdminUi\Menu\Event\ConfigureMenuEvent $event
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function onContentSidebarRightMenuConfigure(ConfigureMenuEvent $event): void
    {
        $options = $event->getOptions();

        /** @var \eZ\Publish\API\Repository\Values\Content\Content $content */
        $content = $options['content'];

        $contentTypeConfigurationResolver = $this->socialMediaService->contentTypeConfigResolver($content);

        if (!$this->permissionResolver->hasAccess('content', 'create_broadcast') || !$contentTypeConfigurationResolver) {
            return;
        }

        $apiChannelsData = $this->socialMediaService->channelsConfigResolver($content);

        $sharedContentInfo = $this->socialMediaService->getSharedContentInfo($content->id);

        $apiChannelsData = $this->socialMediaService->filterSharedContent($apiChannelsData, $sharedContentInfo);

        /** @var \eZ\Publish\API\Repository\Values\Content\Location $location */
        $location = $options['location'];

        $root = $event->getMenu();

        // the form will not be generatd if there are no channels available. Add Social channels in hubspot to be able to post content. When channels are available they will be displayed in Social > Info interface
        $formView = '';
        if ($apiChannelsData) {
            $formView = $this->formFactory->createContentFormView(
                $location,
                $apiChannelsData
            );
        }
        $root->addChild(
            self::HUBSPOT_CONTENT_SIDEBAR_RIGHT_MENU_ITEM,
            [
                'extras' => [
                    'orderNumber'=> 5,
                    'translation_domain' => 'menu',
                    'icon_path' => '/bundles/ezplatformhubspot/img/hubspot.svg#hubspot',
                    'icon_class' => 'ez-hubspot-show',
                    'template' => '@ezdesign/ui/hubspot_content_widget.html.twig',
                    'template_parameters' => [
                        'form' => $formView ?? null,
                        'sharedContentInfos' => $sharedContentInfo ?? null,
                        'locationId' =>  $location->id
                    ],
                ],
                'attributes' => [
                    'class' => 'ez-btn--extra-actions',
                    'data-actions' => 'hubspot-content-form',
                ],
            ]
        );

        $this->reorderChildren($root);
    }

    /**
     * @param \Knp\Menu\MenuItem $menuRootItem
     */
    private function reorderChildren(MenuItem $menuRootItem): void
    {
        $children = $menuRootItem->getChildren();

        uasort($children, static function (MenuItem $a, MenuItem $b) {
            return $a->getExtra('orderNumber') <=> $b->getExtra('orderNumber');
        });
        $menuRootItem->reorderChildren(array_keys($children));
    }

    /** @return array */
    public static function getTranslationMessages()
    {
        return [
            (new Message(self::HUBSPOT_CONTENT_SIDEBAR_RIGHT_MENU_ITEM, 'menu'))->setDesc('Hubspot'),
        ];
    }
}
