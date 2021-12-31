<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ToDoItemUser extends Pivot
{
    /**
     * Pole atribútov, ktore nie su zobrazené pri výpise
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * Zrušenie ukladania timestamps
     *
     * @var bool
     */
    public $timestamps = false;

}
