<?php

namespace Sinofaneliu\LaravelStart;

use Illuminate\Support\Str;

abstract class Filter
{
    protected $request;
    protected $model;
    protected $perPage;
    protected $query;
    
    public function __construct(){}

    public function apply()
    {
        foreach ($this->filters() as $name => $value) {
            switch ($name) {
                case '_perPage':
                    $this->perPage = $value;
                    continue;
                break;

                case '_sorter':
                    $this->setSorter($value);
                    continue;
                break;

                case '_fields':
                    $this->setFields($value);
                    continue;
                break;

                case '_relations':
                    $this->setRelations($value);
                    continue;
                break;
                
                default:
                    $method = Str::camel($name);

                    if (method_exists($this, $method)) {
                        call_user_func_array([$this, $method], array_filter([$value]));
                    }
            }
        }
        return $this->getResults();
    }
    
    public function filters()
    {
        return $this->request->validatedWithDefaults();
    }

    public function allowedFields()
    {
        return [];
    }

    public function setSorter($sorter)
    {
        list($field, $order) = explode('-', $sorter);
        if (!$field || !$order) {
            return;
        }
        $order = Str::startsWith($order, 'asc') ? 'asc' : 'desc';
        $this->query->orderBy($field, $order);
    }

    public function setFields($fields)
    {
        $model_fields = $this->allowedFields() 
                      ?: array_keys($this->model->casts);

        $fields = array_filter(
            $fields, 
            function ($field) use ($model_fields) {
                return in_array($field, $model_fields);
            }
        );

        $this->query->select($fields);
    }

    public function setRelations($relations)
    {
        $relations = explode(',', $relations);
        
        foreach ($relations as $relation) {
            $res = explode('.', $relation);
            $method = $res[0];
            if (method_exists($this, $method)) {
                call_user_func_array([$this, $method], array_filter([$res]));
            }
        }
    }

    public function getResults()
    {
        return $this->perPage
            ? $this->query->paginate($this->perPage)
            : $this->query->get();
    }
}