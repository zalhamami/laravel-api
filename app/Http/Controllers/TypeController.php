<?php

namespace App\Http\Controllers;

use App\Repositories\TypeRepository;
use Illuminate\Http\Request;

class TypeController extends ApiController
{
    private $repo;

    public function __construct() {
        $this->repo = new TypeRepository();
    }

    public function index()
    {
        $data = $this->repo->getAll();
        return $this->collectionData($data);
    }

    public function store(Request $request)
    {
        return $this->repo->create($request->all());
    }

    public function show($id)
    {
        $data = $this->repo->getById($id);
        return $this->singleData($data);
    }

    public function update(Request $request, $id)
    {
        return $this->repo->update($id, $request->all());
    }
}
