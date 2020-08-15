<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpotBundle\DependencyInjection\Security\PolicyProvider;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Security\PolicyProvider\YamlPolicyProvider;

/**
 * Class UIEzPlatformHubSpotBundlePolicyProvider
 * @package EzPlatform\HubSpotBundle\DependencyInjection\Security\PolicyProvider
 */
class UIEzPlatformHubSpotBundlePolicyProvider extends YamlPolicyProvider
{
    /** @var string $path bundle path */
    protected $path;

    /**
     * UIEzPlatformHubSpotBundlePolicyProvider constructor.
     * @param string $path
     */
    public function __construct(string $path)
    {
        $this->path = $path;
    }

    /**
     * @return array
     */
    public function getFiles()
    {
        return [$this->path . '/Resources/config/ezplatform/policies.yaml'];
    }
}
