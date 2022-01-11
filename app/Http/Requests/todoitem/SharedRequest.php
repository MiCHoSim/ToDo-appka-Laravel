<?php

namespace App\Http\Requests\todoitem;

use Illuminate\Foundation\Http\FormRequest;

class SharedRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'to_do_item_id' => ['exists:to_do_items,id'],
            'user_id' => ['exists:users,id'],
        ];
    }
}
