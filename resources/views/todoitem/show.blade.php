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
        <strong>Autor: </strong><i class="fa fa-user"></i> {{ $task->autor->name }}
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
    <a class="btn-sm btn-{{ $task->done ? 'danger' : 'success' }}" href="#" onclick="event.preventDefault(); $('#item-update-done-{{ $task->id }}').submit();">Označiť {{ $task->done ? 'nedokončené' : 'dokončené' }}</a>
    <form action="{{ route('task.updateDone', ['task' => $task]) }}" method="POST" id="item-update-done-{{ $task->id }}" class="d-none">
        @csrf
        @method('PUT')
        <input type="text" name="done" id="done" value="{{ $task->done ? '0' : '1' }}"/>
    </form>

    @can('update', $task)
        <a class="btn-sm btn-primary" href="{{ route('task.edit', ['task' => $task]) }}">Editovať</a>
    @endcan
    @can('delete', $task)
        <a class="btn-sm btn-danger" href="#" onclick="event.preventDefault(); $('#item-delete-{{ $task->id }}').submit();">Odstrániť</a>
        <form action="{{ route('task.destroy', ['task' => $task]) }}" method="POST" id="item-delete-{{ $task->id }}" class="d-none">
            @csrf
            @method('DELETE')
        </form>
    @endcan
    @can('shared', $task)
        <form action="{{ route('task.shared', ['task' => $task]) }}" method="POST">
            @csrf
            <div class="form-inline">
                <label for="user_id" class="sr-only">Zdieľať s</label>
                <select class="form-control" name="user_id" id="user_id">
                    @foreach ($sharedUsers as $id => $name)
                        <option {{ old('category_id') == $id ? 'selected' : '' }} value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
                <input type="hidden" name="to_do_item_id" id="to_do_item_id" value="{{ $task->id }}">
                <button type="submit" class="btn btn-primary">Zdieľať</button>
            </div>
        </form>
    @endcan
@endsection
