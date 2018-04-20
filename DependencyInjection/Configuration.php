<?php
namespace SmartInformationSystems\SmsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

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
            ->scalarNode('from')->isRequired()->end()
            ->arrayNode('allowed_phones')
                ->prototype('scalar')->end()
            ->end()
            ->arrayNode('transport')->isRequired()->children()
                ->enumNode('type')->values(array('dummy', 'smsaero', '01sms'))->isRequired()->end()
                ->arrayNode('params')->children()
                    ->scalarNode('username')->end()
                    ->scalarNode('password')->end()
                ->end()
            ->end()
        ->end();

        return $treeBuilder;
    }
}
