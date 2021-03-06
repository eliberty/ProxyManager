<?php

declare(strict_types=1);

namespace ProxyManager\Factory;

use ProxyManager\Proxy\AccessInterceptorValueHolderInterface;
use ProxyManager\ProxyGenerator\AccessInterceptorValueHolderGenerator;
use ProxyManager\ProxyGenerator\ProxyGeneratorInterface;
use ProxyManager\Signature\Exception\InvalidSignatureException;
use ProxyManager\Signature\Exception\MissingSignatureException;
use function get_class;

/**
 * Factory responsible of producing proxy objects
 *
 */
class AccessInterceptorValueHolderFactory extends AbstractBaseFactory
{
    /** @var AccessInterceptorValueHolderGenerator|null */
    private $generator;

    /**
     * @param object     $instance           the object to be wrapped within the value holder
     * @param \Closure[] $prefixInterceptors an array (indexed by method name) of interceptor closures to be called
     *                                       before method logic is executed
     * @param \Closure[] $suffixInterceptors an array (indexed by method name) of interceptor closures to be called
     *                                       after method logic is executed
     *
     * @throws InvalidSignatureException
     * @throws MissingSignatureException
     * @throws \OutOfBoundsException
     */
    public function createProxy(
        object $instance,
        array $prefixInterceptors = [],
        array $suffixInterceptors = []
    ) : AccessInterceptorValueHolderInterface {
        $proxyClassName = $this->generateProxy(get_class($instance));

        return $proxyClassName::staticProxyConstructor($instance, $prefixInterceptors, $suffixInterceptors);
    }

    /**
     * {@inheritDoc}
     */
    protected function getGenerator() : ProxyGeneratorInterface
    {
        return $this->generator ?: $this->generator = new AccessInterceptorValueHolderGenerator();
    }
}
