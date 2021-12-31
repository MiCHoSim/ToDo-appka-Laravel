<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ToDoItem extends Model
{
    use HasFactory;

    /**
     * Konštanty filtra.
     */
    const
        CATEGORY_ID = 'category_id',
        DONE = 'done',
        MINE = 'mine',
        SHARED_WITH_ME = 'shared_with_me',
        SHARED_WITH_YOU = 'shared_with_you',
        ALL = 'all';

    /**
     * Nastavený filter
     * @var string
     */
    public $filter;

    /**
     * Hodnota filtrovanej kategórie
     *
     * @var NULL|int
     */
    public $categoryId;

    /**
     * Pole vlastností, ktoré niesu chranené pred mass assigment útokom.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'task',
        'term',
        'category_id',
        'done',
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
     * Vytvor inštanciu Eloquent modelu.
     *
     * @param  array $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        // Automaticke pridelenie prihlaseného uživateľa do eloquent modelu
        self::creating(static function (ToDoItem $toDoItem) {
            if (Auth::check()) {
                $toDoItem->autor_id = Auth::id();
            }
        });
    }

    /**
     * Vrať pole Filtra, kluč je konštanta filtra a hodnota názov filtra.
     *
     * @return array
     */
    public function getFilters(): array
    {
        $category = new Category();
        return [
            self::ALL => 'Všetky',
            self::CATEGORY_ID => array('name' => 'Kategórie') + $category->getCategoriesPairs(),
            self::DONE => 'Dokončené',
            self::MINE => 'Moje',
            self::SHARED_WITH_ME => 'Zdieľané so mnou',
            self::SHARED_WITH_YOU => 'Zdieľané s tebou',
        ];
    }

    /**
     * Zvaliduj hodnoty pre Filter
     *
     * @param string $filterItems Prikaz filtrovania podľa
     * @return RedirectResponse|void
     */
    public function validateFilter($filterItems)
    {
        $activFilterArray = explode('-',$filterItems); //Rozdelenie filtra na Filter a Kategóriu
        $this->filter = $activFilterArray[0]; // Filter
        $this->categoryId = isset($activFilterArray[1]) ? $activFilterArray[1] : NULL; // Hodnota filtra

        // Validacia Filtra
        if(!in_array($this->filter, array_keys($this->getFilters())) && $this->filter !== '')
            return redirect()->route('task.index');

        if($this->categoryId !== NULL || $this->filter === ToDoItem::CATEGORY_ID)
        {
            $category = new Category();

            if(!in_array($this->categoryId, array_keys($category->getCategoriesPairs())))
                return redirect()->route('task.index');
        }
    }

    /**
     * Vrať Filtrovaný zoznam Úloh
     *
     * @return mixed
     */
    public function getItemsByFilter()
    {
        // Filtrovanie podľa Kategórie
        if($this->filter === ToDoItem::CATEGORY_ID)
        {                                     //->where('done', 0)
            return User::find(Auth::id())->toDoItems()->where($this->filter, $this->categoryId)->orderBy('term')->simplePaginate(5)->withQueryString();
        }
        // Filtrovanie podľa toho či je úloha dokončená
        if($this->filter=== ToDoItem::DONE)
        {
            return User::find(Auth::id())->toDoItems()->where($this->filter, 1)->simplePaginate(5)->withQueryString();
        }
        // Filtrovanie podľa toho či je prihlasený uživateľ autorom úlohy,
       if($this->filter === ToDoItem::MINE)
        {
            return ToDoItem::join('to_do_item_user','to_do_items.id','to_do_item_user.to_do_item_id')
                ->where('to_do_items.autor_id', Auth::id())
                ->where('to_do_item_user.user_id', '=', Auth::id())->orderBy('term')
                ->simplePaginate(5)->withQueryString();
        }
        // úlohy, ktoré sú prihlasenému uživateľovy zdieľané
        if($this->filter === ToDoItem::SHARED_WITH_ME)
        {
            return User::find(Auth::id())->toDoItems()->where('to_do_items.autor_id', '!=',Auth::id())->simplePaginate(5)->withQueryString();
        }
        // Úlohy, ktoré zdieľal prihlasený uživateľ
        if($this->filter === ToDoItem::SHARED_WITH_YOU)
        {
            return ToDoItem::join('to_do_item_user','to_do_items.id','to_do_item_user.to_do_item_id')
                ->where('to_do_items.autor_id', Auth::id())
                ->where('to_do_item_user.user_id', '!=', Auth::id())->orderBy('term')
                ->simplePaginate(5)->withQueryString();
        }
        // Všetky úlohy prihlaseného uživateľa
        else
        {
            return ToDoItem::join('to_do_item_user','to_do_items.id','to_do_item_user.to_do_item_id')
                ->where('to_do_item_user.user_id', '=', Auth::id())->orderBy('term')
                ->simplePaginate(5)->withQueryString();
        }
    }

    /**
     * Vrať autora úlohy ako inštanciu modelu user
     * Načíta autora úlohy
     *
     * @return BelongsTo
     */
    public function autor(): BelongsTo
    {
        return $this->belongsTo(User::class,'autor_id', 'id');
    }

    /**
     * Vrať uzivatele úlohy ako inštanciu modelu user
     * Načita uživateľa úlohy
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id', 'id');
    }

    /**
     * Vrať kategóriu ako inštanciu modelu category
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Ziskaj všetkých zdieľaných uživateľov úlohy
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    /**
     * Vráti pole Id úloh uživateľa
     *
     * @param int $userId Id uživateľa
     * @return array
     */
    public function getToDoItemsUser($userId): array
    {
        return DB::table('to_do_items')->where('autor_id', $userId)->pluck('id')->toArray();
    }
}
