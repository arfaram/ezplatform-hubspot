<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpot\Repository\User;

use eZ\Publish\API\Repository\Exceptions\NotFoundException;
use eZ\Publish\API\Repository\Repository as RepositoryInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * Class User
 * @package EzPlatform\HubSpot\Repository\User
 */
class User
{
    /** @var \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface */
    private $tokenStorage;

    /** @var \eZ\Publish\API\Repository\Repository */
    private $repository;

    /** @var \Psr\Log\LoggerInterface */
    private $logger;

    /**
     * User constructor.
     * @param \Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface $tokenStorage
     */
    public function __construct(
        TokenStorageInterface $tokenStorage,
        RepositoryInterface $repository,
        LoggerInterface $logger
    ) {
        $this->tokenStorage = $tokenStorage;

        $this->repository = $repository;
        $this->logger = $logger;
    }

    /**
     *
     */
    public function getCurrentUserId()
    {
        $token = $this->tokenStorage->getToken();

        if (!$token || !\is_object($token->getUser())) {
            return;
        }

        return $token->getUser()->getAPIUser()->contentInfo->id;
    }

    /**
     * @param $creator
     * @return mixed
     * @throws \Exception
     */
    public function getCurrentUserName($creator)
    {
        return $this->repository->sudo(
            function (RepositoryInterface $repository) use ($creator) {
                try {
                    $userContent = $repository->getContentService()->loadContent($creator);

                    return  $userContent->getName();
                } catch (NotFoundException $exception) {
                    //user has been deleted
                    $this->logger->warning(sprintf(
                        'Unable to fetch creator content for contentId %s, (original exception: %s)',
                        $creator,
                        $exception->getMessage()
                    ));
                }
            }
        );
    }
}
