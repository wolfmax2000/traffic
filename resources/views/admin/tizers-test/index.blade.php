

@extends('layouts.admin')
@section('content')

@can('tizer_test_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.tizers-test.create") }}">
                Добавить тест
            </a>
        </div>
    </div>
@endcan

<div class="card">
    <div class="card-header">
        АБ тестирование
    </div>

    <div class="card-body">
        
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatat datatable-Tizer">
                <thead>
                    <tr>
                        <th>    
                            свойства
                        </th>                    
                        <th>
                            Тестируемые данные
                        </th>
                        <th>
                            Просмотров
                        </th>
                        <th>
                            Статус
                        </th>   
                        <th>
                            Действия
                        </th>
                        
                    </tr>
                </thead>
                <tbody>
                    @foreach($tests as $key => $test)
                        <tr data-entry-id="{{ $test->id }}">                            
                            <td>
                                ID: [{{$test->tizer->id}}]<br>
                                
                                страна: {{ $test->tizer->country}}
                            </td>                                                                                
                            <td>
                                <table>
                                    
                                    <tr>
                                        <td>картинка</td>
                                        <td>заголовок</td>
                                        <td>просмотров</td>
                                        <td>кликов</td>
                                        <td>CTR</td>
                                    </tr>
                                    <tr>
                                        <td>
                                            @if($test->tizer->image)
                                                <a href="{{ $test->tizer->image->getUrl() }}" target="_blank">
                                                    <img src="{{ $test->tizer->image->getUrl('thumb') }}" width="50px" height="50px">
                                                </a>
                                            @endif
                                        </td>
                                        <td>{{ $test->tizer->title }}</td>
                                        <td>{{ $test->views_a }}</td>
                                        <td>{{ $test->click_a }}</td>
                                        <td>@if($test->views_a){{ round($test->click_a/$test->views_a*100,1) }}@endif</td>
                                    </tr>
                                    <tr style="background-color: {{$test->getStatusColor()}};">
                                        <td>
                                            @if($test->image)
                                                <a href="{{ $test->image->getUrl() }}" target="_blank">
                                                    <img src="{{ $test->image->getUrl('thumb') }}" width="50px" height="50px">
                                                </a>
                                            @endif
                                        </td>
                                        <td>{{ $test->title }}</td>
                                        <td>{{ $test->views_b }}</td>
                                        <td>{{ $test->click_b }}</td>
                                        <td>@if($test->views_b){{ round($test->click_b/$test->views_b*100,1) }}@endif</td>
                                    </tr>                                    
                                </table>                            
                            </td>                        
                            <td>
                                {{ $test->views_b + $test->views_a }} / {{ $test->need_views }}
                            </td>
                            <td>
                                @can('tizer_delete')
                                <input data-id="{{$test->id}}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Активно" data-off="Пауза" {{ $test->is_active ? 'checked' : '' }}>
                                @endcan
                            </td>
                            <td>
                                @can('tizer_delete')
                                    <form action="{{ route('admin.tizers-test.destroy', $test->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan   
                                @if (true || $test->getStatusColor()=='green')
                                @can('tizer_delete')
                                    <form action="/admin/tizerstest/applyb" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="id" value="{{ $test->id }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="Примернить B">
                                    </form>
                                @endcan
                                @endif                       
                            </td>
                        </tr>
                        
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>



@endsection
@section('scripts')
@parent
<script>
    $(function () {
        $('.toggle-class').change(function() {
            var is_active = $(this).prop('checked') == true ? 1 : 0; 
            var item_id = $(this).data('id'); 
            
            $.ajax({
                type: "GET",
                dataType: "json",
                url: 'tizerstest/active',
                data: {'is_active': is_active, 'item_id': item_id},
                success: function(data){
                console.log(data.success)
                }
            });
        });
    })

    let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
    $.extend(true, $.fn.dataTable.defaults, {
        order: [[ 1, 'desc' ]],
        pageLength: 100,
    });
    $('.datatable-Tizer:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });

</script>
@endsection


