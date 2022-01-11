<?php

namespace App\Policies;

use App\Models\ToDoItem;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Auth\Access\Response;


class ToDoItemPolicy
{
    use HandlesAuthorization;

    /**
     * Specifies whether the user has access to the task list
     *
     * @return bool
     */
    public function viewAny(): bool
    {
        return true;
    }

    /**
     * Specifies whether the user's job detail can be displayed
     *
     * @param User $user
     * @param ToDoItem $toDoItem
     * @return Response
     */
    public function view(User $user, ToDoItem $toDoItem): Response
    {
        if (!$user->userHasThisTask($toDoItem))
        {
            return Response::deny('Nieste vlastníkom tejto úlohy!');
        }
        return Response::allow();
    }

    /**
     * Specifies whether the user can create a task
     *
     * @return bool
     */
    public function create():bool
    {
        return true;
    }

    /**
     * Specifies whether the user can edit the task
     *
     * @param User $user
     * @param ToDoItem $toDoItem
     * @return Response
     */
    public function update(User $user, ToDoItem $toDoItem)
    {
        if ($user->id != $toDoItem->author_id)
        {
            return Response::deny('Nieste autorom tejto úlohy!');
        }
        return Response::allow();
    }

    /**
     * Specifies whether the user can delete the task
     *
     * @param User $user
     * @param ToDoItem $toDoItem
     * @return Response
     */
    public function delete(User $user, ToDoItem $toDoItem)
    {
        if ($user->id != $toDoItem->author_id)
        {
            return Response::deny('Nieste autorom tejto úlohy!');
        }
        return Response::allow();
    }

    /**
     * Specifies whether the user can set the task to complete
     * (for this to work, add (link) 'updateDone' => 'updateDone' to the driver -> resourceAbilityMap())
     *
     * @param User $user
     * @param ToDoItem $toDoItem
     * @return Response
     */
    public function updateDone(User $user, ToDoItem $toDoItem)
    {
        if (!$user->userHasThisTask($toDoItem))
        {
            return Response::deny('Nieste vlastníkom tejto úlohy!');
        }
        return Response::allow();
    }
    /**
     * Specifies whether the user can set the task to complete
     * (for this to work, add (link) 'shared' => 'shared' to the driver -> resourceAbilityMap())
     *
     * @param User $user
     * @param ToDoItem $toDoItem
     * @return Response
     */
    public function shared(User $user, ToDoItem $toDoItem)
    {
        if ($toDoItem->author_id != $user->id)
        {
            return Response::deny('Nieste autorom tejto úlohy!');
        }
        return Response::allow();
    }

}
