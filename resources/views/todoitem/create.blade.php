@extends('base')

@section('title', 'Nová úloha')
@section('description', 'Vytvorenie novej úlohy.')

@section('content')
    <h1>Nová úloha</h1>
    <form action="{{ route('task.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="task">Úloha</label>
            <input type="text" name="task" id="task" class="form-control" value="{{ old('task') }}" required minlength="5"/>
        </div>
        <div class="form-group">
            <label for="term">Termín</label>
            <input type="datetime-local" name="term" id="term" class="form-control" value="{{ old('term') }}"/>
        </div>
        <div class="form-group">
            <label for="category_id">Kategória</label>
            <select class="form-control" name="category_id" id="category_id">
                @foreach ($categories as $category)
                    <option {{ old('category_id') == $category->id ? 'selected' : '' }} value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Uložiť</button>
    </form>
@endsection
