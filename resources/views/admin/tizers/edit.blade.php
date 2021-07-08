@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.tizer.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.tizers.update", [$tizer->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="image">{{ trans('cruds.tizer.fields.image') }}</label>
                <div class="needsclick dropzone {{ $errors->has('image') ? 'is-invalid' : '' }}" id="image-dropzone">
                </div>
                @if($errors->has('image'))
                    <span class="text-danger">{{ $errors->first('image') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.tizer.fields.image_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="title">{{ trans('cruds.tizer.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $tizer->title) }}">
                @if($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.tizer.fields.title_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="ext_link">{{ trans('cruds.tizer.fields.ext_link') }}</label>
                <input class="form-control {{ $errors->has('ext_link') ? 'is-invalid' : '' }}" type="text" name="ext_link" id="ext_link" value="{{ old('ext_link', $tizer->ext_link) }}" required>
                @if($errors->has('ext_link'))
                    <span class="text-danger">{{ $errors->first('ext_link') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.tizer.fields.ext_link_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="desc">{{ trans('cruds.tizer.fields.desc') }}</label>
                <input class="form-control {{ $errors->has('desc') ? 'is-invalid' : '' }}" type="text" name="desc" id="desc" value="{{ old('desc', $tizer->desc) }}">
                @if($errors->has('desc'))
                    <span class="text-danger">{{ $errors->first('desc') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.tizer.fields.desc_helper') }}</span>
            </div>


            <div class="form-group">
                <label class="required" for="country">{{ trans('cruds.tizer.fields.country') }}</label>
               
                <select class="form-control select2 {{ $errors->has('country') ? 'is-invalid' : '' }}" name="country" id="country" required>
                    <option value="">-- Необходимо выбрать --</option>
                    @foreach($countries as $cat)
                        <option value="{{ $cat }}" {{ $tizer->country === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                @if($errors->has('country'))
                    <span class="text-danger">{{ $errors->first('country') }}</span>
                @endif                
            </div>

            
            <div class="form-group">
                <label class="required" for="categories">Категории (мультивыбор)</label>
                <select class="form-control select2 {{ $errors->has('cats') ? 'is-invalid' : '' }}" name="cats[]" id="cats" multiple required>
                    <option value="">-- Необходимо выбрать --</option>
                    @foreach($categories as $id => $cat)
                        <option value="{{ $id }}" {{ (in_array($id, old('cats', [])) || in_array($id, $tizer->cats) ) ? 'selected' : '' }}>{{ $cat }}</option>
                    @endforeach
                </select>
                @if($errors->has('category_id'))
                    <span class="text-danger">{{ $errors->first('category_id') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label for="aprove">{{ trans('cruds.tizer.fields.aprove') }}</label>
                <input class="form-control {{ $errors->has('aprove') ? 'is-invalid' : '' }}" type="number" name="aprove" id="aprove" value="{{ old('aprove', $tizer->aprove) }}" step="0.01">
                @if($errors->has('aprove'))
                    <span class="text-danger">{{ $errors->first('aprove') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.tizer.fields.aprove_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="price">{{ trans('cruds.tizer.fields.price') }}</label>
                <input class="form-control {{ $errors->has('price') ? 'is-invalid' : '' }}" name="price" id="price" value="{{ old('price', $tizer->price) }}" step="0.01">
                @if($errors->has('price'))
                    <span class="text-danger">{{ $errors->first('price') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.tizer.fields.price_helper') }}</span>
            </div>
            <div class="form-group">
                <input type="checkbox" value="1" name="is_razgon" id="iz_razgon" {{ $tizer->is_razgon ? 'checked="checked"' : '' }} />
                <label for="is_razgon">Учавствовать в разгоне, если есть недобор просмотров в рамках шаблона</label>                
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