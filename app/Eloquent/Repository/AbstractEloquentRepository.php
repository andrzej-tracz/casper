<?php

namespace App\Eloquent\Repository;

abstract class AbstractEloquentRepository
{
    /**
     * Returns the Model class of repository
     *
     * @return string
     */
    abstract protected function getModelClass();

    /**
     * Handles dynamic calls - delegates to model
     *
     * @param $method
     * @param $parameters
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        $class = $this->getModelClass();
        $model = new $class;

        return $model->{$method}(...$parameters);
    }
}
