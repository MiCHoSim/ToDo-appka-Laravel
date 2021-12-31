<?php

namespace App\Http\Requests\todoitem;

use App\Models\Category;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateRequest extends FormRequest
{
    /**
     * Vrať validačne pravidlo formuláru, ktorý ma na starosti editáciu úlohy.
     *
     * @return array
     */
    public function rules(): array
    {
        $category = new Category();
        return [
            'task' => ['required', 'min:5'],
            'term' => ['date','nullable'],
            'category_id' => [Rule::in(array_keys($category->getCategoriesPairs()))],
        ];
    }
}