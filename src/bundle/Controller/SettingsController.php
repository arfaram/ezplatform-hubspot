<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpotBundle\Controller;

use EzPlatform\HubSpot\HubSpot\Repository\Event\ApiKeyFormEvent;
use EzPlatform\HubSpot\HubSpot\Repository\Form\Factory\FormFactory;
use EzPlatform\HubSpot\HubSpot\Services\IntegrationService;
use EzPlatform\HubSpot\HubSpot\Services\API\SocialMediaService;
use EzPlatform\HubSpotBundle\Entity\HubSpot\ApiKey;
use EzSystems\EzPlatformAdminUi\Notification\NotificationHandlerInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class SettingsController
 * @package EzPlatform\HubSpotBundle\Controller
 */
class SettingsController extends Controller
{
    /** @var \EzPlatform\HubSpot\HubSpot\Repository\Form\Factory\FormFactory $formFactory */
    private $formFactory;

    /** @var \EzPlatform\HubSpotBundle\Entity\HubSpot\ApiKey $apiKey */
    private $apiKey;

    /** @var \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher */
    public $eventDispatcher;

    /** @var \EzSystems\EzPlatformAdminUi\Notification\NotificationHandlerInterface */
    private $notificationHandler;

    /** @var \EzPlatform\HubSpot\HubSpot\Services\IntegrationService */
    private $integrationService;

    /** @var \EzPlatform\HubSpot\HubSpot\Services\API\SocialMediaService */
    private $socialMediaService;

    /**
     * SettingsController constructor.
     * @param \EzPlatform\HubSpot\HubSpot\Repository\Form\Factory\FormFactory $formFactory
     * @param \EzPlatform\HubSpotBundle\Entity\HubSpot\ApiKey $apiKey
     * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $eventDispatcher
     * @param \EzSystems\EzPlatformAdminUi\Notification\NotificationHandlerInterface $notificationHandler
     * @param \EzPlatform\HubSpot\HubSpot\Services\IntegrationService $integrationService
     * @param \EzPlatform\HubSpot\HubSpot\Services\API\SocialMediaService $socialMediaService
     */
    public function __construct(
        FormFactory $formFactory,
        ApiKey $apiKey,
        EventDispatcherInterface $eventDispatcher,
        NotificationHandlerInterface $notificationHandler,
        IntegrationService $integrationService,
        SocialMediaService $socialMediaService
    ) {
        $this->formFactory = $formFactory;
        $this->apiKey = $apiKey;
        $this->eventDispatcher = $eventDispatcher;
        $this->notificationHandler = $notificationHandler;
        $this->integrationService = $integrationService;

        $this->socialMediaService = $socialMediaService;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function indexAction()
    {
        $response = $this->isAccessGranted('hubspot', 'settings');
        if ($response instanceof Response) {
            return $response;
        }

        return $this->render('@ezdesign/settings/index.html.twig');
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response|void|null
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function apiKeyAction(Request $request)
    {
        $response = $this->isAccessGranted('hubspot', 'settings');
        if ($response instanceof Response) {
            return $response;
        }

        $form = $this->formFactory->apiKeyFormType(
            $this->apiKey,
            $this->generateUrl('ez_platform_hubspot_settings_api_key')
        );

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $result = function () use ($form) {
                if ($form->isValid()) {
                    try {
                        $formEvent = new ApiKeyFormEvent($form->getData(), $this->user->getCurrentUserId());
                        $this->eventDispatcher->dispatch($formEvent, ApiKeyFormEvent::UPDATE_APIKEY);

                        return;
                    } catch (\Exception $e) {
                        $this->notificationHandler->error($e->getMessage());
                    }
                } else {
                    //e.g field validation
                    $errors = [];
                    foreach ($form->getErrors(true, true) as $formError) {
                        $errors[] = $formError->getMessage();
                        $this->notificationHandler->error($formError->getMessage());
                    }
                }

                return null;
            };

            if ($result() instanceof Response) {
                return $result();
            }
        }

        return $this->render(
            '@ezdesign/settings/api_key.html.twig',
            [
                'form' => $form->createView(),
                'date' => $this->apiKey->getTimestamp(),
                'userId' => $this->apiKey->getUserId() ? $this->user->getCurrentUserName($this->apiKey->getUserId()) : null,
            ]
        );
    }

    /**
     *  After API Key storage
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function infoAction()
    {
        $response = $this->isAccessGranted('hubspot', 'settings');
        if ($response instanceof Response) {
            return $response;
        }

        $response = $this->socialMediaService->getPublishingChannelsFromStorage();

        return $this->render(
            '@ezdesign/settings/info.html.twig',
            [
                'channels' => $response->data ?? null,
            ]
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Exception
     */
    public function accountInfoAction()
    {
        $response = $this->isAccessGranted('hubspot', 'settings');
        if ($response instanceof Response) {
            return $response;
        }

        $response = $this->integrationService->getAccountDetails();

        return $this->render(
            '@ezdesign/settings/account_info.html.twig',
            [
                'account_info' => $response->data ?? null,
                'response_code' => $response ? $response->getStatusCode() : null,
            ]
        );
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function dailyUsageAction()
    {
        $response = $this->isAccessGranted('hubspot', 'settings');
        if ($response instanceof Response) {
            return $response;
        }

        $response = $this->integrationService->getDailyUsageDetails();

        //@todo report issue to hubspot as this request is returning an array compare to doc: https://developers.hubspot.com/docs/methods/check-daily-api-usage response.
        // We will handle it as array for the moment, see template
        return $this->render(
            '@ezdesign/settings/daily_usage.html.twig',
            [
                'daily_usages' => $response->data ?? null,
            ]
        );
    }
}
