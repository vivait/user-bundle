<?php

namespace Vivait\UserBundle;

use Doctrine\Bundle\DoctrineBundle\DependencyInjection\Compiler\DoctrineOrmMappingsPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Vivait\UserBundle\DependencyInjection\DoctrineCompilerPass;

class VivaitUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }

    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DoctrineCompilerPass());

        $this->registerModel($container);
    }

    /**
     * @param ContainerBuilder $container
     */
    private function registerModel(ContainerBuilder $container)
    {
        $modelDir = realpath(__DIR__);
        $mappings = array(
            __NAMESPACE__ . '\Model' => $modelDir . '/Model',
        );

        // We've also loaded the customer bundle
        if ($container->hasExtension('vivait_customer')) {
            // Enable the compatibility entity
            $mappings[__NAMESPACE__ . '\Customer\Customer'] = $modelDir . '/Customer';
            // TODO: Also add an entity resolver that replaces the standard customer entity
        }

        $container->addCompilerPass(
        // Replace with 'DoctrineOrmMappingsPass' when using DoctrineBundle 1.3
        // See: https://github.com/doctrine/DoctrineBundle/issues/283
            self::createAnnotationMappingDriver(
                array_keys($mappings),
                $mappings,
                [],
                false
            )
        );
    }

    private static function createAnnotationMappingDriver(
        array $namespaces,
        array $directories,
        array $managerParameters = array(),
        $enabledParameter = false
    ) {
        $reader = new Reference('annotation_reader');
        $driver = new Definition('Doctrine\ORM\Mapping\Driver\AnnotationDriver', array($reader, $directories));

        return new DoctrineOrmMappingsPass($driver, $namespaces, $managerParameters, $enabledParameter);
    }
}
