<?php

namespace MicroService\Src\Repository;

use MicroService\Src\Interfaces\interfaceRepository;

abstract class AbstractEloquentRepository implements interfaceRepository
{
    /**
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $_model;
    protected $pagination;

    /**
     * AbstractEloquentRepository constructor.
     */
    public function __construct()
    {
        $this->setModel();
    }

    /**
     * get model
     * @return string
     */
    abstract public function getModel();

    /**
     * Set model
     */
    public function setModel()
    {
        $this->_model = app()->make(
            $this->getModel()
        );
    }

    /**
     * Get All
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function getAll($columns = [], $offset = 0, $limit = 10)
    {
        $this->pagination['total_page'] = (int)ceil($this->count()/$limit);
        $this->pagination['curent_page'] = (int)round($offset/$limit) + 1;
        $this->pagination['list'] = $this->pagination($offset, $limit);

        return $this->pagination;
    }

    /**
     * Get one
     * @param $id
     * @return mixed
     */
    public function find($id, $columns = '*')
    {
        $result = $this->_model->find($id, $columns);

        return $result;
    }

    /**
     * Create
     * @param array $attributes
     * @return mixed
     */
    public function create(array $attributes)
    {
        return $this->_model->create($attributes);
    }

    /**
     * Update
     * @param $id
     * @param array $attributes
     * @return bool|mixed
     */
    public function update($id, array $attributes, $columns = "*")
    {
        $result = $this->_model->find($id, $columns);
        if ($result) {
            $result->update($attributes);
            return $result;
        }

        return false;
    }

    public function pagination($offset = 0, $limit = 10)
    {
        return $this->_model->offset($offset)->limit($limit)->get();
    }

    /**
     * Delete
     *
     * @param $id
     * @return bool
     */
    public function delete($id)
    {
        $result = $this->_model->find($id);
        if ($result) {
            $result->delete();

            return true;
        }

        return false;
    }

    /**
     * Count model
     *
     * @param $id
     * @return bool
     */
    public function count()
    {
        return $this->_model->count();
    }
}