@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Подписчики
    </div>

    <div class="card-body">

        <form id="templ_form">
            <div class="row">
                <div class="col-md-6">
                    <select name='current_domain' id="current_domain" class="select2">
                    @foreach($domains as $d)
                        <option value="{{$d}}" @if( $current_domain==$d ) selected=selected @endif>{{$d}}</option>
                    @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <select name='current_sid8' id="current_sid8" class="select2">
                    @foreach($sid8s as  $s)
                        <option value="{{$s}}" @if( $current_sid8==$s) selected=selected @endif>{{$s}}</option>
                    @endforeach
                    </select>
                </div>
            </div>
        </form>
<br>
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Template">
                <thead>
                    <tr>
                        <th width="10">
                        </th>
                        <th>id</th>
                        <th>Страна</th>  
                        <th>Город</th>
                        <th>С мобильника?</th>
                        <th>Домен</th>
                        <th>Sid8</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($clients as $key => $cli)
                        <tr data-entry-id="{{ $cli->id }}">
                            <td></td>
                            <td>
                                {{ $cli->id ?? '' }}
                            </td>
                            <td>
                                {{ $cli->country }}
                            </td>
                            <td>
                                {{ $cli->city }}
                            </td>
                            <td>
                                {{ $cli->isMobil }}
                            </td>
                            <td>
                                {{ $cli->domen }}
                            </td>
                            <td>
                                {{ $cli->sid8 }}
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
        $('#current_domain, #current_sid8').on('change' , function(e) {
            form = $("#templ_form");
            form[0].submit()
        });

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