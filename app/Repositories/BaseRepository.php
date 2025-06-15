<?php

namespace App\Repositories;

use App\Filters\V1\Filter;
use Illuminate\Database\Eloquent\{Collection, Model};
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository
{
    /**
     * Model instance
     * @var Model
     */
    protected Model $model;

    /**
     * Get all records from the model
     */
    public function all(): Collection
    {
        return $this->model->all();
    }

    /**
     * Paginate records with filters
     *
     * @param Filter $filters Filters instance
     * @param int $perPage Number of records per page
     */
    public function paginate(Filter $filters, int $perPage = 10): LengthAwarePaginator
    {
        return $this->model
                    ->filter($filters)
                    ->paginate($perPage);
    }

    /**
     * Check if a record exists based on attributes
     *
     * @param array $attributes Attributes to check
     */
    public function exists(array $attributes): bool
    {
        return $this->model->where($attributes)->exists();
    }

    /**
     * Find a record by its ID
     *
     * @param int $id ID of the record
     */
    public function find(int $id): Model|null
    {
        return $this->model->find($id);
    }

    /**
     * Find a record by attributes
     *
     * @param array $attributes Attributes to search for
     */
    public function findBy(array $attributes): Model|null
    {
        $query = $this->model->where($attributes);

        if ($query->exists() === true) {
            return $query->first();
        }

        return null;
    }

    /**
     * Find a record by its ID or throw an exception if not found
     *
     * @param int $id ID of the record
     */
    public function findOrFail(int $id): Model
    {
        return $this->model->findOrFail($id);
    }

    /**
     * Create a new record
     *
     * @param array $attributes Attributes to create the record
     */
    public function create(array $attributes): Model
    {
        return $this->model->create($attributes);
    }

    /**
     * Update an existing record
     *
     * @param int $id ID of the record to update
     * @param array $attributes Attributes to update the record
     */
    public function update(int $id, array $attributes): Model
    {
        $record = $this->findOrFail($id);

        $record->update($attributes);

        return $record;
    }

    /**
     * Delete a record by its ID
     *
     * @param int $id ID of the record to delete
     */
    public function delete(int $id): bool
    {
        $record = $this->findOrFail($id);

        return $record ? $record->delete() : false;
    }

    /**
     * Update or create a record based on conditions
     *
     * @param array $where Conditions to find the record
     * @param array $data Data to update or create the record
     */
    public function updateOrCreate(array $where, array $data): Model
    {
        return $this->model->updateOrCreate($where, $data);
    }

    /**
     * Find or create a record based on attributes
     *
     * @param array $attributes Attributes to find or create the record
     */
    public function firstOrCreate(array $attributes): Model
    {
        return $this->model->firstOrCreate($attributes);
    }
}
