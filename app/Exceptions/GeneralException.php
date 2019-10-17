<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use phpDocumentor\Reflection\Types\Parent_;

class GeneralException extends RLException
{
    public function __construct($message = null)
    {
        parent::__construct('An internal service error has occurred');

        $this->name = 'INTERNAL_SERVICE_ERROR';

        $this->code = 500;

        if($message != null) {
            $this->message = $message;
        }
    }

    public function getResponse()
    {
        return Response::json([
            'name' => $this->name,
            'message' => $this->getMessage()
        ], $this->code);
    }
}
