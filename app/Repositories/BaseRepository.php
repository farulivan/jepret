<?php

namespace App\Repositories;

/**
 * Abstract class BaseRepository
 *
 * Implements the basic functionalities as defined in the BaseRepositoryInterface.
 */
abstract class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    /**
     * Create a new record in the database.
     *
     * @param array $data The data to use for creating a new record.
     * @return object Returns an instance of the model that was created.
     */
    public function create(array $data): object
    {
        return $this->model->create($data);
    }
}
