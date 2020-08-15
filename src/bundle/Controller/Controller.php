<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpotBundle\Controller;

use eZ\Publish\API\Repository\PermissionResolver;
use EzSystems\EzPlatformAdminUiBundle\Controller\Controller as BaseController;

/**
 * Class Controller
 * @package EzPlatform\HubSpotBundle\Controller
 */
abstract class Controller extends BaseController
{
    /** @var \EzPlatform\HubSpot\Repository\User\User $user */
    public $user;

    /** @var \eZ\Publish\API\Repository\PermissionResolver $permissionResolver */
    public $permissionResolver;

    /**
     * @param $user
     */
    public function setUser($user)
    {
        $this->user = $user;
    }

    /**
     * @param \eZ\Publish\API\Repository\PermissionResolver $permissionResolver
     */
    public function setPermissionResolver(PermissionResolver $permissionResolver)
    {
        $this->permissionResolver = $permissionResolver;
    }

    /**
     * @param $module
     * @param $function
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \eZ\Publish\API\Repository\Exceptions\InvalidArgumentException
     */
    public function isAccessGranted($module, $function)
    {
        if (!$this->permissionResolver->hasAccess($module, $function)) {
            return $this->render(
                '@ezdesign/access_denied.html.twig',
                [
                    'access_denied' => 'access_denied',
                ]
            );
        }
    }
}
