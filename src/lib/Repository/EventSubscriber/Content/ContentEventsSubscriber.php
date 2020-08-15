<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\Repository\EventSubscriber\Content;

use eZ\Publish\API\Repository\Events\Trash\DeleteTrashItemEvent;
use eZ\Publish\API\Repository\Events\Trash\EmptyTrashEvent;
use EzPlatform\HubSpot\HubSpot\Repository\Storage\Handler\HubspotBroadcastInfoRepositoryHandler;

/**
 * Class ContentEventsSubscriber
 * @package EzPlatform\HubSpot\Repository\EventSubscriber\Content
 */
final class ContentEventsSubscriber extends AbstractSubscriber
{

    /** @var \EzPlatform\HubSpot\HubSpot\Repository\Storage\Handler\HubspotBroadcastInfoRepositoryHandler */
    private $broadcastInfoRepositoryHandler;

    /**
     * ContentEventsSubscriber constructor.
     * @param \EzPlatform\HubSpot\HubSpot\Repository\Storage\Handler\HubspotBroadcastInfoRepositoryHandler $broadcastInfoRepositoryHandler
     */
    public function __construct(
        HubspotBroadcastInfoRepositoryHandler $broadcastInfoRepositoryHandler
    ) {
        $this->broadcastInfoRepositoryHandler = $broadcastInfoRepositoryHandler;
    }

    /**
     * @return array
     */
    public static function getSubscribedEvents()
    {
        return [
            EmptyTrashEvent::class => 'onEmptyTrash',
            DeleteTrashItemEvent::class => 'onDeleteTrashItem'
        ];
    }

    /**
     * @param \eZ\Publish\API\Repository\Events\Trash\DeleteTrashItemEvent $event
     */
    public function onDeleteTrashItem(DeleteTrashItemEvent $event): void
    {
        $trashItemDeleteResult = $event->getResult();

        if (!$trashItemDeleteResult->contentRemoved) {
            return;
        }

        $this->doDeleteBroadcastInfo($trashItemDeleteResult->contentId);
    }

    /**
     * @param \eZ\Publish\API\Repository\Events\Trash\EmptyTrashEvent $event
     */
    public function onEmptyTrash(EmptyTrashEvent $event): void
    {
        $resultList = $event->getResultList();

        foreach ($resultList as $trashItemDeleteResult) {
            if (!$trashItemDeleteResult->contentRemoved) {
                continue;
            }

            $this->doDeleteBroadcastInfo($trashItemDeleteResult->contentId);
        }
    }

    /**
     * @param int $contentId
     * @throws \Doctrine\DBAL\ConnectionException
     */
    private function doDeleteBroadcastInfo(
        int $contentId
    ): void {
        $this->broadcastInfoRepositoryHandler->deleteBroadcastInfo($contentId);
    }
}
