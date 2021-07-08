@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Создание АБ теста
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.tizers-test.store") }}" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label class="required" for="categories">Тизер</label>
                <select class="form-control select2" name="tizer_id" id="tizer_id" required>
                    <option value="">-- Необходимо выбрать --</option>
                    @foreach($tizers as $id => $t)
                        <option value="{{ $t->id }}" {{ old('tizer_id', '') == $t->id ? 'selected' : '' }}> [{{ $t->id }}] - {{ $t->title }}</option>
                    @endforeach
                </select>
                @if($errors->has('tizer_id'))
                    <span class="text-danger">{{ $errors->first('tizer_id') }}</span>
                @endif                
            </div>

            <div class="form-group">
                <label class="required" for="need_views">Колличество показов</label>
                <input class="form-control {{ $errors->has('need_views') ? 'is-invalid' : '' }}" type="number" name="need_views" id="need_views" value="{{ old('need_views', '1000') }}" required>
                @if($errors->has('need_views'))
                    <span class="text-danger">{{ $errors->first('need_views') }}</span>
                @endif
                <span class="help-block"></span>
            </div>


            <div class="form-group">
                <label for="image">Альтернативная картинка</label>
                <div class="needsclick dropzone {{ $errors->has('image') ? 'is-invalid' : '' }}" id="image-dropzone">
                </div>
                @if($errors->has('image'))
                    <span class="text-danger">{{ $errors->first('image') }}</span>
                @endif
                <span class="help-block"></span>
            </div>

            <div class="form-group">
                <label for="title">Альтернативный Заголовок</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', '') }}" >
                @if($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
                <span class="help-block"></span>
            </div>

            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    Добавить в очередь
                </button>
            </div>
        </form>
    </div>
</div>
@endsection


@section('scripts')
<script>
    Dropzone.options.imageDropzone = {
    url: '{{ route('admin.tizers.storeMedia') }}',
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
@if(isset($tizer) && $tizer->image)
      var file = {!! json_encode($tizer->image) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, '{{ $tizer->image->getUrl('thumb') }}')
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