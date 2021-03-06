<?php

namespace ActiveLAMP\Bundle\UtilsBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class ALUtilsExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        $data = Yaml::parse(file_get_contents(__DIR__ . '/../Resources/data/states.yml'));

        $states = array();

        foreach ($data as $state) {
            $states[$state['abbreviation']] = $state['name'];
        }

        $stateType = $container->getDefinition('al_utils.form_type.us_states');
        $stateType->setArguments(array(
            $states,
        ));

    }
}
