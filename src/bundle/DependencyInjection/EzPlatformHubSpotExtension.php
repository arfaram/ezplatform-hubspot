<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpotBundle\DependencyInjection;

use Symfony\Component\Config\Loader\DelegatingLoader;
use Symfony\Component\Config\Loader\LoaderResolver;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class EzPlatformHubSpotExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $fileLocator = new FileLocator(__DIR__ . '/../Resources/config');

        $resolver = new LoaderResolver([
            new Loader\YamlFileLoader($container, $fileLocator),
            new Loader\DirectoryLoader($container, $fileLocator),
        ]);

        $loader = new DelegatingLoader($resolver);
        $loader->load('services.yaml');
        $loader->load('ezplatform/services/');
        $loader->load('hubspot/services/');
    }

    /**
     * Allow an extension to prepend the extension configurations.
     */
    public function prepend(ContainerBuilder $container)
    {
        $configs = array(
            'doctrine.yaml' => 'doctrine',
        );

        foreach ($configs as $fileName => $extensionName) {
            $configFile = __DIR__ . '/../Resources/config/' . $fileName;
            $config = Yaml::parseFile($configFile);
            $container->prependExtensionConfig($extensionName, $config);
        }
    }
}
