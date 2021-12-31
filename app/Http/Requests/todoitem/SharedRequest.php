<?php

namespace App\Http\Requests\todoitem;

use App\Models\ToDoItem;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SharedRequest extends FormRequest
{

    /**
     * Vrať validačne pravidlo formuláru, ktorý ma na starosti zdieľanie úlohy
     *
     * @return array
     */
    public function rules()
    {
        $user = new User();
        $toDoItem = new ToDoItem();
        return [
            'to_do_item_id' => [Rule::in($toDoItem->getToDoItemsUser(Auth::id()))],
            'user_id' => [Rule::in(array_keys($user->getUsersPairs()))],
        ];
    }
}
