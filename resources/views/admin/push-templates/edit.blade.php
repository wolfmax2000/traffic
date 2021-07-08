@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Редактировать кампанию
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.push-templates.update", [$push_template->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="title">Заголовок</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $push_template->title) }}" required>
                @if($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label class="required" for="url">Ссылка перехода</label>
                <input class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" type="text" name="url" id="url" value="{{ old('url', $push_template->url) }}" required>
                @if($errors->has('url'))
                    <span class="text-danger">{{ $errors->first('url') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label class="required" for="geo">Гео</label>               
                <select class="form-control select2 {{ $errors->has('geo') ? 'is-invalid' : '' }}" name="geo" id="geo" required>
                    <option value="">-- Необходимо выбрать --</option>
                    @foreach($countries as $cat)
                        <option value="{{ $cat }}" {{ $cat === old('geo', $push_template->geo) ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                @if($errors->has('geo'))
                    <span class="text-danger">{{ $errors->first('geo') }}</span>
                @endif                
            </div>

            <div class="form-group">
                <label for="device">Устройства</label>
                <select class="form-control select2 {{ $errors->has('device') ? 'is-invalid' : '' }}" name="device" id="device">
                    @foreach($devices as $id => $d)
                        <option value="{{ $id }}" {{ $id == old('device', $push_template->device) ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
                @if($errors->has('device'))
                    <span class="text-danger">{{ $errors->first('device') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="top_type">Тип топа</label>
                <select class="form-control select2 {{ $errors->has('top_type') ? 'is-invalid' : '' }}" name="top_type" id="top_type">
                    @foreach($top_types as $id => $d)
                        <option value="{{ $id }}" {{ $id == old('top_type', $push_template->top_type) ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
                @if($errors->has('top_type'))
                    <span class="text-danger">{{ $errors->first('top_type') }}</span>
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