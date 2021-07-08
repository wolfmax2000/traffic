@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.tizer.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.tizers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.tizer.fields.id') }}
                        </th>
                        <td>
                            {{ $tizer->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tizer.fields.image') }}
                        </th>
                        <td>
                            @if($tizer->image)
                                <a href="{{ $tizer->image->getUrl() }}" target="_blank">
                                    <img src="{{ $tizer->image->getUrl('thumb') }}" width="50px" height="50px">
                                </a>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tizer.fields.title') }}
                        </th>
                        <td>
                            {{ $tizer->title }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tizer.fields.desc') }}
                        </th>
                        <td>
                            {{ $tizer->desc }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tizer.fields.views') }}
                        </th>
                        <td>
                            {{ $tizer->views }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tizer.fields.clicks') }}
                        </th>
                        <td>
                            {{ $tizer->clicks }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tizer.fields.aprove') }}
                        </th>
                        <td>
                            {{ $tizer->aprove }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.tizer.fields.price') }}
                        </th>
                        <td>
                            {{ $tizer->price }}
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.tizers.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection