<?php

namespace App\Validation;

use Respect\Validation\Exceptions\NestedValidationException;

class Validator
{
    private $errors;

    public function validate($request, array $rules, array $messages)
    {
        foreach ($rules as $field => $rule) {
            try{
                $rule->setName(ucfirst($field))->assert($request->getParam($field));
            }
            catch(NestedValidationException $e){
                $errors = $e->findMessages($messages);
                $filteredErrors = array_filter($errors);
                $this->errors[$field] = $filteredErrors;
            }
        }

        $_SESSION['errors'] = $this->errors;

        return $this;
    }

    public function failed()
    {
        return !empty($this->errors);
    }
}