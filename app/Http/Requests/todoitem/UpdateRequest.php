<?php

namespace App\Http\Requests\todoitem;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'task' => ['required', 'min:5'],
            'term' => ['date','nullable'],
            'category_id' => ['exists:categories,id'],
        ];
    }
}
