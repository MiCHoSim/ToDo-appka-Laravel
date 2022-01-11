<?php

namespace App\Http\Controllers;

use App\Http\Requests\todoitem\FilterRequest;
use App\Http\Requests\todoitem\SharedRequest;
use App\Http\Requests\todoitem\StoreRequest;
use App\Http\Requests\todoitem\UpdateDoneRequest;
use App\Http\Requests\todoitem\UpdateRequest;
use App\Models\Category;
use App\Models\ToDoItem;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Exception;

class ToDoItemController extends Controller
{
    /**
     * Set all actions for the logged in user.
     *
     * Link the controller action to the policy class
     *
     */
    public function __construct()
    {
        //$this->middleware('auth');
        $this->authorizeResource(ToDoItem::class, 'task');
    }

    /**
     * View and filter the task list of the logged in user.
     *
     * @return View
     */
    public function index(FilterRequest $request, ToDoItem $toDoItem): view
    {
        $activFilter = isset($request->all()['category']) ? $request->all() : ['category' => ToDoItem::ALL, 'filter' => ToDoItem::ALL]; // currently set filter

        $items = $toDoItem->getItemsByFilter($activFilter);

        return view('todoitem.index',
            ['items' => $items,
                'categories' => $toDoItem->getFilterCategories(),
                'filters' => $toDoItem->getFilter(),
                'activFilter' => $activFilter,
                'sharedUsers' => User::where('id', '!=',Auth::id())->orderBy('name')->get()]);
    }

    /**
     * Display the form for creating a new task.
     *
     * @return View
     */
    public function create():View
    {
        return view('todoitem.create', ['categories' =>  Category::orderBy('name')->get()]);
    }

    /**
     * Validate the submitted data and create a new task.
     *
     * @param StoreRequest $request
     * @return RedirectResponse
     */
    public function store(StoreRequest $request): RedirectResponse
    {
        $task = $request->all();
        $task['author_id'] = Auth::id();

        $toDoItem = ToDoItem::create($task); // save a new task

        $toDoItem->users()->attach(Auth::id(),['to_do_item_id'=> $toDoItem->id]); // creating a link to the task

        return redirect()->route('task.index');
    }

    /**
     * Load It Into The Item And Sell It Data To The Template
     *
     * @param ToDoItem $toDoItem
     * @return View
     */
    public function show(ToDoItem $task): View
    {
        return view('todoitem.show', ['task' => $task, 'sharedUsers' => User::where('id',  '!=',Auth::id())->orderBy('name')->get()]);
    }

    /**
     * Display the form for editing the task and selling the loaded task to the given view.
     *
     * @param ToDoItem $task
     * @return View
     */
    public function edit(ToDoItem $task): View
    {
        return view('todoitem.edit', ['task' => $task, 'categories' => Category::orderBy('name')->get()]);
    }

    /**
     *Validate the submitted data and edit the task.
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
     *  Delete the task from the database.
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
     * Update / Change the value "done" in the database
     *
     * @param UpdateDoneRequest $request Form values
     * @param ToDoItem $task Task Class Instance
     * @return RedirectResponse
     */
    public function updateDone(UpdateDoneRequest $request, ToDoItem $task)
    {
        $task->update($request->all());
        return redirect()->back();
    }

    /**
     * Save the share to databashe
     *
     * @param SharedRequest $request Form values
     * @param ToDoItem $task Task Class Instance
     * @return RedirectResponse
     */
    public function shared(SharedRequest $request, ToDoItem $task)
    {
        $task->users()->attach($request->all()['user_id'],['to_do_item_id'=> $request->all()['to_do_item_id']]); // creating a link to the task

        return redirect()->back();
    }
}
