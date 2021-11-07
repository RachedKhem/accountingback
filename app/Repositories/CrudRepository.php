<?php


namespace App\Repositories;


use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

/**
 * Class CrudRepository
 *
 * Abstract class to be extended by every model repository
 *
 * Contains CRUD methods
 * @package App\Repositories
 */
abstract class CrudRepository
{
    /**
     * Model to access database
     * @var Model
     */
    protected $model;


    protected $searchFields;

    /**
     * CrudRepository constructor.
     * @param Model $model
     * @param array $searchFields
     */
    public function __construct(Model $model, array $searchFields = [])
    {
        $this->model = $model;
        $this->searchFields = $searchFields;
    }

    /**
     * Get all records of model
     *
     * @param array $attributes specifies the attributes to get
     * @param array $conditions specifies the conditions to filter the data
     * @param array $relations specifies the relations to get with each record
     * @param array $orderBy specifies how to order data
     * @param int|null $offset
     * @param int|null $limit
     * @param array $nullConditions nullable fields to filter the data
     * @return mixed
     * @throws Exception
     */
    public function all(array $attributes = array('*'), array $conditions = [], array $relations = [], array $orderBy = [], int $offset = -1, int $limit = -1, array $nullConditions = [], array $hasNotRelations = [])
    {
        return $this->buildQuery($attributes, $conditions, $relations, $orderBy, $offset, $limit, $nullConditions, $hasNotRelations)->get();
    }

    /**
     * Get one model of model where $pks
     *
     * @param array $pks
     * @param array $attributes specifies the attributes to get
     * @param array $conditions specifies the conditions to filter the data
     * @param array $relations specifies the relations to get with each record
     * @param array $orderBy specifies how to order data
     * @param array $nullConditions nullable fields to filter the data
     * @return mixed
     * @throws Exception
     */
    public function show(array $pks, array $attributes = ['*'], array $conditions = [], array $relations = [], array $orderBy = [], array $nullConditions = [], array $hasNotRelations = [])
    {
        $this->is_assoc($pks);
        $conditions = array_merge($conditions, $pks);
        try {
            return $this->buildQuery($attributes, $conditions, $relations, $orderBy, -1, -1, $nullConditions, $hasNotRelations)->firstOrFail();
        } catch (Exception $exception) {
            Log::error($exception);
            return $exception;
        }
    }

    /**
     * Store a record in database
     *
     * @param array $data
     * @return \Illuminate\Config\Repository|mixed
     */
    public function store(array $data)
    {
        try {
            return $this->model->create($data);
        } catch (Exception $exception) {
            Log::error($exception);
            return $exception;
        }

    }

    /**
     * Update a record where $pks
     *
     * @param array $pks
     * @param array $data
     * @return Exception|mixed
     */
    public function update(array $pks, array $data)
    {
        try {
            if (($model = $this->show($pks)) instanceof Exception) {
                Log::error($model);
                throw $model;
            }
            $model->update($data);
            return $model;
        } catch (Exception $exception) {
            Log::error($exception);
            return $exception;
        }
    }

    /**
     * Delete record of model where $pks
     *
     * @param array $pks
     * @return Exception
     */
    public function destroy(array $pks)
    {
        try {
            return $this->show($pks)->delete();
        } catch (Exception $exception) {
            Log::error($exception);
            return $exception;
        }
    }


    /**
     * Count number of records in database where $conditions, $nullConditions and $search
     *
     * @param array $conditions specifies the conditions to filter the data
     * @param array $nullConditions nullable fields to filter the data
     * @param array $search array of attributes to search, must be associative
     * @return mixed
     * @throws Exception
     */
    public function count(array $conditions = [], array $nullConditions = [], array $search = [])
    {
        $query = $this->model->selectRaw('count(*) as count');
        if (count($conditions) > 0) {
            $this->is_assoc($conditions);
            foreach ($conditions as $column => $condition) {
                $query = $query->where($column, $condition);
            }
        }
        if (count($nullConditions) > 0) {
            $this->is_assoc($nullConditions);
            foreach ($nullConditions as $column => $condition) {
                if ($condition)
                    $query = $query->whereNull($column);
                else
                    $query = $query->whereNotNull($column);
            }
        }
        if (count($search) > 0) {
            $this->is_assoc($search);
            $query = $this->buildSearchQuery($query, $search);
        }
        return $query->first()->count;
    }


