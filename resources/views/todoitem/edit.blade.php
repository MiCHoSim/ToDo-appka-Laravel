@extends('base')

@section('title', 'Editácia úlohy ' . $task->task)
@section('description', 'Editor úloh.')

@section('content')
    <h1>Editácia úlohy</h1>
    <form action="{{ route('task.update', ['task' => $task]) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="form-group">
            <label for="task">Úloha</label>
            <input type="text" name="task" id="task" class="form-control" value="{{ old('task') ?: $task->task }}" required minlength="5"/>
        </div>

        <div class="form-group">
            <label for="term">Termín</label>
            <input type="datetime-local" name="term" id="term" class="form-control" value="{{ old('term') ?: ($task->term ? $task->term->isoFormat('YYYY-MM-DDTHH:MM') : '') }}"/>
        </div>
        <div class="form-group">
            <label for="category_id">Kategória</label>
            <select class="form-control" name="category_id" id="category_id">
                @foreach ($categories as $id => $name)
                    <option {{ $task->category_id == $id ? 'selected' : '' }} value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Uložit zmeny</button>
    </form>
@endsection
