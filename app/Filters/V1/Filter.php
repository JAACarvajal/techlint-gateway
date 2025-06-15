<?php

namespace App\Filters\V1;

use Illuminate\Database\Eloquent\Builder;

abstract class Filter
{
    /**
     * Eloquent query builder instance
     * @var Builder
     */
    protected $builder;

    /**
     * Filters to apply
     * @var array
     */
    protected $filters;

    /**
     * Sortable fields for the query
     * @var array
     */
    protected $sortables = [];

    /**
     * Create filter instance
     *
     * @param array $filters Filters to apply
     */
    public function __construct(array $filters = [])
    {
        $this->filters = $filters;
    }

    /**
     * Apply the filters/sorts/includes to the Eloquent query builder
     *
     * @param Builder $builder Builder instance to apply filters to
     */
    public function apply(Builder $builder): Builder
    {
        $this->builder = $builder;

        foreach ($this->filters as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    /**
     * Apply the filters to the Eloquent query builder
     *
     * @param array $filters Filters to apply
     */
    protected function filter($filters): Builder
    {
        foreach ($filters as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this->builder;
    }

    /**
     * Apply sorting to the Eloquent query builder
     *
     * @param string $sorts Sorts to apply
     */
    protected function sort($sorts): Builder
    {
        $sortAttributes = explode(',', $sorts);

        foreach ($sortAttributes as $attribute) {
            $direction = 'asc';

            if (str_starts_with($attribute, '-')) {
                $direction = 'desc';
                $attribute = ltrim($attribute, '-');
            }

            if (
                array_key_exists($attribute, $this->sortables) === false &&
                in_array($attribute, $this->sortables) === false
            ) {
                continue;
            }

            $columnName = $this->sortables[$attribute] ?? null;

            if (is_null($columnName)) {

                $columnName = $attribute;
            }

            $this->builder->orderBy($columnName, $direction);
        }

        return $this->builder;
    }
}
