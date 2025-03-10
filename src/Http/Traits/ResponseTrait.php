<?php

namespace QuickTrello\Http\Traits;

trait ResponseTrait
{

    public function sendResponse($code = 200, $message, $errors = null, $data = null, $pagination = null)
    {
        $array = [
            'status' => $code,
            'message' => $message,
        ];
        if (is_null($data) && !is_null($errors)) {
            $array['errors'] = $errors;
        } elseif (is_null($errors) && !is_null($data)) {
            $array['data'] = $data;
        }
        if (!is_null($pagination)) {
            $array['pagination'] = $pagination;
        }
        return response($array, $code);
    }
}
