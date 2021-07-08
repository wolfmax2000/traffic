@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Добавить Push для кампании "{{$template->title}}"
        
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.pushes.store") }}" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="template_id" value="{{$template->id}}" />
            <div class="form-group">
                <label class="required" for="title">Заголовок</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}" required>
                @if($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label class="required" for="desc">Текст</label>
                <input class="form-control {{ $errors->has('desc') ? 'is-invalid' : '' }}" type="text" name="desc" id="desc" value="{{ old('desc', '') }}" required>
                @if($errors->has('desc'))
                    <span class="text-danger">{{ $errors->first('desc') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="limit">Limit </label>
                <input class="form-control {{ $errors->has('limit') ? 'is-invalid' : '' }}" type="text" name="limit" id="limit" value="{{ old('limit', '') }}">
                @if($errors->has('limit'))
                    <span class="text-danger">{{ $errors->first('limit') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="icon">Иконка</label>
                <div class="needsclick dropzone {{ $errors->has('icon') ? 'is-invalid' : '' }}" id="icon-dropzone">
                </div>
                @if($errors->has('icon'))
                    <span class="text-danger">{{ $errors->first('icon') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="image">Картинка</label>
                <div class="needsclick dropzone {{ $errors->has('image') ? 'is-invalid' : '' }}" id="image-dropzone">
                </div>
                @if($errors->has('image'))
                    <span class="text-danger">{{ $errors->first('image') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.tizer.fields.image_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="required" for="url">Ссылка перехода</label> 
                <input class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" type="text" name="url" id="url" value="{{ old('url', $template->url) }}" required>
                @if($errors->has('url'))
                    <span class="text-danger">{{ $errors->first('url') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label class="required" for="geo">Гео</label>               
                <select class="form-control select2 {{ $errors->has('geo') ? 'is-invalid' : '' }}" name="geo" id="geo" required>
                    <option value="">-- Необходимо выбрать --</option>
                    @foreach($countries as $cat)
                        <option value="{{ $cat }}" {{ $cat == old('geo', $template->geo) ? 'selected' : '' }}>{{ $cat }}</option>
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
                        <option value="{{ $id }}" {{ $id == old('device', $template->device) ? 'selected' : '' }}>{{ $d }}</option>
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
                        <option value="{{ $id }}" {{ $id == old('top_type', $template->top_type) ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
                @if($errors->has('top_type'))
                    <span class="text-danger">{{ $errors->first('top_type') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="top_type">Домен</label>
                <select class="form-control select2 {{ $errors->has('domen') ? 'is-invalid' : '' }}" name="domen" id="domen">
                    @foreach($domains as $id => $d)
                        <option value="{{ $d }}" {{ $id == old('domen', '') ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
                @if($errors->has('domen'))
                    <span class="text-danger">{{ $errors->first('domen') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="push_type">Тип Пуша</label>
                <select class="form-control select2 {{ $errors->has('push_type') ? 'is-invalid' : '' }}" name="push_type" id="push_type">
                    @foreach($push_types as $id => $d)
                        <option value="{{ $id }}" {{ $id == old('push_type', '') ? 'selected' : '' }}>{{ $d }}</option>
                    @endforeach
                </select>
                @if($errors->has('push_type'))
                    <span class="text-danger">{{ $errors->first('push_type') }}</span>
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


@section('scripts')
<script>
   Dropzone.options.iconDropzone = {
    url: '{{ route('admin.pushes.storeMedia') }}',
    maxFilesize: 20, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 20,
      width: 4000,
      height: 4000
    },
    success: function (file, response) {
      $('form').find('input[name="icon"]').remove()
      $('form').append('<input type="hidden" name="icon" value="' + response.name + '">')
    },
    removedfile: function (file) {
      file.previewElement.remove()
      if (file.status !== 'error') {
        $('form').find('input[name="icon"]').remove()
        this.options.maxFiles = this.options.maxFiles + 1
      }
    },
    init: function () {
        @if(isset($push) && $push->icon)
            var file = {!! json_encode($push->icon) !!}
            this.options.addedfile.call(this, file)
            this.options.thumbnail.call(this, file, '{{ $push->icon->getUrl('thumb') }}')
            file.previewElement.classList.add('dz-complete')
            $('form').append('<input type="hidden" name="icon" value="' + file.file_name + '">')
            this.options.maxFiles = this.options.maxFiles - 1
        @endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}


Dropzone.options.imageDropzone = {
    url: '{{ route('admin.pushes.storeMedia') }}',
    maxFilesize: 20, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
        size: 20,
        width: 4096,
        height: 4096
    },
    success: function (file, response) {
        $('form').find('input[name="image"]').remove()
        $('form').append('<input type="hidden" name="image" value="' + response.name + '">')
    },
    removedfile: function (file) {
        file.previewElement.remove()
        if (file.status !== 'error') {
            $('form').find('input[name="image"]').remove()
            this.options.maxFiles = this.options.maxFiles + 1
        }
    },
    init: function () {
        @if(isset($push) && $push->image)
            var file = {!! json_encode($push->image) !!}
            this.options.addedfile.call(this, file)
            this.options.thumbnail.call(this, file, '{{ $push->image->getUrl('thumb') }}')
            file.previewElement.classList.add('dz-complete')
            $('form').append('<input type="hidden" name="image" value="' + file.file_name + '">')
            this.options.maxFiles = this.options.maxFiles - 1
        @endif
    },
    error: function (file, response) {
        if ($.type(response) === 'string') {
            var message = response //dropzone sends it's own error messages in string
        } else {
            var message = response.errors.file
        }
        file.previewElement.classList.add('dz-error')
        _ref = file.previewElement.querySelectorAll('[data-dz-errormessage]')
        _results = []
        for (_i = 0, _len = _ref.length; _i < _len; _i++) {
            node = _ref[_i]
            _results.push(node.textContent = message)
        }

        return _results
    }
}
</script>
@endsection