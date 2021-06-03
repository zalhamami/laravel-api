<?php

namespace App\Repositories;

use App\Traits\ApiRequester;
use Illuminate\Database\Eloquent\Model;

class Repository
{
    use ApiRequester;

    /**
     * @var Model
     */
    protected $model;

    public function getAll($query = NULL)
    {
        if (!$this->model) {
            return;
        }

        $request = $this->getRequest();
        $collection = $this->model;
        if ($query) {
            $collection = $query;
        }
        if ($request['sort_by']) {
            if ($request['sort_direction'] == 'desc') {
                $collection = $collection->orderByDesc($request['sort_by']);
            } else {
                $collection = $collection->orderBy($request['sort_by']);
            }
        }
        $data = $collection->paginate($request['per_page']);

        return $data;
    }

    public function getAllWithDetails()
    {
        $query = $this->model->details();
        return $this->getAll($query);
    }

    public function getById($id)
    {
        if (!$this->model) {
            return;
        }
        $data = $this->model->details()->findOrFail($id);
        return $data;
    }

    public function create($payload)
    {
        return $this->model->create($payload);
    }

    public function update($id, $payload)
    {
        $data = $this->getById($id);
        $data->update($payload);
        return $data;
    }
}
