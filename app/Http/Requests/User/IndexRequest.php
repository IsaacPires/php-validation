<?php

namespace App\Http\Requests\User;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IndexRequest extends FormRequest
{
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
     * @return array
     */
    public function rules()
    {
        $sortableColumns = array_merge((new User)->getFillable(), ['id']);

        return [
            'sort_by' => [
                'sometimes',
                'string',
                Rule::in($sortableColumns)
            ],
            'sort_dir' => [
                'sometimes',
                'string',
                Rule::in(['asc', 'desc'])
            ],
            'per_page' => [
                'sometimes',
                'integer',
                'min:1',
                'max:100'
            ]
        ];
    }

    /**
     * Get the default values for the request.
     *
     * @return array
     */
    public function validated($key = null, $default = null)
    {
        $validatedData = parent::validated($key, $default);

        return array_merge([
            'sort_by' => 'id',
            'sort_dir' => 'desc',
            'per_page' => 5,
        ], $validatedData);
    }
}