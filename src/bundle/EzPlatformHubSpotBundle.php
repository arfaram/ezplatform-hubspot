<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpotBundle;

use EzPlatform\HubSpotBundle\DependencyInjection\Configuration\Parser;
use EzPlatform\HubSpotBundle\DependencyInjection\Security\PolicyProvider\UIEzPlatformHubSpotBundlePolicyProvider;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class EzPlatformHubSpotBundle
 * @package EzPlatform\HubSpotBundle
 */
class EzPlatformHubSpotBundle extends Bundle
{
    /**
     * @param \Symfony\Component\DependencyInjection\ContainerBuilder $container
     */
    public function build(ContainerBuilder $container)
    {
        /** @var \eZ\Bundle\EzPublishCoreBundle\DependencyInjection\EzPublishCoreExtension $kernelExtension */
        $kernelExtension = $container->getExtension('ezpublish');
        $kernelExtension->addPolicyProvider(new UIEzPlatformHubSpotBundlePolicyProvider($this->getPath()));

        $kernelExtension->addConfigParser(new Parser\HubSpotConfigParser());
        $kernelExtension->addDefaultSettings(__DIR__ . '/Resources/config/ezplatform', ['default_settings.yaml']);
    }
}
