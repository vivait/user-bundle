<?php

namespace Vivait\UserBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class EntityNamespaceCompilerPass implements CompilerPassInterface
{
    /**
     * @var array
     */
    private $mapping;

    function __construct(array $mapping)
    {
        $this->mapping = $mapping;
    }

    /**
     * You can modify the container here before it is dumped to PHP code.
     *
     * @param ContainerBuilder $container
     *
     * @api
     */
    public function process(ContainerBuilder $container)
    {
        $ormConfigDef = $container->getDefinition('doctrine.orm.configuration');
        $ormConfigDef->addMethodCall(
            'setEntityNamespaces',
            [$this->mapping]
        );
    }
}