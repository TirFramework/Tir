<?php

namespace Tir\Crud\Support\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Arr;
use function Termwind\renderUsing;

class CrudRequest extends FormRequest
{
    private array $unDoted;
    private array $fields;
    private mixed $model;
    private array $creationRules;
    private array $updateRules;
    private array $onlyFields;

    private mixed $scaffolder;

    public function __construct($model, $scaffolder) {
        parent::__construct();
        $this->model = $model;
        $this->scaffolder = $scaffolder;
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return $this->getRules();
    }


    private function getRules(): array
    {
        $rules = [];
        if ($this->method() == 'POST') {
            $rules =  $this->creationRules;
        }

        if ($this->method() == 'PUT' || $this->method() == 'PATCH') {
            $rules =  $this->updateRules;
        }

        return $rules;
    }


    protected function prepareForValidation()
    {
        $this->model = $this->input('crudModel');
        $this->creationRules = $this->model->getCreationRules();
        $this->updateRules = $this->model->getUpdateRules();
        $this->fields = collect($this->model->getAllDataFields())
            ->pluck('request')->flatten()->unique()->toArray();

        //get only request that has equal field in scaffold
        $this->onlyFields = $this->all();
        //convert dot string request to array
        $this->unDoted = Arr::undot($this->onlyFields);


        //Make request empty
        foreach ($this->all() as $offset => $value) {
            $this->offsetUnset($offset);
        }

        $this->merge($this->unDoted);
    }


    protected function groupByNumber($array): array
    {
        $result = array();

        foreach ($array as $key => $value) {
            $parts = preg_split('/\.\d+\./', $key);
            if (count($parts) == 1) {
                $result[$key] = $value;
            } else {
                preg_match('/\.\d+\./', $key, $matches);
                $index = str_replace('.', '', $matches)[0] ?? null;
                $prefix = $parts[0] ?? null;
                $suffix = $parts[1] ?? null;

                if ($suffix) {
                    $result[$prefix][$index][$suffix] = $value;
                } else {
                    $result[$prefix][$index] = $value;
                }
            }

        }

        return $result;
    }


    protected function passedValidation()
    {
        //make ready request for mongodb
        if ($this->model->getConnection()->getDriverName() === 'mongodb') {

            foreach ($this->all() as $offset => $value) {
                $this->offsetUnset($offset);
            }
            $this->merge($this->groupByNumber($this->onlyFields));
        }
    }


}
