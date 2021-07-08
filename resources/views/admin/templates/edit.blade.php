@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.template.title_singular') }}
    </div>
    


    <div class="card-body">
    <br>
    <b>Ссылки:</b><br>
    Витрина с новостями <input type="text" style="width: 600px;" value="https://<?=$_SERVER['SERVER_NAME']?>/?template_id=<?=$template->id?>"> <br>
    Витрина с короткой новостью<input type="text" style="width: 600px;" value="https://<?=$_SERVER['SERVER_NAME']?>/news_short/%ID_NEWS%?template_id=<?=$template->id?>">  <br>
    Витрина с полностью новостью<input type="text" style="width: 600px;" value="https://<?=$_SERVER['SERVER_NAME']?>/news/%ID_NEWS%?template_id=<?=$template->id?>">  <br>
    Витрина с тизерами<input type="text" style="width: 600px;" value="https://<?=$_SERVER['SERVER_NAME']?>/tizers?template_id=<?=$template->id?>">  <br>

        <form method="POST" action="{{ route("admin.templates.update", [$template->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="title">{{ trans('cruds.template.fields.title') }}</label>
                <input class="form-control {{ $errors->has('title') ? 'is-invalid' : '' }}" type="text" name="title" id="title" value="{{ old('title', $template->title) }}" required>
                @if($errors->has('title'))
                    <span class="text-danger">{{ $errors->first('title') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.template.fields.title_helper') }}</span>
            </div>

            <div class="form-group">
                <label class="required" for="stat_days">{{ trans('cruds.template.fields.stat_days') }}</label>
                <input class="form-control {{ $errors->has('stat_days') ? 'is-invalid' : '' }}" type="text" name="stat_days" id="stat_days" value="{{ old('stat_days', $template->stat_days) }}" required>
                @if($errors->has('stat_days'))
                    <span class="text-danger">{{ $errors->first('stat_days') }}</span>
                @endif
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="utm_name">{{ trans('cruds.template.fields.utm_name') }}</label>
                    <input class="form-control {{ $errors->has('utm_name') ? 'is-invalid' : '' }}" type="text" name="utm_name" id="utm_name" value="{{ old('utm_name', $template->utm_name) }}">
                    @if($errors->has('utm_name'))
                        <span class="text-danger">{{ $errors->first('utm_name') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.template.fields.utm_name_helper') }}</span>
                </div>

                <div class="form-group col-md-6">
                    <label for="utm_value">{{ trans('cruds.template.fields.utm_value') }}</label>
                    <input class="form-control {{ $errors->has('utm_value') ? 'is-invalid' : '' }}" type="text" name="utm_value" id="utm_value" value="{{ old('utm_value', $template->utm_value) }}">
                    @if($errors->has('utm_value'))
                        <span class="text-danger">{{ $errors->first('utm_value') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.template.fields.utm_value_helper') }}</span>
                </div>
            </div>


            <div class="form-group">
                <label class="required" for="categories">{{ trans('cruds.template.fields.categories') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('categories') ? 'is-invalid' : '' }}" name="categories[]" id="categories" multiple required>
                    @foreach($categories as $id => $categories)
                        <option value="{{ $id }}" {{ (in_array($id, old('categories', [])) || $template->categories->contains($id)) ? 'selected' : '' }}>{{ $categories }}</option>
                    @endforeach
                </select>
                @if($errors->has('categories'))
                    <span class="text-danger">{{ $errors->first('categories') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.template.fields.categories_helper') }}</span>
            </div>

            <div class="form-row">
                <!--div class="form-group col-md-3">
                    <label for="tizer_boost_geo">{{ trans('cruds.template.fields.tizer_boost_geo') }}</label>
                    <input class="form-control {{ $errors->has('tizer_boost_geo') ? 'is-invalid' : '' }}" type="text" name="tizer_boost_geo" id="tizer_boost_geo" value="{{ old('tizer_boost_geo', $template->tizer_boost_geo) }}">
                    @if($errors->has('tizer_boost_geo'))
                        <span class="text-danger">{{ $errors->first('tizer_boost_geo') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.template.fields.tizer_boost_geo_helper') }}</span>
                </div-->
                <div class="form-group col-md-3">
                    <label for="tizer_boost_val">Разгон тизера - до колличества просмотров</label>
                    <input class="form-control {{ $errors->has('tizer_boost_val') ? 'is-invalid' : '' }}" type="text" name="tizer_boost_val" id="tizer_boost_val" value="{{ old('tizer_boost_val', $template->tizer_boost_val) }}">
                    @if($errors->has('tizer_boost_val'))
                        <span class="text-danger">{{ $errors->first('tizer_boost_val') }}</span>
                    @endif
                </div>
            
                <!--div class="form-group col-md-3">
                    <label for="news_boost_geo">{{ trans('cruds.template.fields.news_boost_geo') }}</label>
                    <input class="form-control {{ $errors->has('news_boost_geo') ? 'is-invalid' : '' }}" type="text" name="news_boost_geo" id="news_boost_geo" value="{{ old('news_boost_geo', $template->news_boost_geo) }}">
                    @if($errors->has('news_boost_geo'))
                        <span class="text-danger">{{ $errors->first('news_boost_geo') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.template.fields.news_boost_geo_helper') }}</span>
                </div-->
                <div class="form-group col-md-3">
                    <label for="news_boost_val">Разгон новости - до колличества просмотров</label>
                    <input class="form-control {{ $errors->has('news_boost_val') ? 'is-invalid' : '' }}" type="text" name="news_boost_val" id="news_boost_val" value="{{ old('news_boost_val', $template->news_boost_val) }}">
                    @if($errors->has('news_boost_val'))
                        <span class="text-danger">{{ $errors->first('news_boost_val') }}</span>
                    @endif
                </div>
            </div>
            
            
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="head_script">{{ trans('cruds.template.fields.head_script') }}</label>
                    <textarea class="form-control {{ $errors->has('head_script') ? 'is-invalid' : '' }}" name="head_script" id="head_script">{{ old('head_script', $template->head_script) }}</textarea>
                    @if($errors->has('head_script'))
                        <span class="text-danger">{{ $errors->first('head_script') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.template.fields.head_script_helper') }}</span>
                </div>
                <div class="form-group col-md-6">
                    <label for="body_script">{{ trans('cruds.template.fields.body_script') }}</label>
                    <textarea class="form-control {{ $errors->has('body_script') ? 'is-invalid' : '' }}" name="body_script" id="body_script">{{ old('body_script', $template->body_script) }}</textarea>
                    @if($errors->has('body_script'))
                        <span class="text-danger">{{ $errors->first('body_script') }}</span>
                    @endif
                    <span class="help-block">{{ trans('cruds.template.fields.body_script_helper') }}</span>
                </div>  
                <div class="form-group">
                    <input type="checkbox" value="1" name="mix" id="mix" {{ $template->mix ? 'checked="checked"' : '' }} />
                    <label for="is_razgon">Режим 50x50</label>                
                </div>            
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