<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpotBundle\Entity\HubSpot;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="hubspot_broadcast_info",indexes={@ORM\Index(name="content_id_idx", columns={"content_id"})})
 * @ORM\Entity(repositoryClass="EzPlatform\HubSpot\HubSpot\Repository\Storage\Doctrine\HubspotBroadcastInfoRepository")
 */
class HubspotBroadcastInfo
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(name="location_id", type="integer")
     */
    private $locationId;

    /**
     * @ORM\Column(name="content_id",type="integer")
     */
    private $contentId;

    /**
     * @ORM\Column(type="integer")
     */
    private $version;

    /**
     * @ORM\Column(type="integer")
     */
    private $timestamp;

    /**
     * @ORM\Column(name="u_channel_type", type="string", length=255, nullable=true)
     */
    private $uChannelType;

    /**
     * @ORM\Column(name="u_channel_name", type="string", length=255, nullable=true)
     */
    private $uChannelName;

    /**
     * @ORM\Column(name="u_channel_id", type="string", length=255, nullable=true)
     */
    public $uChannelId;

    /**
     * @ORM\Column(name="u_schedule_time", type="integer", nullable=true)
     */
    private $uScheduleTime;

    /**
     * @ORM\Column(name="u_option", type="string", length=255, nullable=true)
     */
    private $uOption;

    /**
     * @ORM\Column(name="b_broadcast_guid", type="string", length=255, nullable=true)
     */
    private $broadcastGuid;

    /**
     * @ORM\Column(name="b_status",type="string", length=255, nullable=true)
     */
    private $status;

    /**
     * @ORM\Column(name="b_trigger_at", type="integer", nullable=true)
     */
    private $triggerAt;

    /**
     * @ORM\Column(name="b_is_published", type="boolean", nullable=true)
     */
    private $isPublished;

    /**
     * @ORM\Column(name="b_is_failed", type="boolean", nullable=true)
     */
    private $isFailed;

    /**
     * @ORM\Column(name="b_data", type="json", nullable=true)
     */
    private $data;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return int|null
     */
    public function getLocationId(): ?int
    {
        return $this->locationId;
    }

    /**
     * @param int $locationId
     * @return $this
     */
    public function setLocationId(int $locationId): self
    {
        $this->locationId = $locationId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getContentId(): ?int
    {
        return $this->contentId;
    }

    /**
     * @param int $contentId
     * @return $this
     */
    public function setContentId(int $contentId): self
    {
        $this->contentId = $contentId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getVersion(): ?int
    {
        return $this->version;
    }

    /**
     * @param int $version
     * @return $this
     */
    public function setVersion(int $version): self
    {
        $this->version = $version;

        return $this;
    }
    /**
     * @return string|null
     */
    public function getUChannelName(): ?string
    {
        return $this->uChannelName;
    }

    /**
     * @param string $uChannelType
     * @return $this
     */
    public function setUChannelType(string $uChannelType): self
    {
        $this->uChannelType = $uChannelType;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUChannelType(): ?string
    {
        return $this->uChannelType;
    }

    /**
     * @param string $uChannelName
     * @return $this
     */
    public function setUChannelName(string $uChannelName): self
    {
        $this->uChannelName = $uChannelName;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUChannelId(): ?string
    {
        return $this->uChannelId;
    }

    /**
     * @param string $uChannelId
     */
    public function setUChannelId($uChannelId): self
    {
        $this->uChannelId = $uChannelId;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getUScheduleTime(): ?int
    {
        return $this->uScheduleTime;
    }

    /**
     * @param int $uScheduleTime
     * @return $this
     */
    public function setUScheduleTime(?int $uScheduleTime): self
    {
        $this->uScheduleTime = $uScheduleTime;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getUOption(): ?string
    {
        return $this->uOption;
    }

    /**
     * @param $uOption
     * @return $this
     */
    public function setUOption(?string $uOption): self
    {
        $this->uOption = $uOption;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTimestamp(): ?int
    {
        return $this->timestamp;
    }

    /**
     * @param int $timestamp
     * @return $this
     */
    public function setTimestamp(?int $timestamp): self
    {
        $this->timestamp = $timestamp;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getBroadcastGuid(): ?string
    {
        return $this->broadcastGuid;
    }

    /**
     * @param string $broadcastGuid
     * @return $this
     */
    public function setBroadcastGuid(?string $broadcastGuid): self
    {
        $this->broadcastGuid = $broadcastGuid;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * @param string|null $status
     * @return $this
     */
    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getTriggerAt(): ?int
    {
        return $this->triggerAt;
    }

    /**
     * @param int $triggerAt
     * @return $this
     */
    public function setTriggerAt(?int $triggerAt): self
    {
        $this->triggerAt = $triggerAt;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsPublished(): ?bool
    {
        return $this->isPublished;
    }

    /**
     * @param bool|null $isPublished
     * @return $this
     */
    public function setIsPublished(?bool $isPublished): self
    {
        $this->isPublished = $isPublished;

        return $this;
    }

    /**
     * @return bool|null
     */
    public function getIsFailed(): ?bool
    {
        return $this->isFailed;
    }

    /**
     * @param bool|null $isFailed
     * @return $this
     */
    public function setIsFailed(?bool $isFailed): self
    {
        $this->isFailed = $isFailed;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getData(): ?string
    {
        return $this->data;
    }

    /**
     * @param string|null $data
     * @return $this
     */
    public function setData(?string $data): self
    {
        $this->data = $data;

        return $this;
    }
}
