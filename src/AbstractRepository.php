<?php

namespace Jenhacool\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Jenhacool\Repository\Contracts\CriteriaContract;
use Closure;

abstract class AbstractRepository
{
    protected $query;

    protected $modelInstance;

    protected $specs;

    protected $scopes = [];

    public function __construct()
    {
        $this->makeModel();

        $this->specs = new Collection();
    }

    public function getModel()
    {
        return $this->model;
    }

    protected function makeModel()
    {
        if(empty($this->model)) {
            throw new RepositoryExceptions('The model class must be set');
        }

        return $this->modelInstance = new $this->model;
    }

    protected function getQuery()
    {
        $this->query = $this->getNewInstance()->newQuery();

        $this->applyScopes();

        return $this->query;
    }

    protected function getNewInstance(array $data = [])
    {
        return $this->modelInstance->newInstance($data);
    }

    /**
     * Get all data with pagination
     *
     * @param array $columns
     * @return mixed
     */
    public function all($columns = ['*'])
    {
        return $this->getQuery()->get($columns);
    }

    /**
     * Get all data with pagination
     *
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 15, $columns = ['*'])
    {
        return $this->getQuery()->paginate($perPage, $columns);
    }

    /**
     * Find data by id
     *
     * @param int $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = ['*'])
    {
        return $this->getQuery()->find($id, $columns);
    }

    /**
     * Find data by field and value
     *
     * @param $field
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findByField($field, $value, $columns = ['*'])
    {
        return $this->getQuery()->where($field, '=', $value)->get($columns);
    }

    /**
     * Find data by multiple fields
     *
     * @param array $where
     * @param array $columns
     * @return mixed
     */
    public function findWhere(array $where, $columns = ['*'])
    {
        $this->getQuery();

        $this->applyConditions($where);

        return $this->query->get($columns);
    }

    /**
     * Find data by field and value contained within the given array
     *
     * @param $field
     * @param array $values
     * @param array $columns
     * @return mixed
     */
    public function findWhereIn($field, array $values, $columns = ['*'])
    {
        return $this->getQuery()->whereIn($field, $values)->get($columns);
    }

    /**
     * Create new data
     *
     * @param array $data
     * @return bool|mixed
     */
    public function create(array $data)
    {
        $model = $this->getNewInstance($data);

        if($model->save()) {
            return $model;
        }

        return false;
    }

    /**
     * Update data
     *
     * @param $id
     * @param array $data
     * @return bool|mixed
     */
    public function update($id, array $data)
    {
        $model = $this->find($id);
        $model->fill($data);

        if($model->save()) {
            return $model;
        }

        return false;
    }

    /**
     * Delete data
     *
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $model = $this->find($id);

        return $model->delete();
    }

    /**
     * Delete entry by conditions
     *
     * @param array $where
     * @return bool
     */
    public function deleteWhere(array $where)
    {
        $this->getQuery();

        $this->applyConditions($where);

        $delete = $this->query->delete();

        return $delete ? true : false;
    }

    /**
     *  Sort data
     *
     *  @param $column
     *  @param $direction
     */
    public function orderBy($column, $direction = 'asc')
    {
        return $this->addScope(function($query) use ($column, $direction) {
           $direction = (in_array($direction, ['desc', 'asc'])) ? $direction : 'asc';

           return $query->orderBy($this->appendTableName($column), $direction);
        });
    }

    protected function appendTableName($column)
    {
        if(strpos($column, '.') == false) {
            return $this->modelInstance->getTable() . '.' . $column;
        }

        return $column;
    }

    protected function addScope(Closure $scope)
    {
        $this->scopes[] = $scope;

        return $this;
    }

    protected function applyScopes()
    {
        foreach($this->scopes as $callback) {
            if(is_callable($callback)) {
                $this->query = $callback($this->query);
            }
        }

        $this->scopes = [];

        return $this;
    }

    /**
     * Apply conditions
     *
     * @param array $where
     */
    protected function applyConditions(array $where)
    {
        foreach($where as $field => $value) {
            if(is_array($value)) {
                list($field, $condition, $val) = $value;
                $this->query = $this->query->where($field, $condition, $val);
            } else {
                $this->query = $this->query->where($field, '=', $value);
            }
        }
    }

    public function __call($method, $parameters)
    {
        if(method_exists($this, $scope = 'scope' . ucfirst($method))) {
            return call_user_func_array([$this, $scope], $parameters);
        }

        $className = get_class($this);

        throw new \BadMethodCallException("Call to undefined method {$className}::{$method}()");
    }
}