<?php

namespace DavidBadura\FixturesBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\Reference;

/**
 *
 * @author David Badura <d.badura@gmx.de>
 */
class ConverterPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('davidbadura_fixtures.converter_repository')) {
            return;
        }

        $converters = array();
        foreach ($container->findTaggedServiceIds('davidbadura_fixtures.converter') as $id => $attributes) {
            $converters[] = new Reference($id);
        }

        $container->getDefinition('davidbadura_fixtures.converter_repository')->addMethodCall('addConverters', array(
            $converters
        ));
    }

}
