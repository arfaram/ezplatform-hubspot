<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpotBundle\Controller;

use eZ\Publish\API\Repository\LocationService;
use EzPlatform\HubSpot\HubSpot\Repository\Form\Data\DateBasedHubspotOptionsData;
use EzPlatform\HubSpot\HubSpot\Repository\Form\Type\DateBasedHubspotOptionsDataType;
use EzPlatform\HubSpot\Repository\Event\ContentMappingEvent;
use EzPlatform\HubSpot\Repository\Resolver\HubspotChannelsAndConfigResolver;
use EzSystems\EzPlatformAdminUi\Form\SubmitHandler;
use EzSystems\EzPlatformAdminUi\Notification\TranslatableNotificationHandlerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class ContentBroadcastController
 * @package EzPlatform\HubSpotBundle\Controller
 */
class ContentBroadcastController extends Controller
{
    /** @var \eZ\Publish\API\Repository\LocationService */
    private $locationService;

    /** @var \Symfony\Component\Routing\RouterInterface */
    private $router;

    /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface */
    private $eventDispatcher;

    /** @var \EzSystems\EzPlatformAdminUi\Notification\TranslatableNotificationHandlerInterface */
    private $notificationHandler;

    /** @var \Symfony\Component\HttpFoundation\RequestStack */
    private $requestStack;

    /** @var \EzSystems\EzPlatformAdminUi\Form\SubmitHandler */
    private $submitHandler;

    /** @var \EzPlatform\HubSpot\Repository\Resolver\HubspotChannelsAndConfigResolver */
    private $hubspotChannelsAndConfigResolver;

    /**
     * ContentBroadcastController constructor.
     * @param \eZ\Publish\API\Repository\LocationService $locationService
     * @param \Symfony\Component\Routing\RouterInterface $router
     * @param \Symfony\Component\HttpFoundation\RequestStack $requestStack
     * @param \EzPlatform\HubSpot\Repository\Resolver\HubspotChannelsAndConfigResolver $hubspotChannelsAndConfigResolver
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \EzSystems\EzPlatformAdminUi\Notification\TranslatableNotificationHandlerInterface $notificationHandler
     * @param \EzSystems\EzPlatformAdminUi\Form\SubmitHandler $submitHandler
     */
    public function __construct(
        LocationService $locationService,
        RouterInterface $router,
        RequestStack $requestStack,
        HubspotChannelsAndConfigResolver $hubspotChannelsAndConfigResolver,
        EventDispatcherInterface $eventDispatcher,
        TranslatableNotificationHandlerInterface $notificationHandler,
        SubmitHandler $submitHandler
    ) {
        $this->locationService = $locationService;
        $this->router = $router;
        $this->eventDispatcher = $eventDispatcher;
        $this->notificationHandler = $notificationHandler;
        $this->requestStack = $requestStack;
        $this->submitHandler = $submitHandler;
        $this->hubspotChannelsAndConfigResolver = $hubspotChannelsAndConfigResolver;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param int $locationId
     * @return \Symfony\Component\HttpFoundation\RedirectResponse|\Symfony\Component\HttpFoundation\Response
     * @throws \eZ\Publish\API\Repository\Exceptions\NotFoundException
     * @throws \eZ\Publish\API\Repository\Exceptions\UnauthorizedException
     */
    public function createMessageAction(Request $request, int $locationId)
    {
        /** @var \eZ\Publish\API\Repository\Values\Content\Location $location */
        $location = $this->locationService->loadLocation($locationId);
        $contentId = (int) $location->contentInfo->id;
        $originalRoute = new RedirectResponse($this->router->generate('_ez_content_view', ['contentId' => $contentId]));
        $apiChannelsData = $this->hubspotChannelsAndConfigResolver->resolver($location->getContent());

        if (!$apiChannelsData) {
            $this->notificationHandler->error(
            /** @Desc("No apiChannelsData were found for this location") */
                'hubspot.content.broadcast.controllers.apiChannelsData.error',
                [],
                'hubspot'
            );
            return $originalRoute;
        }

        $form = $this->createForm(
            DateBasedHubspotOptionsDataType::class,
            new DateBasedHubspotOptionsData(
                $location,
                $apiChannelsData
            )
        );
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $result = $this->submitHandler->handle($form, function (DateBasedHubspotOptionsData $data) use ($location, $originalRoute) {
                try {
                    $ContentMappingEvent = new ContentMappingEvent($location, $this->getBaseURL(), $data);

                    $this->eventDispatcher->dispatch($ContentMappingEvent, ContentMappingEvent::MESSAGE_DATA_MAP);

                    $this->notificationHandler->success(
                    /** @Desc("Content '%contentName%' is shared over hubspot.") */
                        'hubspot.share.success',
                        [
                            '%contentName%' => $location->contentInfo->name,
                        ],
                        'views'
                    );

                    return $originalRoute;
                } catch (\Exception $e) {
                    $this->notificationHandler->error($e->getMessage());
                    return $originalRoute;
                }
            });
        }

        return $result instanceof Response
            ? $result
            : $originalRoute;
    }

    /**
     * @todo something better? if the admin domain should not be shared. New domain config per channel or global!
     * @return string
     */
    private function getBaseURL()
    {
        $schema = $this->requestStack->getCurrentRequest()->getScheme();
        $host = $this->requestStack->getCurrentRequest()->getHost();

        return $schema.'://'.$host;
    }
}
