@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} домен
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.domains.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="title"> Название </label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                @if($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label class="required" for="template_id">Шаблон по умолчанию</label>
                <select class="form-control select2 {{ $errors->has('template_id') ? 'is-invalid' : '' }}" name="template_id" id="template_id" required>
                    <option value="">-- Необходимо выбрать --</option>
                    @foreach($templates as $id => $tem)
                        <option value="{{ $id }}" {{ (in_array($id, old('template_id', []))  ) ? 'selected' : '' }}>{{ $tem }}</option>
                    @endforeach
                </select>
                @if($errors ->has('template_id'))
                    <span class="text-danger">{{ $errors->first('template_id') }}</span>
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