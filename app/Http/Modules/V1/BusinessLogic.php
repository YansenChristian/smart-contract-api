<?php


namespace App\Http\Modules\V1;


use Illuminate\Support\Facades\Validator;

abstract class BusinessLogic
{
    protected $scopes;

    /**
     * The main function of this Business logic
     * @return mixed
     */
    abstract public function run();

    public function getScopes()
    {
        return $this->scopes;
    }

    public function getScope($key, $classString = null)
    {
        if(!isset($this->scopes[$key])) {
            return null;
        }

        if($classString !== null) {
            return array_key_exists($classString, $this->scopes[$key]) ? array_first($this->scopes[$key]) : null;
        }

        return isset($this->scopes[$key]) ? array_first($this->scopes[$key]) : null;
    }

    public function hasScope($key, $classString = null)
    {
        if(!isset($this->scopes[$key])) {
            return false;
        }

        if($classString !== null) {
            return array_key_exists($classString, $this->scopes[$key]) ? true : false;
        }

        return isset($this->scopes[$key]) ? true : false;
    }

    public function putScope($key, $value)
    {
        $dataType = gettype($value) == 'object' ? get_class($value) : gettype($value);

        $this->scopes[$key] = [
            $dataType => $value
        ];
    }

    public function removeScope($key)
    {
        unset($this->scopes[$key]);
    }

    public function validateScopes($rules)
    {
        $messages = [
            'required' => 'Missing required scope :attribute field is required.',
            'required_without' => 'Missing required scope :attribute when :values is missing.'
        ];

        $attributes = array_combine(array_keys($rules), array_keys($rules));

        $validator = Validator::make($this->scopes, $rules, $messages);
        $validator->setAttributeNames($attributes);

        if ($validator->fails()) {
            throw new \Exception(sprintf($validator->errors()->first()), 422);
        }

        return;
    }
}