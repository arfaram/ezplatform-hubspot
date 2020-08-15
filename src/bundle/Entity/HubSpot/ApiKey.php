<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */


namespace EzPlatform\HubSpotBundle\Entity\HubSpot;

use EzPlatform\HubSpot\HubSpot\Repository\Storage\Handler\ApiKeyStorageRepositoryHandler;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="hubspot_apikey")
 * @ORM\Entity(repositoryClass="EzPlatform\HubSpot\HubSpot\Repository\Storage\Doctrine\ApiKeyStorageRepository")
 */
class ApiKey
{
    /** @var \EzPlatform\HubSpot\HubSpot\Repository\Storage\Handler\ApiKeyStorageRepositoryHandler $apiKeyStorageRepositoryHandler */
    public $apiKeyStorageRepositoryHandler;

    /**
     * ApiKey constructor.
     * @param \EzPlatform\HubSpot\HubSpot\Repository\Storage\Handler\ApiKeyStorageRepositoryHandler $apiKeyStorageRepositoryHandler
     */
    public function __construct(
        ApiKeyStorageRepositoryHandler $apiKeyStorageRepositoryHandler
    ) {
        $this->apiKeyStorageRepositoryHandler = $apiKeyStorageRepositoryHandler;
    }

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(name="api_key",type="string", length=255)
     * @var string
     */
    public $apiKey;

    /**
     * @ORM\Column(name="timestamp",type="datetime", nullable=true)
     * @var \DateTime
     */
    public $timestamp;

    /**
     * @ORM\Column(name="data",type="json", nullable=true)
     * @var string
     */
    public $data;

    /**
     * @ORM\Column(name="status",type="boolean", nullable=true)
     * @var bool
     */
    public $status;

    /**
     * @ORM\Column(name="user_id",type="integer", nullable=true)
     * @var int
     */
    public $userId;

    /**
     * Get id.
     *
     * @return int
     */
    public function getId(): ?int
    {
        return $this->id ?? $this->recordInformationHelper()->id ?? null;
    }

    /**
     * for update DB entry.
     * @param $id
     * @return int
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this->id;
    }

    /**
     * Set apiKey.
     *
     * @param string $apiKey
     *
     * @return ApiKey
     */
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;

        return $this;
    }

    /**
     * Get the value from:
     * 1. The form if no record exist in DB
     * 2. From DB: $apiKeyExist
     * 3. The first time is null.
     *
     * @return string|null
     */
    public function getApiKey(): ?string
    {
        return $this->apiKey ?? $this->recordInformationHelper()->apiKey ?? null;
    }

    /**
     * Set timestamp.
     *
     * @param \DateTime $timestamp
     *
     * @return ApiKey
     */
    public function setTimestamp(?\DateTime $timestamp = null)
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * Get timestamp.
     *
     * @return \DateTime
     */
    public function getTimestamp(): ? \DateTime
    {
        return $this->timestamp ?? $this->recordInformationHelper()->timestamp ?? null;
    }

    /**
     * Set data.
     *
     * @param array $data
     *
     * @return ApiKey
     */
    public function setData($data)
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Get data.
     *
     * @return string
     */
    public function getData()
    {
        return json_encode($this->data);
    }

    /**
     * Set status. 0: not checked (during retrieving channels settings ->apikey) , 1 : valid
     *
     * @param bool $status
     *
     * @return ApiKey
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status.
     *
     * @return bool
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set userId.
     *
     * @param int $userId
     *
     * @return ApiKey
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId.
     *
     * @return int
     */
    public function getUserId(): ? int
    {
        return $this->userId ?? $this->recordInformationHelper()->userId ?? null;
    }

    /**
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    private function recordInformationHelper()
    {
        return $this->apiKeyStorageRepositoryHandler->getAPIKeyRecord();
    }
}
