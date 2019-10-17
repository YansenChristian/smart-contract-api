<?php


namespace App\Http\Modules\V1;


abstract class Service
{
    private $scopes;

    public function getScopes()
    {
        return $this->scopes;
    }

    public function getScope($key)
    {
        return isset($this->scopes[$key])
            ? array_first($this->scopes[$key])
            : null;
    }

    public function execute(array $businessesLogic, $additionalScopes = [])
    {
        # add additional scopes to current service's scopes
        foreach ($additionalScopes as $key => $object) {
            $class = gettype($object) == 'object' ? get_class($object) : gettype($object);
            $this->scopes[$key] = [
                $class => $object
            ];
        }

        # running businesses logic in the list
        $results = collect();
        foreach ($businessesLogic as $key => $class) {
            if (!class_exists($class)) {
                throw  new \Exception('Class "' . $class . '" does not exists!', 422);
            }
            $objective = new $class($this->scopes);
            $currentResultRun = $objective->run();
            $this->scopes = $objective->getScopes();
            $results->put($class, $currentResultRun);
        }

        return $results;
    }
}
