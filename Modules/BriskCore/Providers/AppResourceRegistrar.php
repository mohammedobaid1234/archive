<?php

namespace Modules\BriskCore\Providers;

use Illuminate\Routing\ResourceRegistrar;
use Illuminate\Support\Str;

class AppResourceRegistrar extends ResourceRegistrar
{
    // add data to the array
    /**
     * The default actions for a resourceful controller.
     *
     * @var array
     */
    protected $resourceDefaults = ['index', 'create', 'store', 'show', 'edit', 'update', 'destroy', 'datatable', 'list'];


    public function register($name, $controller, array $options = [])
    {
        if (isset($options['parameters']) && !isset($this->parameters)) {
            $this->parameters = $options['parameters'];
        }

        // If the resource name contains a slash, we will assume the developer wishes to
        // register these resource routes with a prefix so we will set that up out of
        // the box so they don't have to mess with it. Otherwise, we will continue.
        if (Str::contains($name, '/')) {
            $this->prefixedResource($name, $controller, $options);

            return;
        }

        // We need to extract the base resource from the resource name. Nested resources
        // are supported in the framework, but we need to know what name to use for a
        // place-holder on the route parameters, which should be the base resources.
        $base = $this->getResourceWildcard(last(explode('.', $name)));
        $defaults = $this->resourceDefaults;
        foreach ($this->getResourceMethods($defaults, $options) as $m) {
            $this->addWheres(
                $this->{'addResource' . ucfirst($m)}($name, $base, $controller, $options),
                $options
            );
        }
    }

    /**
     * Add any parameter patterns to the route.
     *
     * @param  \Illuminate\Routing\Route $route
     * @param  array $options
     * @return \Illuminate\Routing\Route
     */
    protected function addWheres($route, array $options = [])
    {
        if (empty($options['where'])) {
            return $route;
        }
        if (isset($this->wheres)) {
            $wheres = $this->wheres;
        } else {
            $wheres = $options['where'];
            $keys = array_map(function ($item) {
                return $this->getResourceWildcard($item);
            }, array_keys($wheres));
            $this->wheres = $wheres = array_combine($keys, $wheres);
        }
        return $route->where($wheres);
    }

    protected function addResourceCreate($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/create';

        $action = $this->getResourceAction($name, $controller, 'create', $options);

        return $this->router->get($uri, $action);
    }

    protected function addResourceShow($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/{id}';

        $action = $this->getResourceAction($name, $controller, 'show', $options);

        return $this->router->get($uri, $action);
    }

    protected function addResourceUpdate($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/{id}';

        $action = $this->getResourceAction($name, $controller, 'update', $options);

        return $this->router->post($uri, $action);
    }

    protected function addResourceDestroy($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/{id}';

        $action = $this->getResourceAction($name, $controller, 'destroy', $options);

        return $this->router->delete($uri, $action);
    }

    protected function addResourceDatatable($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/datatable';

        $action = $this->getResourceAction($name, $controller, 'datatable', $options);

        return $this->router->get($uri, $action);
    }

    protected function addResourceList($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/list';

        $action = $this->getResourceAction($name, $controller, 'list', $options);

        return $this->router->get($uri, $action);
    }
}