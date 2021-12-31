<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Category extends Model
{
    use HasFactory;

    /**
     * Pole vlastností, ktoré niesu chranené pred mass assigment útokom.
     *
     * @var array
     */
    protected $fillable = [
        'name',
    ];

    /**
     * Vráti pole kategórií, kde kluč je id a hodnota je názov kategórie
     * @return array
     */
    public function getCategoriesPairs() : array
    {
        return DB::table('categories')->orderBy('name')->pluck('name','id')->toArray();
    }
}
