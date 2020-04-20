<?php
/**
 * @author Glenn Schmidt <glenn@codeacious.com>
 * @copyright Copyright 2015 Codeacious Pty Ltd
 */

namespace Codeacious\OAuth2Provider\MvcAuth;

use Codeacious\OAuth2Provider\Authentication\AccessTokenAdapter;
use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Laminas\ApiTools\MvcAuth\Authentication\DefaultAuthenticationListener;
use Laminas\Http\PhpEnvironment\Request as HttpRequest;
use Laminas\ServiceManager\Exception\ServiceNotCreatedException;
use Laminas\ServiceManager\Exception\ServiceNotFoundException;
use Laminas\ServiceManager\Factory\FactoryInterface;

/**
 * Factory to create the AuthenticationListener service.
 *
 * Designed as a substitute for the DefaultAuthenticationListenerFactory provided with the
 * zfcampus/zf-mvc-auth package.
 */
class AuthenticationListenerFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        $config = $container->get('config');
        $authConfig = $config['zf-mvc-auth'];
        if (isset($authConfig['authentication']['oauth2provider'])
            && $container->has('Request')
            && ($container->get('Request') instanceof HttpRequest))
        {
            $adapter = new AccessTokenAdapter(
                $container->get($authConfig['authentication']['oauth2provider'])
            );

            $listener = new AuthenticationListener($adapter);

            if (isset($authConfig['authorization']['uri_whitelist']))
                $listener->setUriWhitelist($authConfig['authorization']['uri_whitelist']);

            return $listener;
        }
        else
        {
            //Fall back to the default factory
            $factory = new \Laminas\ApiTools\MvcAuth\Factory\DefaultAuthenticationListenerFactory();
            return $factory($container, DefaultAuthenticationListener::class);
        }
    }
}