@extends('base')

@section('title', 'Zoznam úloh')
@section('description', 'Zoznam všetkých úloh uživateľa.')

@section('content')
    <div class="d-flex justify-content-center">
        <form method="GET"class="form-inline">
            <select class="form-control" name="filter" id="filter" onchange="this.form.submit()">
                @foreach ($filters as $key => $filter)
                    @if($key === \App\Models\ToDoItem::CATEGORY_ID)

                        <optgroup label="{{ $filter['name'] }}">

                            @php unset($filter['name']);  @endphp

                            @foreach ($filter as $category_id => $category)
                                <option {{ $activFilter == $key . '-' . $category_id ? 'selected' : '' }} value="{{ $key }}-{{$category_id }}">{{ $category }}</option>
                            @endforeach
                        </optgroup>
                    @else
                        <option {{ $activFilter == $key ? 'selected' : '' }} value="{{ $key }}">{{ $filter }}</option>
                    @endif
                @endforeach
            </select>
        </form>
    </div>
    <table class="table table-striped table-bordered table-responsive-md">
        <thead>
        <tr>
            <th>Autor {{ $activFilter === \App\Models\ToDoItem::SHARED_WITH_YOU ? '/ Zdieľané s' : '' }} </th>
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
                <td class="align-middle"><i class="fa fa-user"></i> {{ $item->autor->name }}
                    {{ $activFilter === \App\Models\ToDoItem::SHARED_WITH_YOU ? ' / ' . $item->user->name : '' }}
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
                    <a class="d-block text-center btn-sm btn-{{ $item->done ? 'danger' : 'success' }}" href="#" onclick="event.preventDefault(); $('#item-update-done-{{ $item->id }}').submit();">Označiť {{ $item->done ? 'nedokončené' : 'dokončené' }}</a>
                    <form action="{{ route('task.updateDone', ['task' => $item]) }}" method="POST" id="item-update-done-{{ $item->id }}" class="d-none">
                        @csrf
                        @method('PUT')
                        <input type="number" name="done" id="done" value="{{ $item->done ? '0' : '1' }}"/>
                    </form>
                    @can('update', $item)
                        <a class="d-block text-center btn-sm btn-primary" href="{{ route('task.edit', ['task' => $item]) }}">Editovať</a>
                    @endcan
                    @can('delete', $item)
                        <a class="d-block text-center btn-sm btn-danger" href="#" onclick="event.preventDefault(); $('#item-delete-{{ $item->id }}').submit();">Odstrániť</a>
                        <form action="{{ route('task.destroy', ['task' => $item]) }}" method="POST" id="item-delete-{{ $item->id }}" class="d-none">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endcan
                    @can('shared', $item)
                    <form action="{{ route('task.shared', ['task' => $item]) }}" method="POST">
                        @csrf
                        <div class="form-inline">
                            <label for="user_id" class="sr-only">Zdieľať s</label>
                            <select class="form-control" name="user_id" id="user_id">
                                @foreach ($sharedUsers as $id => $name)
                                    <option {{ old('category_id') == $id ? 'selected' : '' }} value="{{ $id }}">{{ $name }}</option>
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
