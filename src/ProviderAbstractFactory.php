<?php
/**
 * @author Glenn Schmidt <glenn@codeacious.com>
 * @copyright Copyright 2015 Codeacious Pty Ltd
 */

namespace Codeacious\OAuth2Provider;

use Codeacious\OAuth2Provider\Exception\ConfigurationException;
use Interop\Container\ContainerInterface;
use Laminas\ServiceManager\Factory\AbstractFactoryInterface;

class ProviderAbstractFactory implements AbstractFactoryInterface
{

    /**
     * @var array
     */
    private $config;

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

    public function canCreate(ContainerInterface $container, $requestedName)
    {
        $config = $this->getConfig($container);
        return (isset($config[$requestedName]) && is_array($config[$requestedName]));
    }

    /**
     * @param ContainerInterface $container
     * @param string $requestedName
     * @param mixed $options
     * @return Provider
     */

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $config = $this->getConfig($container);
        $config = $config[$requestedName];

        /* @var $serverFactory Config\Factory */
        $serverFactory = $container->get(Config\ServerFactory::class);
        $server = $serverFactory->create($config, $requestedName);

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

    /**
     * @param ContainerInterface $services
     * @return array
     */
    private function getConfig(ContainerInterface $services)
    {
        if ($this->config === null)
        {
            $this->config = array();
            if ($services->has('Config'))
            {
                $config = $services->get('Config');
                if (isset($config[$this->serviceConfigKey])
                    && is_array($config[$this->serviceConfigKey]))
                {
                    $this->config = $config[$this->serviceConfigKey];
                }
            }
        }
        return $this->config;
    }


    const DEFAULT_SERVICE_CONFIG_KEY = 'oauth2providers';
    const DEFAULT_PROVIDER_CLASS = '\Codeacious\OAuth2Provider\Provider';
}