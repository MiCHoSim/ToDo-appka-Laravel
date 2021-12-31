<?php

namespace App\Http\Controllers;

use App\Http\Requests\todoitem\SharedRequest;
use App\Http\Requests\todoitem\StoreRequest;
use App\Http\Requests\todoitem\UpdateDoneRequest;
use App\Http\Requests\todoitem\UpdateRequest;
use App\Models\Category;
use App\Models\ToDoItem;
use App\Models\ToDoItemUser;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Exception;

class ToDoItemController extends Controller
{
    /**
     * Nastav všetky akcie pre prihlaseného uživateľa.
     *
     * Prepojenie akcie kontroléra s policy triedou
     *
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->authorizeResource(ToDoItem::class, 'task');
    }

    /**
     * Zobraz a filtruj zoznam úloh prihlaseného uživateľa.
     *
     * @return View
     */
    public function index(ToDoItem $toDoItem): view
    {
        $activFilter = request()->get('filter'); // aktuálne nastavený filter
        $toDoItem->validateFilter($activFilter);

        $items = $toDoItem->getItemsByFilter();

        $user = new User();
        $sharedUsers = $user->getUsersPairs();

        return view('todoitem.index', ['items' => $items, 'filters' => $toDoItem->getFilters(), 'activFilter' => $activFilter, 'sharedUsers' => $sharedUsers]);
    }

    /**
     * Zobraz formulár pre vytvorenie novej úlohy.
     *
     * @return View
     */
    public function create():View
    {
        $category = new Category();
        return view('todoitem.create', ['categories' => $category->getCategoriesPairs()]);
    }

    /**
     * Zvaliduj odoslané dáta a vytvor novú úlohu.
     *
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        ToDoItem::create($request->all());
        ToDoItemUser::create(['to_do_item_id'=> DB::getPdo()->lastInsertId(), 'user_id' => Auth::id()]);

        return redirect()->route('task.index');
    }

    /**
     * Načitaj To Do položku a predaj jej data do šablony
     *
     * @param ToDoItem $toDoItem
     * @return View
     */
    public function show(ToDoItem $task): View
    {
        $user = new User();
        $sharedUsers = $user->getUsersPairs();
        return view('todoitem.show', ['task' => $task, 'sharedUsers' => $sharedUsers]);
    }

    /**
     * Zobraz formulár na editáciu úlohy a predaj danému pohladu načitanú úlohu.
     *
     * @param ToDoItem $task
     * @return View
     */
    public function edit(ToDoItem $task): View
    {
        $category = new Category();
        return view('todoitem.edit', ['task' => $task, 'categories' => $category->getCategoriesPairs()]);
    }

    /**
     * Zvaliduj odoslané data a uprav úlohu.
     *
     * @param UpdateRequest $request
     * @param ToDoItem $task
     * @return RedirectResponse
     */
    public function update(UpdateRequest $request, ToDoItem $task): RedirectResponse
    {
        $task->update($request->all());

        return redirect()->route('task.index');
    }

    /**
     *  Odstráň úlohu z databáze.
     *
     * @param ToDoItem $task
     * @return RedirectResponse
     */
    public function destroy(ToDoItem $task): RedirectResponse
    {
        try {
            $task->delete();
        } catch (Exception $exception) {
            return redirect()->back()->withErrors(['Pri odstranovaní úlohy došlo k chybe.']);
        }

        return redirect()->route('task.index');
    }

    /**
     * Aktualizuj/Zmen hodnotu "done" v databáze
     *
     * @param UpdateDoneRequest $request Hodnoty formulára
     * @param ToDoItem $task Inštancia Triedy Úloh
     * @return RedirectResponse
     */
    public function updateDone(UpdateDoneRequest $request, ToDoItem $task)
    {
        $task->update($request->all());
        return redirect()->back();
    }

    /**
     * Ulož zdieľanie do tabuľky
     *
     * @param SharedRequest $request Hodnoty formulára
     * @param ToDoItem $task Inštancia Triedy Úloh
     * @return RedirectResponse
     */
    public function shared(SharedRequest $request, ToDoItem $task)
    {
        ToDoItemUser::create($request->all());
        return redirect()->back();
    }
}