    /**
     * @param array $search array of attributes to search, must be associative
     * @param array $attributes specifies the attributes to get
     * @param array $conditions specifies the conditions to filter the data
     * @param array $relations specifies the relations to get with each record
     * @param array $orderBy specifies how to order data
     * @param int $offset specifies the starting of set for a query
     * @param int $limit specifies the number of record to get per query
     * @param array $nullConditions nullable fields to filter the data
     * @return array
     * @throws Exception
     */
    public function search(array $search, array $attributes = array('*'), array $conditions = [], array $relations = [], array $orderBy = [], int $offset = -1, int $limit = -1, array $nullConditions = [], array $hasNotRelations = [])
    {
        $this->is_assoc($search);

        if (count($search) > 0) {
            $q = $this->buildQuery($attributes, $conditions, $relations, $orderBy, $offset, $limit, $nullConditions, $hasNotRelations);
            $q = $this->buildSearchQuery($q, $search);
            return $q->get();
        }
        return [];
    }

    public function deleteFile($dir)
    {
        if (unlink($dir))
            return true;
        return false;
    }

    public function moveFile($dir, $file)
    {
        if ($file) {
            if ($file->isValid()) {
                $path = 'uploads/' . $dir;
                $extension = $file->getClientOriginalExtension();
                do {
                    $fileName = $this->generateRandomString(10) . '.' . $extension;
                } while (file_exists($path . '/' . $fileName));
                if ($file->move($path, $fileName))
                    return $path . '/' . $fileName;
            }
        }
        return new Exception('error moving file');
    }


    private function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    /**
     * Check if an array is associative or not
     *
     * ['key1' => 'value1', 'key2' => 'value2'] is an example of associative array
     * [0 => 'value0', 1 => 'value1'] is an example of non-associative array
     *
     * @param array $array
     * @return void
     * @throws Exception throws exception when array is not associative
     */
    private function is_assoc(array $array)
    {
        if (array_keys($array) === range(0, count($array) - 1)) {
            throw(new Exception('Array must be associative'));
        }
    }

    /**
     * Build query to select $attributes where $conditions and $nullConditions with $relations, ordering by $orderBy, starting from $offset limiting $limit
     *
     * Select $attributes
     * Set $conditions
     * Set $orderBy
     * Set $nullConditions
     * Set $relations
     * Set $offset
     * Set $limit
     *
     * @param array $attributes specifies the attributes to get
     * @param array $conditions specifies the conditions to filter the data, must be associative
     * @param array $relations specifies the relations to get with each record
     * @param array $orderBy specifies how to order data, must be associative
     * @param int $offset specifies the starting of set for a query
     * @param int $limit specifies the number of record to get per query
     * @param array $nullConditions nullable fields to filter the data, must be associative
     * @return mixed
     * @throws Exception
     */
    protected function buildQuery(array $attributes, array $conditions, array $relations, array $orderBy, int $offset, int $limit, array $nullConditions, array $hasNotRelations)
    {
        $query = $this->select($attributes);

        $query = $this->setConditionsToQuery($query, $conditions);

        $query = $this->setOrderByToQuery($query, $orderBy);

        $query = $this->setNullConditionsToQuery($query, $nullConditions);

        $query = $this->setRelationsToQuery($query, $relations);

        $query = $this->setOffsetToQuery($query, $offset);

        $query = $this->setLimitToQuery($query, $limit);

        $query = $this->setHasNotRelations($query, $hasNotRelations);

        return $query;
    }


    /**
     * Prepare 'date' field for date type
     * @param array $data
     * @return array
     */
    protected function prepareDate(array $data)
    {
        if (isset($data['date'])) {
            $data['date'] = explode('T', $data['date'])[0];
        }
        return $data;
    }


    /**
     * Select attributes from model
     *
     * @param array $attributes
     * @return mixed
     */
    private function select(array $attributes)
    {
        return $this->model->select($attributes);
    }

    /**
     * set conditions to query
     *
     * Check if there is a condition in $conditions (must be associative), if yes, loop over the array to set where clauses
     *
     * @param Builder $query
     * @param array $conditions
     * @return Builder
     * @throws Exception
     */
    private function setConditionsToQuery(Builder $query, array $conditions)
    {
        if (count($conditions) > 0) {

            $this->is_assoc($conditions);
            foreach ($conditions as $key => $condition) {
                $query = $query->where($key, $condition);
            }
        }

        return $query;
    }

