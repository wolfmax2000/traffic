@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Статистика
    </div>

    <div class="card-body">
        
        <form id="sid_form">
            <div class="row">
            <div class="col-md-6">
                <select name='current_sid' id="current_sid" class="select2">
                    <option></option>
                @foreach($sids as  $sid)
                    <option value="{{$sid->id}}" @if( $current_sid==$sid->id ) selected=selected @endif>{{$sid->name}}</option>
                @endforeach
                </select>
            </div>
            <div class="col-md-6">
                <select name='current_site' id="current_site" class="select2">
                    <option></option>
                @foreach($sites as  $site)
                    <option value="{{$site->id}}" @if( $current_site==$site->id ) selected=selected @endif>{{$site->name}}</option>
                @endforeach
                </select>
            </div>
            </div>
        </form>
        <br>
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable-Tizer">
                <thead>
                    <tr>
                        <th>
                            Site
                        </th>
                        <th>
                            Sid
                        </th>  
                        <th>
                            Посещений
                        </th> 
                        <th>
                            Уников
                        </th>
                        <th>
                            Среднее время (сек)
                        </th> 
                        <th>
                            Загрузок страницы новости
                        </th> 
                        <th>
                            Кликов по тизерам
                        </th>  
                        <th>
                            Пробив %
                        </th>  
                        <th>
                            Отказ %
                        </th>                   
                    </tr>
                </thead>
                <tbody>
                    @foreach($stats as $key => $stat)
                        <tr>
                            <td>
                                {{ $stat->site_name }}
                            </td>                           
                            <td>
                                {{ $stat->sid_name }}
                            </td>
                            <td>
                                {{ $stat->cnt }}
                            </td>
                            <td>
                                {{ $stat->uniq }}
                            </td>
                            <td>
                                {{ round($stat->avg_time)}}
                            </td>
                            <td>
                                {{ $stat->views }}
                            </td>
                            <td>
                                {{ $stat->clicks }}
                            </td>

                            <td>
                                {{ $stat->views > 0 ? round($stat->clicks/$stat->views*100) : 0 }}
                            </td>
                            <td>
                                {{ $stat->cnt > 0 ? round($stat->otkaz/$stat->cnt*100) : 0 }}
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
        $('#current_sid, #current_site').on('change' , function(e) {
            form = $("#sid_form");
            form[0].submit()
        });

    });


    $.extend(true, $.fn.dataTable.defaults, {
        order: [[ 1, 'desc' ]],
        pageLength: 100,
    });
    $('.datatable-Tizer:not(.ajaxTable)').DataTable({  })

</script>
@endsection