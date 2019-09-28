<?php

namespace Jenhacool\Repository\Contracts;

interface RepositoryContract
{
    /**
     * Get all data with pagination
     *
     * @param array $columns
     * @return mixed
     */
    public function all($columns = ['*']);

    /**
     * Get all data with pagination
     *
     * @param int $perPage
     * @param array $columns
     * @return mixed
     */
    public function paginate($perPage = 15, $columns = ['*']);

    /**
     * Find data by id
     *
     * @param int $id
     * @param array $columns
     * @return mixed
     */
    public function find($id, $columns = ['*']);

    /**
     * Find data by field and value
     *
     * @param $field
     * @param $value
     * @param array $columns
     * @return mixed
     */
    public function findByField($field, $value, $columns = ['*']);

    /**
     * Find data by multiple fields
     *
     * @param array $where
     * @param array $columns
     * @return mixed
     */
    public function findWhere(array $where, $columns = ['*']);

    /**
     * Find data by field and value contained within the given array
     *
     * @param $field
     * @param array $values
     * @param array $columns
     * @return mixed
     */
    public function findWhereIn($field, array $values, $columns = ['*']);

    /**
     * Create new data
     *
     * @param array $data
     * @return bool|mixed
     */
    public function create(array $data);

    /**
     * Update data
     *
     * @param $id
     * @param array $data
     * @return bool|mixed
     */
    public function update($id, array $data);

    /**
     * Delete data
     *
     * @param $id
     * @return bool
     */
    public function delete($id);

    /**
     * Delete entry by conditions
     *
     * @param array $where
     * @return bool
     */
    public function deleteWhere(array $where);

    /**
     *  Sort data
     *
     *  @param $column
     *  @param $direction
     */
    public function orderBy($column, $direction = 'asc');
}