    /**
     * Set orderBy to query
     *
     * Check if there is an attribute in $orderBy (must be associative), if yes, loop over the array to set orderBy clauses
     *
     * @param Builder $query
     * @param array $orderBy
     * @return Builder|\Illuminate\Database\Query\Builder
     * @throws Exception
     */
    private function setOrderByToQuery(Builder $query, array $orderBy)
    {
        if (count($orderBy) > 0) {

            $this->is_assoc($orderBy);
            foreach ($orderBy as $column => $order) {
                /*
                 * For each row check is $column contains @
                 * If yes, it means ordering by on relation
                 * After explode, if array length>1 we order on relation with whereHas
                 * Otherwise we use orderBy on query
                 */
                $columnRelations = explode('@', $column);
                if (count($columnRelations) == 1) {
                    $queryString = '`' . $column . '` ' . strtoupper($order);

                    $query = $query->orderByRaw($queryString);
                } else {
                    $column = $columnRelations[count($columnRelations) - 1];
                    unset($columnRelations[count($columnRelations) - 1]);
                    $relation = join('.', $columnRelations);
                    $query = $query->whereHas($relation, function ($q) use ($column, $order, $columnRelations) {
                        $queryString = '-`' . $column . '` ' . strtoupper($order);
                        $q->orderByRaw($queryString);
                    });
                    if ($order == 'desc') {
                        $relation = $columnRelations[0];
                        $query = $query->union($this->model->whereNull($this->model->$relation()->getForeignKeyName()));
                    }
                    if ($order == 'asc') {
                        $relation = $columnRelations[0];
                        $query = $this->model->whereNull($this->model->$relation()->getForeignKeyName())->union($query);
                    }

                }
            }
        }
        return $query;
    }

    /**
     * Set null conditions to query
     *
     * Check if there is a nullable field in $nullConditions (must be associative), if yes, loop over the array to set whereNull clauses
     *
     * @param Builder $query
     * @param array $nullConditions
     * @return Builder|\Illuminate\Database\Query\Builder
     * @throws Exception
     */
    private function setNullConditionsToQuery(Builder $query, array $nullConditions)
    {
        if (count($nullConditions) > 0) {

            $this->is_assoc($nullConditions);
            foreach ($nullConditions as $attribute => $nullable) {
                if ($nullable) {
                    $query = $query->whereNull($attribute);
                } else {
                    $query = $query->whereNotNull($attribute);
                }
            }
        }

        return $query;
    }

    /**
     * Set relations to query
     *
     * Check if there is a relation in $relations (must be non-associative), if yes, set with attribute
     *
     * @param Builder $query
     * @param array $relations
     * @return Builder
     */
    private function setRelationsToQuery(Builder $query, array $relations)
    {
        if (count($relations) > 0) {

            $query = $query->with($relations);
        }
        return $query;
    }

    /**
     * Set hasNotRelations to query
     *
     * Check if there is a relation in $relations (must be non-associative), if yes, set with attribute
     *
     * @param Builder $query
     * @param array $hasNotRelations
     * @return Builder
     */
    private function setHasNotRelations(Builder $query, array $hasNotRelations)
    {
        if (count($hasNotRelations) > 0) {
            foreach ($hasNotRelations as $relation) {
                $query = $query->doesntHave($relation);
                Log::info($relation);
            }
        }
        return $query;
    }

    /**
     * Set offset to query
     *
     * Check if $offset is greater than -1 (which means is set), if yes, set query offset
     *
     * @param Builder $query
     * @param int|null $offset
     * @return Builder
     */
    private function setOffsetToQuery(Builder $query, $offset)
    {
        if ($offset >= 0)
            $query = $query->offset($offset);
        return $query;
    }

    /**
     * Set limit to query
     *
     * Check if $limit is greater than -1 (which means is set), if yes, set query limit
     *
     * @param Builder $query
     * @param $limit
     * @return Builder
     */
    private function setLimitToQuery(Builder $query, $limit)
    {
        if ($limit >= 0)
            $query = $query->limit($limit);
        return $query;
    }

    /**
     * Build search query
     *
     * @param Builder $query
     * @param array $search array of attributes to search, must be associative
     * @return mixed
     */
    private function buildSearchQuery(Builder $query, array $search = [])
    {
        for ($i = 0; $i < count($search); $i++) {
            $attributes = array_keys($search)[$i];
            $value = $search[$attributes];
            $fields = explode('@', $attributes);
            if (count($fields) == 1) {
                $query = $query->where($attributes, 'like', '%' . $value . '%');
            } else {
                $column = $fields[count($fields) - 1];
                unset($fields[count($fields) - 1]);
                $relation = join('.', $fields);
                $query = $query->whereHas($relation, function ($q) use ($value, $column) {
                    $q->where($column, 'like', '%' . $value . '%');
                });
            }
        }

        return $query;
    }

}
