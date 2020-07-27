<?php

namespace Sinofaneliu\LaravelStart\Traits;

trait AnalysisRouteAction
{
    public function getCurrentControllerName()
    {
        $class= $this->getCurrentAction()['controller'];

        return substr(strrchr($class,'\\'),1);
    }

    public function getCurrentMethodName()
    {
        return $this->getCurrentAction()['method'];
    }


    public function getCurrentAction()
    {
        $action = $this->route()->getActionName();
        list($class, $method) = explode('@', $action);

        return ['controller' => $class, 'method' => $method];
    }
}
