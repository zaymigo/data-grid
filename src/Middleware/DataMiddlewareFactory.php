<?php
/**
 * @author: Deller <r.malashin@zaymigo.com>
 * @copyright Copyright (c) 2017, Zaymigo
 */

namespace Nnx\DataGrid\Middleware;


use Interop\Container\ContainerInterface;
use Interop\Container\Exception\ContainerException;
use Nnx\DataGrid\GridPluginManager;
use Zend\Diactoros\Response\JsonResponse;
use Zend\Http\PhpEnvironment\Request;
use Zend\ServiceManager\Exception\ServiceNotCreatedException;
use Zend\ServiceManager\Exception\ServiceNotFoundException;

class DataMiddlewareFactory implements \Zend\ServiceManager\Factory\FactoryInterface
{
    /**
     * Create an object
     *
     * @param  ContainerInterface $container
     * @param  string $requestedName
     * @param  null|array $options
     * @return object
     * @throws ServiceNotFoundException if unable to resolve the service.
     * @throws ServiceNotCreatedException if an exception is raised when
     *     creating a service.
     * @throws ContainerException if any other error occurs
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        /** @var Request $req */
        $req = $container->get('Request');
        $gridName = $req->getQuery('grid', null);
        if (!$gridName) {
            throw new \RuntimeException('Not setted grid name (GET arg grid)');
        }
        /** @var GridPluginManager $gridManager */
        $gridManager = $container->get('GridManager');
        $grid = $gridManager->get('grids.' . $gridName);
        return new DataMiddleware($grid);
    }

}