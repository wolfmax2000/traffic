@extends('layouts.admin')
@section('content')
@can('push_access')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.pushes.create", $push_template->id) }}">
                {{ trans('global.add') }} PUSH
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        Список PUSH кампании {{$push_template->title}}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Template">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            id
                        </th>
                        <th>
                            Заголовок
                        </th>  
                        <th>Просмотров</th>
                        <th>Кликов</th> 
                        <th>Лимит</th>
                        <th>Дата</th>
                        <th>Статус</th>                     
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($pushes as $key => $push)
                        <tr data-entry-id="{{ $push->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $push->id ?? '' }}
                            </td>
                            <td style="display:flex">
                                @if($push->icon)
                                    <img style="display: inline-block" src="{{ $push->icon->getUrl('icon_thumb') }}" width=60>
                                @endif
                                <div style="margin-left: 10px; display: inline-block; float: right">
                                <b>{{ $push->title ?? '' }}</b> <br>
                                {{ $push->desc ?? '' }}         </div>                 
                            </td>
                            <td>
                                {{ $push->views }}
                            </td>
                            <td>
                                {{ $push->clicks }}
                            </td>
                            <td>
                                {{ $push->limit }}
                            </td>
                            <td>
                                <b>{{ $push->geo }}</b>
                                <br>
                                {{ $push->created_at }}
                            </td>
                            <td>
                                {{ $push->status }}
                            </td>
                            <td>

                                @can('push_access')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.pushes.edit', $push->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                    
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.pushes.status', ['push' => $push->id, 'status' => 'process']) }}">
                                        Запуск
                                    </a>

                                    <form action="{{ route('admin.pushes.destroy', $push->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="в архив">
                                    </form>
                                @endcan
                                

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
        console.log($.fn.dataTable.defaults.buttons)
  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('push_access')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.pushes.massDestroy') }}",
    className: 'btn-danger',
    action: function (e, dt, node, config) {
      var ids = $.map(dt.rows({ selected: true }).nodes(), function (entry) {
          return $(entry).data('entry-id')
      });

      if (ids.length === 0) {
        alert('{{ trans('global.datatables.zero_selected') }}')

        return
      }

      if (confirm('{{ trans('global.areYouSure') }}')) {
        $.ajax({
          headers: {'x-csrf-token': _token},
          method: 'POST',
          url: config.url,
          data: { ids: ids, _method: 'DELETE' }})
          .done(function () { location.reload() })
      }
    }
  }
  dtButtons.push(deleteButton)
@endcan

  $.extend(true, $.fn.dataTable.defaults, {
    order: [[ 1, 'desc' ]],
    pageLength: 100,
  });
  $('.datatable-Template:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection