<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\HubSpot\Repository\Storage\Handler;

use EzPlatform\HubSpot\HubSpot\Repository\Storage\Doctrine\ApiKeyStorageRepository;
use EzPlatform\HubSpotBundle\Entity\HubSpot\ApiKey;

/**
 * Class ApiKeyStorageRepositoryHandler
 * @package EzPlatform\HubSpot\HubSpot\Repository\Storage\Handler
 */
class ApiKeyStorageRepositoryHandler
{
    /** @var \EzPlatform\HubSpot\HubSpot\Repository\Storage\Doctrine\ApiKeyStorageRepository $apiKeyRepository */
    private $apiKeyRepository;

    /**
     * @todo add a gateway to handle other storage layers
     *
     * ApiKeyStorageRepositoryHandler constructor.
     * @param \EzPlatform\HubSpot\HubSpot\Repository\Storage\Doctrine\ApiKeyStorageRepository $apiKeyRepository
     */
    public function __construct(ApiKeyStorageRepository $apiKeyRepository)
    {
        $this->apiKeyRepository = $apiKeyRepository;
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function getAPIKeyRecord()
    {
        return $this->apiKeyRepository->selectRecord()->getQuery()->getOneOrNullResult();
    }

    /**
     * @param \EzPlatform\HubSpotBundle\Entity\HubSpot\ApiKey $apiKey
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     */
    public function addRecord(ApiKey $apiKey)
    {
        $connection = $this->apiKeyRepository->getConnection();
        $connection->beginTransaction();

        if ($apiKey->getId() !== null) {
            try {
                $this->apiKeyRepository->updateRecord($apiKey)->getQuery()->execute();
                $connection->commit();
            } catch (\Exception $e) {
                $connection->rollBack();
                throw $e;
            }
        } else {
            try {
                $this->apiKeyRepository->insertRecord()->persist($apiKey);
                $this->apiKeyRepository->insertRecord()->flush();
                $connection->commit();
            } catch (\Exception $e) {
                $connection->rollBack();
                throw $e;
            }
        }
    }
}
