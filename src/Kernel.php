<?php

declare(strict_types=1);

namespace App;

use App\Admin\Admin;
use App\Admin\Registry\AdminInterface;
use function class_exists;
use function dirname;
use function interface_exists;
use function is_a;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\Config\Resource\FileResource;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

class Kernel extends BaseKernel implements CompilerPassInterface
{
    use MicroKernelTrait;

    private const CONFIG_EXTS = '.{php,xml,yaml,yml}';

    public function registerBundles(): iterable
    {
        $contents = require $this->getProjectDir().'/config/bundles.php';
        foreach ($contents as $class => $envs) {
            if ($envs[$this->environment] ?? $envs['all'] ?? false) {
                yield new $class();
            }
        }
    }

    public function getProjectDir(): string
    {
        return dirname(__DIR__);
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $container->addResource(new FileResource($this->getProjectDir().'/config/bundles.php'));
        $container->setParameter('container.dumper.inline_class_loader', true);
        $confDir = $this->getProjectDir().'/config';

        $loader->load($confDir.'/{packages}/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{packages}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{services}'.self::CONFIG_EXTS, 'glob');
        $loader->load($confDir.'/{services}_'.$this->environment.self::CONFIG_EXTS, 'glob');
    }

    protected function configureRoutes(RouteCollectionBuilder $routes): void
    {
        $confDir = $this->getProjectDir().'/config';

        $routes->import($confDir.'/{routes}/'.$this->environment.'/**/*'.self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir.'/{routes}/*'.self::CONFIG_EXTS, '/', 'glob');
        $routes->import($confDir.'/{routes}'.self::CONFIG_EXTS, '/', 'glob');
    }

    public function process(ContainerBuilder $container): void
    {
        $set = [
            AdminInterface::class => Admin::class,
        ];

        $definitions = $container->getDefinitions();

        foreach ($set as $interface => $serviceName) {
            $tagged = $this->findServiceByInterface($interface, $definitions);
            $target = $container->getDefinition($serviceName);
            $target->setArgument(0, $tagged);
        }
    }

    /** @param Definition[] $definitions */
    private function findServiceByInterface(string $interface, array $definitions)
    {
        $tagged = [];
        foreach ($definitions as $name => $definition) {
            $class = $definition->getClass() ?: $name;
            if (is_string($class) && interface_exists($interface, false) && class_exists($class, false) && is_a($class, $interface, true)) {
                $tagged[$name] = $definition;
            }
        }

        return $tagged;
    }
}
