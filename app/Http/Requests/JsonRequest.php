<?php

namespace App\Http\Requests;

use App\Abstracts\Http\FormRequest;
use App\Models\Document\Document as Model;
use App\Utilities\Date;
use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest as BaseFormRequest;

class JsonRequest extends BaseFormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    protected function prepareForValidation()
    {
        //itterate the array then add company id
        $input = $this->json()->all();

        foreach ($input as $key => $value) {
            $input[$key]['company_id'] = company_id();
        }

        $this->replace($input);
    }
}
