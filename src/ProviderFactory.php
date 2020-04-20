<?php
/**
 * @author Glenn Schmidt <glenn@codeacious.com>
 * @copyright Copyright 2015 Codeacious Pty Ltd
 */

namespace Codeacious\OAuth2Provider;

use Codeacious\OAuth2Provider\Exception\ConfigurationException;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\FactoryInterface;

class ProviderFactory implements FactoryInterface
{
    /**
     * @var string
     */
    private $serviceConfigKey;


    /**
     * @param string $serviceConfigKey
     */
    public function __construct($serviceConfigKey = self::DEFAULT_SERVICE_CONFIG_KEY)
    {
        $this->serviceConfigKey = $serviceConfigKey;
    }

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param mixed $options
     * @return mixed
     */

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = array();

        if ($container->has('Config'))
        {
            $c = $container->get('Config');
            if (isset($c[$this->serviceConfigKey])
                && is_array($c[$this->serviceConfigKey]))
            {
                $config = $c[$this->serviceConfigKey];
            }
        }

        /* @var $serverFactory Config\Factory */
        $serverFactory = $container->get(Config\ServerFactory::class);
        $server = $serverFactory->create($config, $this->serviceConfigKey);

        $class = self::DEFAULT_PROVIDER_CLASS;
        if (isset($config['class']))
        {
            $class = $config['class'];
            if (!class_exists($class))
                throw new ConfigurationException('Provider class "'.$class.'" not found');
        }
        /* @var $provider Provider */
        $provider = new $class($server, $container->get('Request'));
        return $provider;
    }


    const DEFAULT_SERVICE_CONFIG_KEY = 'oauth2provider';
    const DEFAULT_PROVIDER_CLASS = '\Codeacious\OAuth2Provider\Provider';
}