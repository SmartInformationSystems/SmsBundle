<?php

namespace SmartInformationSystems\SmsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('smart_information_systems_sms');

        $rootNode->children()
            ->arrayNode('allowed_phones')
                ->prototype('scalar')->end()
            ->end()
            ->arrayNode('transport')->isRequired()->children()
                ->enumNode('type')->values(array('dummy', 'smsaero'))->isRequired()->end()
                ->arrayNode('params')->children()
                    ->scalarNode('username')->end()
                    ->scalarNode('password')->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
