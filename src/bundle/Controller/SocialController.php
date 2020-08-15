<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpotBundle\Controller;

use EzPlatform\HubSpot\HubSpot\Services\API\SocialMediaService;
use Pagerfanta\Adapter\ArrayAdapter;
use Pagerfanta\Pagerfanta;
use SevenShores\Hubspot\Http\Response;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class SocialController
 * @package EzPlatform\HubSpotBundle\Controller
 */
class SocialController extends Controller
{
    /** @var \EzPlatform\HubSpot\HubSpot\Services\API\SocialMediaService */
    private $socialMediaService;

    /**
     * SocialController constructor.
     * @param \EzPlatform\HubSpot\HubSpot\Services\API\SocialMediaService $socialMediaService
     */
    public function __construct(
        SocialMediaService $socialMediaService
    ) {
        $this->socialMediaService = $socialMediaService;
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function indexAction()
    {
        $response = $this->isAccessGranted('hubspot', 'social');
        if ($response instanceof \Symfony\Component\HttpFoundation\Response) {
            return $response;
        }

        return $this->render('@ezdesign/social/index.html.twig');
    }

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function infoAction()
    {
        $response = $this->isAccessGranted('hubspot', 'social');
        if ($response instanceof \Symfony\Component\HttpFoundation\Response) {
            return $response;
        }

        $response = $this->socialMediaService->getPublishingChannelsFromStorage();

        return $this->render(
            '@ezdesign/social/info.html.twig',
            [
                'channels' => $response->data ?? null,
            ]
        );
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function broadcastsAction(Request $request)
    {
        $response = $this->isAccessGranted('hubspot', 'social');
        if ($response instanceof \Symfony\Component\HttpFoundation\Response) {
            return $response;
        }

        $page = $request->query->get('page') ?? 1;

        $response = $this->socialMediaService->getBroadcastMessages(['count' => '100']); //@todo to improve using different tabs

        if ($response instanceof Response && $response->data) {
            $data = $this->sortResponse($response->data);
            $pagerfanta = new Pagerfanta(
                new ArrayAdapter($data)
            );

            $pagerfanta->setMaxPerPage(8);
            $pagerfanta->setCurrentPage(min($page, $pagerfanta->getNbPages()));

            $response = $pagerfanta->getCurrentPageResults();
        }

        return $this->render(
            '@ezdesign/social/broadcasts.html.twig',
            [
                'broadcasts' => $pagerfanta ?? null,
            ]
        );
    }

    /**
     * //TODO do it much better.
     * @param $response
     * @return array
     */
    private function sortResponse($response): array
    {
        $data = array_map(function ($item) {
            if ($item->status === 'SUCCESS' || $item->status === 'DRAFT' || $item->status === 'WAITING') {
                return $item;
            }
        }, $response);

        $data = array_filter($data);

        usort($data, function ($a, $b) {
            return -($a->createdAt <=> $b->createdAt);
        });

        return $data;
    }
}
