<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class ToDoItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Filter constants.
     */
    const
        DONE = 'done',
        MINE = 'mine',
        SHARED_WITH_ME = 'shared_with_me',
        SHARED_WITH_YOU = 'shared_with_you',
        ALL = 'all';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'task',
        'term',
        'category_id',
        'done',
        'author_id',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'term' => 'datetime',
    ];

    /**
     * Create an instance of the Eloquent model.
     *
     * @param  array $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        /*
        // Automatic assignment of the logged in user to the eloquent model
        self::creating(static function (ToDoItem $toDoItem) {
            if (Auth::check()) {
                $toDoItem->author_id = Auth::id();
            }
        })
        */
    }

    /**
     * Returns a category filter, where key is the all / id category and value is the category name
     *
     * @return array
     */
    public function getFilterCategories(): array
    {
        return [self::ALL => 'Všetky'] + Category::orderBy('name')->get()->pluck('name','id')->toArray();
    }


    /**
     * Returns a filter where key is the filter constant and value is the name of the filter
     *
     * @return array
     */
    public function getFilter(): array
    {
        return [
            self::ALL => 'Všetky',
            self::DONE => 'Dokončené',
            self::MINE => 'Moje',
            self::SHARED_WITH_ME => 'Zdieľané so mnou',
            self::SHARED_WITH_YOU => 'Zdieľané s tebou',
        ];
    }

    /**
     * Return to the Filtered Task List
     *
     * @param array $activFilter Set filter
     * @return mixed
     */
    public function getItemsByFilter($activFilter)
    {
        $query = new ToDoItem();

        //category filtering
        if (in_array($activFilter['category'], Category::get()->pluck('id')->toArray()))
        {
            $query = $query->where('category_id', $activFilter['category']);
        }

        //ilter completed tasks
        if ($activFilter['filter'] === ToDoItem::DONE)
        {
            $query = $query->where('done', 1);
        }
        // filtering the tasks of the logged in user / author
        elseif ($activFilter['filter'] === ToDoItem::MINE)
        {
            $query = $query->where('author_id', Auth::id());
        }

        // filtering a task that is shared with a logged in user
        if ($activFilter['filter'] === ToDoItem::SHARED_WITH_ME)
        {
            $query = $query->where('author_id', '!=', Auth::id())->whereHas('users', function ($query) {
                return $query->where('user_id',Auth::id());
            });
        }
        // filtering Tasks that the logged in user has shared
        elseif ($activFilter['filter'] === ToDoItem::SHARED_WITH_YOU)
        {
            $query = $query->where('author_id', Auth::id())->whereHas('users', function ($query) {
                return $query->where('user_id', '!=', Auth::id());
            });
        }
        // tasks of the logged in user
        else
        {
            $query = $query->whereHas('users', function ($query) {
                return $query->where('user_id',Auth::id());
            });
        }

        return $query->orderBy('term')->simplePaginate(5)->withQueryString();
    }

    /**
     * Return the author of the task as an instance of the user model
     * Loads the author of the task
     *
     * @return BelongsTo
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class,'author_id');
    }

    /**
     * Return the category as an instance of the category model
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get all shared users of the task
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }
}
