<?php
namespace App\Repositories;

use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Validation\Validator;

abstract class Repository
{
    abstract public function rules() : array;
    public function attributes()
    {
        return [];
    }
    public function messages()
    {
        return [];
    }

    public function validator($input, ?array $rules = null, ?array $messages = null, ?array $attributes = null)
    {
        $rules ??= $this->rules();
        $attributes ??= $this->attributes();
        $messages ??= $this->messages();
        return ValidatorFacade::make($input, $rules, $messages, $attributes)
                                ->after(fn($validator) => $this->after($validator));
    }
    public function validate($input)
    {
        return $this->validator($input)->validate();
    }
    protected function after(Validator $validator){}

    abstract public function findById($id) : mixed;
    abstract public function create(array $input) : mixed;
    abstract public function updateById($id, $input) : bool;
    abstract public function deleteById($id) : void;
    abstract public function getAll($paginated = true) : mixed;

}
