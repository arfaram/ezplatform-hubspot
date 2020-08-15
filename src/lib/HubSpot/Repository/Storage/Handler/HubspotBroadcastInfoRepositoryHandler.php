<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\HubSpot\Repository\Storage\Handler;

use EzPlatform\HubSpot\HubSpot\Repository\Storage\Doctrine\HubspotBroadcastInfoRepository;
use EzPlatform\HubSpot\HubSpot\Repository\Storage\Mapper\HubspotBroadcastInfoRepositoryMapper;
use EzPlatform\HubSpot\Repository\Value\BroadcastStorageData;
use EzPlatform\HubSpotBundle\Entity\HubSpot\HubspotBroadcastInfo;

/**
 * Class HubspotBroadcastInfoRepositoryHandler
 * @package EzPlatform\HubSpot\HubSpot\Repository\Storage\Handler
 */
class HubspotBroadcastInfoRepositoryHandler
{
    /** @var \EzPlatform\HubSpot\HubSpot\Repository\Storage\Doctrine\HubspotBroadcastInfoRepository */
    private $hubspotBroadcastInfoRepository;

    /** @var \EzPlatform\HubSpotBundle\Entity\HubSpot\HubspotBroadcastInfo */
    private $hubspotBroadcastInfo;

    /** @var \EzPlatform\HubSpot\HubSpot\Repository\Storage\Mapper\HubspotBroadcastInfoRepositoryMapper */
    private $hubspotBroadcastInfoRepositoryMapper;

    /**
     * HubspotBroadcastInfoRepositoryHandler constructor.
     * @param \EzPlatform\HubSpot\HubSpot\Repository\Storage\Doctrine\HubspotBroadcastInfoRepository $hubspotBroadcastInfoRepository
     * @param \EzPlatform\HubSpotBundle\Entity\HubSpot\HubspotBroadcastInfo $hubspotBroadcastInfo
     * @param \EzPlatform\HubSpot\HubSpot\Repository\Storage\Mapper\HubspotBroadcastInfoRepositoryMapper $hubspotBroadcastInfoRepositoryMapper
     */
    public function __construct(
        HubspotBroadcastInfoRepository $hubspotBroadcastInfoRepository,
        HubspotBroadcastInfo $hubspotBroadcastInfo,
        HubspotBroadcastInfoRepositoryMapper $hubspotBroadcastInfoRepositoryMapper
    ) {
        $this->hubspotBroadcastInfoRepository = $hubspotBroadcastInfoRepository;
        $this->hubspotBroadcastInfo = $hubspotBroadcastInfo;
        $this->hubspotBroadcastInfoRepositoryMapper = $hubspotBroadcastInfoRepositoryMapper;
    }

    /**
     * @param \EzPlatform\HubSpot\Repository\Value\BroadcastStorageData $broadcastStorageData
     * @throws \Doctrine\Common\Persistence\Mapping\MappingException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function addRecord(BroadcastStorageData $broadcastStorageData): void
    {
        $this->hubspotBroadcastInfoRepository->insertRecord()->persist($this->hubspotBroadcastInfoRepositoryMapper->addRecordMapper($broadcastStorageData));
        $this->hubspotBroadcastInfoRepository->insertRecord()->flush();
        $this->hubspotBroadcastInfoRepository->insertRecord()->clear();
    }

    /**
     * @todo add cache layer
     * @param int $contentId
     * @return int|mixed|string
     */
    public function getRecords(int $contentId)
    {
        return $this->hubspotBroadcastInfoRepository->getRecords($contentId)->getQuery()->execute();
    }

    /**
     * @todo add cache layer
     * @param int $contentId
     * @throws \Doctrine\DBAL\ConnectionException
     */
    public function deleteBroadcastInfo(int $contentId)
    {
        $connection = $this->hubspotBroadcastInfoRepository->getConnection();
        $connection->beginTransaction();
        try {
            $this->hubspotBroadcastInfoRepository->removeRecord($contentId)->getQuery()->execute();
            $connection->commit();
        } catch (\Exception $e) {
            $connection->rollBack();
            //throw $e;
        }
    }
}
