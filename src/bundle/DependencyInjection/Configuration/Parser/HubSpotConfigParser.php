<?php
/*
 * This file is part of the arfaram/ezplatform-hubspot bundle.
 * Copyright (c) 2020 Ramzi Arfaoui  <ramzi_arfa@hotmail.de>
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
declare(strict_types=1);

namespace EzPlatform\HubSpotBundle\DependencyInjection\Configuration\Parser;

use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\AbstractParser;
use eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\ContextualizerInterface;
use Symfony\Component\Config\Definition\Builder\NodeBuilder;

/**
 * Class HubSpotConfigParser
 * @package EzPlatform\HubSpotBundle\DependencyInjection\Configuration\Parser
 */
class HubSpotConfigParser extends AbstractParser
{
    private const ROOT_NODE_KEY = 'hubspot_config';

    private const CONTENT_TYPES_MAP_NODE_KEY = 'content_types_map';

    private const CONTENT_TYPES_PARAMETER_NAME = 'hubspot_config.content_types_map';

    /**
     * configuration example for all backend
     * admin_group:
     *      hubspot_config:
     *          content_types_map:
     *              article:
     *                  facebookpage:
     *                      body: 'title'
     *                      photoUrl: 'image'
     *                      enabled: true
     *                  twitter:
     *                      body: 'title'
     *                      photoUrl: 'image'
     *                      enabled: false
     *
     * @param \Symfony\Component\Config\Definition\Builder\NodeBuilder $nodeBuilder
     */
    public function addSemanticConfig(NodeBuilder $nodeBuilder): void
    {
        $nodeBuilder
            ->arrayNode(self::ROOT_NODE_KEY)
                ->info('Hubspot configuration')
                    ->children()
                        ->arrayNode(self::CONTENT_TYPES_MAP_NODE_KEY)
                            ->useAttributeAsKey('identifier')
                            ->arrayPrototype()
                                ->useAttributeAsKey('channel')
                                ->arrayPrototype()
                                    ->children()
                                        ->scalarNode('body')->isRequired()->end()
                                        ->scalarNode('photoUrl')->end()
                                        ->booleanNode('enabled')->defaultFalse()->end()
    //                                    ->arrayNode('channels')
    //                                        ->scalarPrototype()->end()
    //                                    ->end()
                                    ->end()
                                ->end()
                        ->end()
                    ->end()
                ->end();
    }

    /**
     * Does semantic config to internal container parameters mapping for $currentScope.
     *
     * This method is called by the `ConfigurationProcessor`, for each available scopes (e.g. SiteAccess, SiteAccess groups or "global").
     *
     * @param array $scopeSettings Parsed semantic configuration for current scope.
     *                             It is passed by reference, making it possible to alter it for usage after `mapConfig()` has run.
     * @param string $currentScope
     * @param ContextualizerInterface $contextualizer
     */
    public function mapConfig(array &$scopeSettings, $currentScope, ContextualizerInterface $contextualizer)
    {
        if (empty($scopeSettings[self::ROOT_NODE_KEY])) {
            return;
        }

        if (isset($scopeSettings[self::ROOT_NODE_KEY][self::CONTENT_TYPES_MAP_NODE_KEY])) {
            $scopeSettings[self::CONTENT_TYPES_PARAMETER_NAME] =
                $scopeSettings[self::ROOT_NODE_KEY][self::CONTENT_TYPES_MAP_NODE_KEY];
            unset($scopeSettings[self::ROOT_NODE_KEY][self::CONTENT_TYPES_MAP_NODE_KEY]);
        }

        $contextualizer->setContextualParameter(
            self::CONTENT_TYPES_PARAMETER_NAME,
            $currentScope,
            $scopeSettings[self::CONTENT_TYPES_MAP_NODE_KEY] ?? []
        );
    }

    /**
     * @param array $config
     * @param \eZ\Bundle\EzPublishCoreBundle\DependencyInjection\Configuration\SiteAccessAware\ContextualizerInterface $contextualizer
     */
    public function postMap(array $config, ContextualizerInterface $contextualizer): void
    {
        $contextualizer->mapConfigArray(self::CONTENT_TYPES_PARAMETER_NAME, $config);
    }
}
