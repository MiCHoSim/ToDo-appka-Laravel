<?php

namespace App\Policies;

use App\Models\ToDoItem;
use App\Models\ToDoItemUser;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;


class ToDoItemPolicy
{
    use HandlesAuthorization;

    /**
     * Určuje, či ma uživateľ pŕistup do zoznamu úloh
     *
     * @return bool
     */
    public function viewAny(): bool
    {
        return true;
    }

    /**
     * Určuje, či sa môže uživateľovy zobraziť detail úlohy
     *
     * @param User $user
     * @param ToDoItem $toDoItem
     * @return Response
     */
    public function view(User $user, ToDoItem $toDoItem): Response
    {
        if (!$user->userHasThisTask($toDoItem))
        {
            return Response::deny('Nieste autorom tejto úlohy!',404);
        }
        return Response::allow();
    }

    /**
     * Určuje, či uživateľ môže vytvárať úlohu
     *
     * @return bool
     */
    public function create():bool
    {
        return true;
    }

    /**
     * Určuje, či uživateľ môže editovať úlohu
     *
     * @param User $user
     * @param ToDoItem $toDoItem
     * @return Response
     */
    public function update(User $user, ToDoItem $toDoItem)
    {
        if ($user->id != $toDoItem->autor_id)
        {
            return Response::deny('Nieste autorom tejto úlohy!');
        }
        return Response::allow();
    }

    /**
     * Určuje, či uživateľ môže odstrániť úlohu
     *
     * @param User $user
     * @param ToDoItem $toDoItem
     * @return Response
     */
    public function delete(User $user, ToDoItem $toDoItem)
    {
        if ($user->id != $toDoItem->autor_id)
        {
            return Response::deny('Nieste autorom tejto úlohy!');
        }
        return Response::allow();
    }

    /**
     * Určuje, či uživateľ môže úlohu nastaviť ako dokončenú
     * (aby to fungovalo treba pridať (prepojiť) 'updateDone' => 'updateDone' do ovladača -> resourceAbilityMap())
     *
     * @param User $user
     * @param ToDoItem $toDoItem
     * @return Response
     */
    public function updateDone(User $user, ToDoItem $toDoItem)
    {
        if (!$user->userHasThisTask($toDoItem))
        {
            return Response::deny('Nieste autorom tejto úlohy!');
        }
        return Response::allow();
    }
    /**
     * Určuje, či uživateľ môže úlohu nastaviť ako dokončenú
     * (aby to fungovalo treba pridať (prepojiť) 'shared' => 'shared' do ovladača -> resourceAbilityMap())
     *
     * @param User $user
     * @param ToDoItem $toDoItem
     * @return Response
     */
    public function shared(User $user, ToDoItem $toDoItem)
    {
        if (!$user->userAutorThisTask($toDoItem))
        {
            return Response::deny('Nieste autorom tejto úlohy!');
        }
        return Response::allow();
    }

}
