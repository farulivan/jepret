<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;

/**
 * Abstract class BaseRepository
 *
 * Implements the basic functionalities as defined in the BaseRepositoryInterface.
 */
abstract class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Create a new record in the database.
     *
     * @param array $data The data to use for creating a new record.
     * @return Model Returns an instance of the model that was created.
     */
    public function create(array $data): Model
    {
        return $this->model->create($data);
    }
}
