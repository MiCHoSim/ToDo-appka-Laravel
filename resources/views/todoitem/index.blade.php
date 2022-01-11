@extends('base')

@section('title', 'Zoznam úloh')
@section('description', 'Zoznam všetkých úloh uživateľa.')

@section('content')
    <div class="d-flex justify-content-center">
        <form method="GET"class="form-inline">
            <select class="form-control" name="category" id="category" onchange="this.form.submit()">
                @foreach ($categories as $key => $category)
                    <option {{ isset($activFilter['category']) && $activFilter['category'] == $key ? 'selected' : '' }} value="{{ $key }}">{{ $category }}</option>
                @endforeach
            </select>
            <select class="form-control" name="filter" id="filter" onchange="this.form.submit()">
                @foreach ($filters as $key => $filter)
                        <option {{ isset($activFilter['filter']) && $activFilter['filter'] == $key ? 'selected' : '' }} value="{{ $key }}">{{ $filter }}</option>
                @endforeach
            </select>
        </form>
    </div>
    <table class="table table-striped table-bordered table-responsive-md">
        <thead>
        <tr>
            <th>Autor -> Zdieľane s </th>
            <th>Dokončená</th>
            <th>Úloha</th>
            <th>Termín</th>
            <th>Kategória</th>
            <th>Edit</th>
        </tr>
        </thead>
        <tbody>
        @forelse ($items as $item)
            <tr>
                <td class="align-middle"><i class="fa fa-user"></i> {{ $item->author->name }}
                    @forelse ($item->users as $user)
                         {{ $item->author->name != $user->name ? '-> '.$user->name : ''}}
                    @empty
                    @endforelse
                </td>
                <td class="align-middle">
                    <span class=" btn-sm btn-{{ $item->done ? 'success' : 'danger' }}">
                        {{ $item->done ? 'Dokončená' : 'Nedokončená' }}
                    </span>
                </td>
                <td class="align-middle">
                    <a class="font-weight-bolder text-decoration-none" href="{{ route('task.show', ['task' => $item]) }}">
                        {{ $item->task }}
                    </a>
                </td>
                <td class="align-middle"><i class="fa fa-calendar"></i> {{ $item->term ? $item->term->isoFormat('LLLL') : 'Neurčitý' }}</td>
                <td class="align-middle">{{ $item->category->name }}</td>
                <td class="align-middle">
                    <form action="{{ route('task.updateDone', ['task' => $item]) }}" method="POST" id="item-update-done-{{ $item->id }}" class="">
                        @csrf
                        @method('PUT')
                        <input class="d-none" type="number" name="done" id="done" value="{{ $item->done ? '0' : '1' }}"/>
                        <button type="submit" class="btn-block btn-sm btn-{{ $item->done ? 'danger' : 'success' }}">
                            Označiť {{ $item->done ? 'nedokončené' : 'dokončené' }}
                        </button>
                    </form>
                    @can('update', $item)
                        <a class="d-block text-center btn-sm btn-primary" href="{{ route('task.edit', ['task' => $item]) }}">Editovať</a>
                    @endcan
                    @can('delete', $item)
                        <form action="{{ route('task.destroy', ['task' => $item]) }}" method="POST" id="item-delete-{{ $item->id }}" class="">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-block btn-sm btn-danger">
                                Odstrániť
                            </button>
                        </form>
                    @endcan
                    @can('shared', $item)
                    <form action="{{ route('task.shared', ['task' => $item]) }}" method="POST">
                        @csrf
                        <div class="form-inline">
                            <label for="user_id" class="sr-only">Zdieľať s</label>
                            <select class="form-control" name="user_id" id="user_id">
                                @foreach ($sharedUsers as $user)
                                    <option {{ old('user_id') == $user->id ? 'selected' : '' }} value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                            <input type="hidden" name="to_do_item_id" id="to_do_item_id" value="{{ $item->id }}">
                            <button type="submit" class="btn btn-primary">Zdieľať</button>
                        </div>
                    </form>
                    @endcan
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center">
                    V tejto kategórií nemáte žiadné úlohy.
                </td>
            </tr>
        @endforelse
        </tbody>
    </table>
    <a href="{{ route('task.create') }}" class="btn btn-primary">
        Vytvoriť novú úlohu
    </a>
    <div class="text-center">
        {{ $items->links() }}
    </div>
@endsection
