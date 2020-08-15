<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\HubSpot\Repository\Storage\Doctrine;

use Doctrine\DBAL\Connection;
use EzPlatform\HubSpotBundle\Entity\HubSpot\HubspotBroadcastInfo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method HubspotBroadcastInfo|null find($id, $lockMode = null, $lockVersion = null)
 * @method HubspotBroadcastInfo|null findOneBy(array $criteria, array $orderBy = null)
 * @method HubspotBroadcastInfo[]    findAll()
 * @method HubspotBroadcastInfo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class HubspotBroadcastInfoRepository extends ServiceEntityRepository
{
    /** @var \Doctrine\DBAL\Connection */
    private $connection;

    /**
     * HubspotBroadcastInfoRepository constructor.
     * @param \Doctrine\Persistence\ManagerRegistry $registry
     */
    public function __construct(
        ManagerRegistry $registry,
        Connection $connection
    ) {
        parent::__construct($registry, HubspotBroadcastInfo::class);
        $this->connection = $connection;
    }

    /**
     * @return \Doctrine\DBAL\Connection
     */
    public function getConnection()
    {
        return $this->connection;
    }

    /**
     * @todo createQueryBuilder->insert was added in later Doctrine version
     *
     * @return \Doctrine\ORM\EntityManager
     */
    public function insertRecord()
    {
        return $this->getEntityManager();
    }

    /**
     * @param int $contentId
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getRecords(int $contentId)
    {
        return $this->createQueryBuilder('h')
            ->andWhere('h.contentId = :val')
            ->setParameter('val', $contentId);
    }

    /**
     * @param int $contentId
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function removeRecord(int $contentId)
    {
        return $this->createQueryBuilder('h')
            ->delete()
            ->where('h.contentId = ?1')
            ->setParameter(1, $contentId);
    }
}
