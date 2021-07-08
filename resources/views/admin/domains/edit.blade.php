@extends('layouts.admin')
@section('content')

<!--link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.1/css/bootstrap-colorpicker.min.css" rel="stylesheet"-->
<!--script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.1/js/bootstrap-colorpicker.min.js"></script-->  
<style>
.input-group-addon.color i{
    width: 38px;
    height: 38px;
    border: 1px gray solid;
    border-radius: 5px;
}
</style>

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} домен
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.domains.update", [$domain->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="title">Домен</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $domain->title) }}" required>
                @if($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label class="required" for="template_id">Шаблон по умолчанию</label>
                <select class="form-control select2 {{ $errors->has('template_id') ? 'is-invalid' : '' }}" name="template_id" id="template_id" required>
                    <option value="">-- Необходимо выбрать --</option>
                    @foreach($templates as $id => $tem)
                        <option value="{{ $id }}" {{ ( old('template_id', $domain->template_id) == $id  ) ? 'selected' : '' }}>{{ $tem }}</option>
                    @endforeach
                </select>
                @if($errors ->has('template_id'))
                    <span class="text-danger">{{ $errors->first('template_id') }}</span>
                @endif
            </div>

            <div class="form-group">
                <label class="required" for="type">Тип сайта</label>
                <select class="form-control select2 {{ $errors->has('type') ? 'is-invalid' : '' }}" name="type" id="type" required>
                    @foreach($types as $id => $t )
                        <option value="{{ $id }}" {{ ( old('type', $domain->type) == $id  ) ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>                
            </div>

            @if($domain->isKloaka())
            <fieldset>
                <legend>Настройки Клоаки - Показывается белая витрина, кроме:</legend>
                <div class="form-row">
                    <div class="form-group col-md-4">
                        <label for="site_news_no_geo">Кроме страны:</label>                
                        <select class="form-control select2 {{ $errors->has('site_news_no_geo') ? 'is-invalid' : '' }}" name="site_news_no_geo" id="site_news_no_geo">
                            <option value="">-- Необходимо выбрать --</option>
                            @foreach($countries as $cat)
                                <option value="{{ $cat }}" {{ $domain->site_news_no_geo === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('site_news_no_geo'))
                            <span class="text-danger">{{ $errors->first('site_news_no_geo') }}</span>
                        @endif
                    </div>
                    <div class="form-group col-md-4">
                        <label for="lang">Кроме языка (например 'ru'):</label>               
                        <input class="form-control {{ $errors->has('lang') ? 'is-invalid' : '' }}" type="text" name="lang" id="lang" value="{{ old('lang', $domain->lang) }}">
                        @if($errors->has('lang'))
                            <span class="text-danger">{{ $errors->first('lang') }}</span>
                        @endif
                        <span class="help-block">Смотреть то как нас видят <a href="https://informerspro.ru/testpeople">тут</a></span>
                    </div>
                    <div class="form-group col-md-4">
                        <label for="site_news_device">кроме устройств:</label>                
                        <select class="form-control select2 {{ $errors->has('site_news_device') ? 'is-invalid' : '' }}" name="site_news_device" id="site_news_device">
                            <option value="">-- Необходимо выбрать --</option>
                            @foreach($devices as $cat)
                                <option value="{{ $cat }}" {{ $domain->site_news_device === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        @if($errors->has('site_news_device'))
                            <span class="text-danger">{{ $errors->first('site_news_device') }}</span>
                        @endif 
                    </div>
                </div> 
                
                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="need_views">Если кликов </label>
                        <input class="form-control {{ $errors->has('need_views') ? 'is-invalid' : '' }}" type="text" name="need_views" id="need_views" value="{{ old('need_views', $domain->need_views) }}">
                        @if($errors->has('need_views'))
                            <span class="text-danger">{{ $errors->first('need_views') }}</span>
                        @endif
                    </div>

                    <div class="form-group col-md-6">
                        <label for="last_hours">За период (в часах)</label>
                        <input class="form-control {{ $errors->has('last_hours') ? 'is-invalid' : '' }}" type="text" name="last_hours" id="last_hours" value="{{ old('last_hours', $domain->last_hours) }}">
                        @if($errors->has('last_hours'))
                            <span class="text-danger">{{ $errors->first('last_hours') }}</span>
                        @endif
                    </div>
                </div>

            </fieldset>
            @endif

            @if($domain->type != 'landing')
            <fieldset >
                <legend>Настройка внешнего вида домена:</legend>
                <div class="form-group">
                    <label class="required" for="site_name">Название сайта</label>
                    <input class="form-control {{ $errors->has('site_name') ? 'is-invalid' : '' }}" type="text" name="site_name" id="site_name" value="{{ old('site_name', $domain->site_name) }}" required>
                    @if($errors->has('site_name'))
                        <span class="text-danger">{{ $errors->first('site_name') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="seo_title">Заголовок TITLE</label>
                    <input class="form-control {{ $errors->has('seo_title') ? 'is-invalid' : '' }}" type="text" name="seo_title" id="seo_title" value="{{ old('seo_title', $domain->seo_title) }}">
                    @if($errors->has('seo_title'))
                        <span class="text-danger">{{ $errors->first('seo_title') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="image">Логотип</label>
                    <div class="needsclick dropzone {{ $errors->has('image') ? 'is-invalid' : '' }}" id="image-dropzone">
                    </div>
                    @if($errors->has('image'))
                        <span class="text-danger">{{ $errors->first('image') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="banner">Баннер</label>
                    <div class="needsclick dropzone {{ $errors->has('banner') ? 'is-invalid' : '' }}" id="banner-dropzone">
                    </div>
                    @if($errors->has('banner'))
                        <span class="text-danger">{{ $errors->first('banner') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="banner_url">URL Баннера</label>
                    <input class="form-control {{ $errors->has('banner_url') ? 'is-invalid' : '' }}" type="text" name="banner_url" id="banner_url" value="{{ old('banner_url', $domain->banner_url) }}" >
                    @if($errors->has('banner_url'))
                        <span class="text-danger">{{ $errors->first('banner_url') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="info1">Инфо 1 картинка </label>
                    <div class="needsclick dropzone {{ $errors->has('info1') ? 'is-invalid' : '' }}" id="info1-dropzone">
                    </div>
                    @if($errors->has('info1'))
                        <span class="text-danger">{{ $errors->first('info1') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="info_txt_1">ТЕКСТ Инфо 1</label>
                    <input class="form-control {{ $errors->has('info_txt_1') ? 'is-invalid' : '' }}" type="text" name="info_txt_1" id="info_txt_1" value="{{ old('info_txt_1', $domain->info_txt_1) }}" >
                    @if($errors->has('info_txt_1'))
                        <span class="text-danger">{{ $errors->first('info_txt_1') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="info2">Инфо 2 картинка </label>
                    <div class="needsclick dropzone {{ $errors->has('info2') ? 'is-invalid' : '' }}" id="info2-dropzone">
                    </div>
                    @if($errors->has('info2'))
                        <span class="text-danger">{{ $errors->first('info2') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="info_txt_2">ТЕКСТ Инфо 2</label>
                    <input class="form-control {{ $errors->has('info_txt_2') ? 'is-invalid' : '' }}" type="text" name="info_txt_2" id="info_txt_2" value="{{ old('info_txt_2', $domain->info_txt_2) }}" >
                    @if($errors->has('info_txt_2'))
                        <span class="text-danger">{{ $errors->first('info_txt_2') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="info3">Инфо 3 картинка </label>
                    <div class="needsclick dropzone {{ $errors->has('info3') ? 'is-invalid' : '' }}" id="info3-dropzone">
                    </div>
                    @if($errors->has('info3'))
                        <span class="text-danger">{{ $errors->first('info3') }}</span>
                    @endif
                </div>
                <div class="form-group">
                    <label for="info_txt_3">ТЕКСТ Инфо 3</label>
                    <input class="form-control {{ $errors->has('info_txt_3') ? 'is-invalid' : '' }}" type="text" name="info_txt_3" id="info_txt_3" value="{{ old('info_txt_3', $domain->info_txt_3) }}" >
                    @if($errors->has('info_txt_3'))
                        <span class="text-danger">{{ $errors->first('info_txt_3') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="user_accept">Пользовательское соглашение</label>
                    <textarea class="form-control ckeditor {{ $errors->has('user_accept') ? 'is-invalid' : '' }}" name="user_accept" id="user_accept">{!! old('user_accept', $domain->user_accept) !!}</textarea>
                    @if($errors->has('user_accept'))
                        <span class="text-danger">{{ $errors->first('user_accept') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="coockie_text">Куки текст в всплывашке</label>
                    <textarea class="form-control ckeditor {{ $errors->has('coockie_text') ? 'is-invalid' : '' }}" name="coockie_text" id="coockie_text">{!! old('coockie_text', $domain->coockie_text) !!}</textarea>
                    @if($errors->has('coockie_text'))
                        <span class="text-danger">{{ $errors->first('coockie_text') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="coockie_page_text">Куки текст на отдельной странице</label>
                    <textarea class="form-control ckeditor {{ $errors->has('coockie_page_text') ? 'is-invalid' : '' }}" name="coockie_page_text" id="coockie_page_text">{!! old('coockie_page_text', $domain->coockie_page_text) !!}</textarea>
                    @if($errors->has('coockie_page_text'))
                        <span class="text-danger">{{ $errors->first('coockie_page_text') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="coockie_button">Текст кнопки в Куки всплывашке</label>
                    <input class="form-control {{ $errors->has('coockie_button') ? 'is-invalid' : '' }}" type="text" name="coockie_button" id="coockie_button" value="{{ old('coockie_button', $domain->coockie_button) }}" >
                    @if($errors->has('coockie_button'))
                        <span class="text-danger">{{ $errors->first('coockie_button') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="contacts">Контакты</label>
                    <textarea class="form-control ckeditor {{ $errors->has('contacts') ? 'is-invalid' : '' }}" name="contacts" id="contacts">{!! old('contacts', $domain->contacts) !!}</textarea>
                    @if($errors->has('contacts'))
                        <span class="text-danger">{{ $errors->first('contacts') }}</span>
                    @endif
                </div>

                <div class="form-group">
                    <label for="menu_items">Пункты меню</label>
                    <input class="form-control {{ $errors->has('menu_items') ? 'is-invalid' : '' }}" type="text" name="menu_items" id="menu_items" value="{{ old('menu_items', $domain->menu_items) }}" >
                    @if($errors->has('menu_items'))
                        <span class="text-danger">{{ $errors->first('menu_items') }}</span>
                    @endif
                </div>

                <div class="form-row">
                    <div class="form-group col-md-3">            
                        <label for="color1">Цвет шапки 1</label>
                        <div class="input-group colorpicker colorpicker-component"> 
                            <input type="text" id='color1' name="color1" value="{{ old('color1', $domain->color1) }}" class="form-control" /> 
                            <span class="input-group-addon color"><i></i></span> 
                        </div>
                    </div>

                    <div class="form-group col-md-3">            
                        <label for="color2">Цвет текста шапки 1</label>
                        <div class="input-group colorpicker colorpicker-component"> 
                            <input type="text" id='color2' name="color2" value="{{ old('color2', $domain->color2) }}" class="form-control" /> 
                            <span class="input-group-addon color"><i></i></span> 
                        </div>
                    </div>

                    <div class="form-group col-md-3">            
                        <label for="color3">Цвет шапки 2</label>
                        <div class="input-group colorpicker colorpicker-component"> 
                            <input type="text" id='color3' name="color3" value="{{ old('color3', $domain->color3) }}" class="form-control" /> 
                            <span class="input-group-addon color"><i></i></span> 
                        </div>
                    </div>

                    <div class="form-group col-md-3">            
                        <label for="color4">Цвет текста шапки 2</label>
                        <div class="input-group colorpicker colorpicker-component"> 
                            <input type="text" id='color4' name="color4" value="{{ old('color4', $domain->color4) }}" class="form-control" /> 
                            <span class="input-group-addon color"><i></i></span> 
                        </div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="head_script">{{ trans('cruds.template.fields.head_script') }}</label>
                        <textarea class="form-control {{ $errors->has('head_script') ? 'is-invalid' : '' }}" name="head_script" id="head_script">{{ old('head_script', $domain->head_script) }}</textarea>
                        @if($errors->has('head_script'))
                            <span class="text-danger">{{ $errors->first('head_script') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.template.fields.head_script_helper') }}</span>
                    </div>
                    <div class="form-group col-md-6">
                        <label for="body_script">{{ trans('cruds.template.fields.body_script') }}</label>
                        <textarea class="form-control {{ $errors->has('body_script') ? 'is-invalid' : '' }}" name="body_script" id="body_script">{{ old('body_script', $domain->body_script) }}</textarea>
                        @if($errors->has('body_script'))
                            <span class="text-danger">{{ $errors->first('body_script') }}</span>
                        @endif
                        <span class="help-block">{{ trans('cruds.template.fields.body_script_helper') }}</span>
                    </div>                             
                </div>
            </fieldset>
            @endif


            @if($domain->type == 'landing')
            <fieldset >
                <legend>Чёрно-белый лендинг:</legend>
                <div class="form-row">

                    <div class="form-group col-md-4">
                        <label for="white_url">Ссылка чёрный лендинг (редирект)</label>
                        <input class="form-control {{ $errors->has('white_url') ? 'is-invalid' : '' }}" type="text" name="white_url" id="white_url" value="{{ old('white_url', $domain->white_url) }}" >
                        @if($errors->has('white_url'))
                            <span class="text-danger">{{ $errors->first('white_url') }}</span>
                        @endif
                    </div>

                    <div class="form-group col-md-4">
                        <label for="white_files">Закачать архив в белый лендинг</label>
                        <input class="form-control {{ $errors->has('white_files') ? 'is-invalid' : '' }}" type="file" multiple name="white_files[]" id="white_files" >
                        @if($errors->has('white_files'))
                            <span class="text-danger">{{ $errors->first('white_files') }}</span>
                        @endif
                        <span class="help-block">Обязательно должен быть index.html, в котором все адреса картинок в тойже директории, что и он</span>

                    </div>
                </div>
            </fieldset>
            @endif


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
    url: '{{ route('admin.domains.storeMedia') }}',
    maxFilesize: 2, // MB
    acceptedFiles: '.jpeg,.jpg,.png,.gif',
    maxFiles: 1,
    addRemoveLinks: true,
    headers: {
      'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
    params: {
      size: 2,
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
@if(isset($domain) && $domain->image)
      var file = {!! json_encode($domain->image) !!}
          this.options.addedfile.call(this, file)
      this.options.thumbnail.call(this, file, '{{ $domain->image->getUrl('thumb') }}')
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

var bannerUrl = false
var bannerValue = false
@if(isset($domain) && $domain->banner)
    var bannerValue = {!! json_encode($domain->banner) !!};
    var bannerUrl = '{{ $domain->banner->getUrl('thumb') }}';
@endif

Dropzone.options.bannerDropzone = getDropzoneOptions('banner', bannerValue, bannerUrl);

var info1Url = false
var info1Value = false
@if(isset($domain) && $domain->info1)
    var info1Value = {!! json_encode($domain->info1) !!};
    var info1Url = '{{ $domain->info1->getUrl('info') }}';
@endif

Dropzone.options.info1Dropzone = getDropzoneOptions('info1', info1Value, info1Url);

var info2Url = false
var info2Value = false
@if(isset($domain) && $domain->info2)
    var info2Value = {!! json_encode($domain->info2) !!};
    var info2Url = '{{ $domain->info2->getUrl('info') }}';
@endif

Dropzone.options.info2Dropzone = getDropzoneOptions('info2', info2Value, info2Url);

var info3Url = false
var info3Value = false
@if(isset($domain) && $domain->info3)
    var info3Value = {!! json_encode($domain->info3) !!};
    var info3Url = '{{ $domain->info3->getUrl('info') }}';
@endif

Dropzone.options.info3Dropzone = getDropzoneOptions('info3', info3Value, info3Url);


function getDropzoneOptions(media, value, url) {
    return {
        url: '{{ route('admin.domains.storeMedia') }}',
        maxFilesize: 2, // MB
        acceptedFiles: '.jpeg,.jpg,.png,.gif',
        maxFiles: 1,
        addRemoveLinks: true,
        headers: {
        'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        params: {
        size: 2,
        width: 4096,
        height: 4096
        },
        success: function (file, response) {
        $('form').find('input[name="' + media + '"]').remove()
        $('form').append('<input type="hidden" name="' + media + '" value="' + response.name + '">')
        },
        removedfile: function (file) {
        file.previewElement.remove()
        if (file.status !== 'error') {
            $('form').find('input[name="' + media + '"]').remove()
            this.options.maxFiles = this.options.maxFiles + 1
        }
        },
        init: function () {
            if ( value ) {
                var file = value;
                this.options.addedfile.call(this, file)
                this.options.thumbnail.call(this, file, url)
                file.previewElement.classList.add('dz-complete')
                $('form').append('<input type="hidden" name="' + media + '" value="' + file.file_name + '">')
                this.options.maxFiles = this.options.maxFiles - 1
            }      
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
}

$('.colorpicker').colorpicker();
</script>

@endsection