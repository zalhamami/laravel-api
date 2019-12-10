<?php

namespace App\Repositories;

use App\Traits\ApiRequester;

class Repository
{
    use ApiRequester;

    protected $model;

    public function getAll()
    {
        if (!$this->model) {
            return;
        }
        
        $request = $this->getRequest();
        $collection = $this->model;
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

    public function getById($id)
    {
        if (!$this->model) {
            return;
        }
        $data = $this->model->findOrFail($id);
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