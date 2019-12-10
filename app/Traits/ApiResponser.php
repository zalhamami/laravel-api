<?php

namespace App\Traits;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Validator;

trait ApiResponser
{
    protected function successResponse($data, $code = 200)
    {
        return response()->json($data, $code);
    }

    protected function errorResponse($message, $code)
    {
        return response()->json([
            'code' => $code,
            'message' => $message
        ], $code);
    }

    protected function collectionData($collection, $code = 200)
    {
        return $this->successResponse($collection, $code);
    }

    protected function singleData($model, $code = 200)
    {
        return $this->successResponse($model, $code);
    }

    protected function sortData($collection)
    {
        if (request()->has('sort_by')) {
            $attribute = request()->sort_by;
            if (request()->has('sort_direction') && strtolower(request()->sort_direction) == 'desc') {
                $collection = $collection->sortByDesc($attribute)->values();
                return $collection;
            }
            $collection = $collection->sortBy($attribute)->values();
        }
        return $collection;
    }

    protected function paginateData($collection)
    {
        $rules = [
            'per_page' => 'integer|min:2|max:50'
        ];
        Validator::validate(request()->all(), $rules);

        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 25;
        if (request()->has('per_page')) {
            $perPage = request()->per_page;
        }

        $result = $collection->slice(($page - 1) * $perPage, $perPage)->values();
        $paginated = new LengthAwarePaginator($result, $collection->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPath()
        ]);
        
        $paginated->appends(request()->all());
        
        return $paginated;
    }
}