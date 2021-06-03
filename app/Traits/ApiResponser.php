<?php

namespace App\Traits;

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
}
