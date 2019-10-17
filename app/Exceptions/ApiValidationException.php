<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use phpDocumentor\Reflection\Types\Parent_;

class ApiValidationException extends RLException
{
    public $validator;

    public $errorBag;

    public function __construct($validator)
    {
        parent::__construct('The given data was invalid.');

        $this->message = $validator->getMessageBag();
        $this->name = 'VALIDATION_FAILURE';
        $this->code = 422;
    }

    public function errors()
    {
        return $this->validator->errors()->messages();
    }

    public function getResponse()
    {
        return Response::json([
            'name' => $this->name,
            'message' => 'The given data was invalid.',
            'errors' => $this->getMessage(),
        ], $this->code);
    }
}
