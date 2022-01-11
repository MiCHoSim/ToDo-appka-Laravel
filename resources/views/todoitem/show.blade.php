@extends('base')

@section('title', 'ToDo Item')
@section('description', 'Zobrazenie ToDo Itemov')

@section('content')

    <h3 class="border-bottom pb-2">{{ $task->task }}
        <span class=" btn-sm btn-{{ $task->done ? 'success' : 'danger' }}">
            {{ $task->done ? 'Dokončená' : 'Nedokončená' }}
        </span>
    </h3>
    <div>
        <strong>Autor: </strong><i class="fa fa-user"></i> {{ $task->author->name }}
    </div>
    <div>
        <strong>Termín: </strong><i class="fa fa-calendar"></i> {{ $task->term ? $task->term->isoFormat('LLLL') : 'Neurčitý' }}
    </div>
    <div>
        <strong>Kategoria: </strong>{{ $task->category->name }}
    </div>
    <div>
        <strong>Vytvorená: </strong><i class="fa fa-calendar"></i> {{ $task->created_at->isoFormat('LLLL') }}
    </div>
    <div>
        <strong>Upravená: </strong><i class="fa fa-calendar"></i> {{ $task->updated_at->isoFormat('LLLL') }}
    </div>
    <div class="btn-group">
        <form action="{{ route('task.updateDone', ['task' => $task]) }}" method="POST" id="item-update-done-{{ $task->id }}" class="">
            @csrf
            @method('PUT')
            <input class="d-none" type="text" name="done" id="done" value="{{ $task->done ? '0' : '1' }}"/>
            <button type="submit" class="btn-sm btn-{{ $task->done ? 'danger' : 'success' }}">
                Označiť {{ $task->done ? 'nedokončené' : 'dokončené' }}
            </button>
        </form>

        @can('update', $task)
            <a class="btn-sm btn-primary" href="{{ route('task.edit', ['task' => $task]) }}">Editovať</a>
        @endcan
        @can('delete', $task)
            <form action="{{ route('task.destroy', ['task' => $task]) }}" method="POST" id="item-delete-{{ $task->id }}" class="">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn-sm btn-danger">
                    Odstrániť
                </button>
            </form>
        @endcan
    </div>
    @can('shared', $task)
        <form action="{{ route('task.shared', ['task' => $task]) }}" method="POST">
            @csrf
            <div class="form-inline">
                <label for="user_id" class="sr-only">Zdieľať s</label>
                <select class="form-control" name="user_id" id="user_id">
                    @foreach ($sharedUsers as $user)
                        <option {{ old('user_id') == $user->id ? 'selected' : '' }} value="{{ $user->id }}">{{ $user->name }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="to_do_item_id" id="to_do_item_id" value="{{ $task->id }}">
                <button type="submit" class="btn btn-primary">Zdieľať</button>
            </div>
        </form>
    @endcan
@endsection
