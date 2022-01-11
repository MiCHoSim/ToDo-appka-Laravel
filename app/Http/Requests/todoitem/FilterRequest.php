<?php

namespace App\Http\Requests\todoitem;

use App\Models\ToDoItem;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FilterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        $toDoItem = new ToDoItem();
        return [
            'category' => [Rule::in(array_keys($toDoItem->getFilterCategories()))],
            'filter' => [Rule::in(array_keys($toDoItem->getFilter()))],
        ];
    }
}
