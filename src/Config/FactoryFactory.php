<?php
/**
 * @author Glenn Schmidt <glenn@codeacious.com>
 * @version $Id$
 */

namespace Codeacious\OAuth2Provider\Config;

use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class FactoryFactory implements FactoryInterface
{
    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param mixed $options
     * @return Factory
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        if (empty($requestedName) || !class_exists($requestedName))
            return null;

        return new $requestedName($container);
    }
}