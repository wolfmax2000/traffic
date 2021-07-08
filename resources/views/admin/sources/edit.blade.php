@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Редактировать источник
    </div>

    <div class="card-body">  
        <form method="POST" action="{{ route("admin.sources.update", [$source->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="title">Заголовок</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $source->title) }}" required>
                @if($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label class="required" for="utms">Макросы</label>
                <input class="form-control {{ $errors->has('utms') ? 'is-invalid' : '' }}" type="text" name="utms" id="utms" value="{{ old('utms', $source->utms) }}" required>
                @if($errors->has('utms'))
                    <span class="text-danger">{{ $errors->first('utms') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="scripts">Скрипты</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('scripts') ? 'is-invalid' : '' }}" name="scripts[]" id="scripts" multiple>
                    @foreach($scripts as $id => $script)
                        <option value="{{ $id }}" {{ (in_array($id, old('scripts', [])) || $source->scripts->contains($id)) ? 'selected' : '' }}>{{ $script }}</option>
                    @endforeach
                </select>
                @if($errors->has('scripts'))
                    <span class="text-danger">{{ $errors->first('scripts') }}</span>
                @endif
            </div>

            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection