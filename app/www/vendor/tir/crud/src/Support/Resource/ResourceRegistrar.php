<?php


namespace Tir\Crud\Support\Resource;
use Illuminate\Routing\ResourceRegistrar as OriginalRegistrar;


class ResourceRegistrar extends OriginalRegistrar
{
    // add data to the array
    /**
     * The default actions for a resourceful controller.
     *
     * @var array
     */
    protected $resourceDefaults =['index', 'create', 'store', 'edit', 'update', 'destroy',
                                 'trash','data','select','trashData', 'restore' ,'fullDestroy' ,'action','reorder', 'show'];



    /**
     * Add the data method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceTrash($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/trash';

        $action = $this->getResourceAction($name, $controller, 'trash', $options);

        return $this->router->get($uri, $action);
    }





    /**
     * Add the data method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceData($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/data';

        $action = $this->getResourceAction($name, $controller, 'data', $options);

        return $this->router->get($uri, $action);
    }



    /**
     * Add the data method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceSelect($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/select';

        $action = $this->getResourceAction($name, $controller, 'select', $options);

        return $this->router->get($uri, $action);
    }



    /**
     * Add the data method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceTrashData($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/trashData';

        $action = $this->getResourceAction($name, $controller, 'trashData', $options);

        return $this->router->get($uri, $action);
    }



    /**
     * Add the data method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceRestore($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) .'/{'.$base.'}/'. 'restore';
        $action = $this->getResourceAction($name, $controller, 'restore', $options);

        return $this->router->get($uri, $action);
    }

    /**
     * Add the data method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
     protected function addResourceFullDestroy($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) .'/{'.$base.'}/'. 'fullDestroy';

        $action = $this->getResourceAction($name, $controller, 'fullDestroy', $options);

        return $this->router->delete($uri, $action);
    }

    /**
     * Add the data method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceAction($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/action';

        $action = $this->getResourceAction($name, $controller, 'action', $options);

        return $this->router->post($uri, $action);
    }

    /**
     * Add the data method for a resourceful route.
     *
     * @param  string  $name
     * @param  string  $base
     * @param  string  $controller
     * @param  array   $options
     * @return \Illuminate\Routing\Route
     */
    protected function addResourceReorder($name, $base, $controller, $options)
    {
        $uri = $this->getResourceUri($name) . '/reorder';

        $action = $this->getResourceAction($name, $controller, 'reorder', $options);

        return $this->router->post($uri, $action);
    }
}
