@extends('layouts.admin')
@section('content')
@can('tizer_create')
    <div style="margin-bottom: 10px;" class="row">
        <div class="col-lg-12">
            <a class="btn btn-success" href="{{ route("admin.tizers.create") }}">
                {{ trans('global.add') }} {{ trans('cruds.tizer.title_singular') }}
            </a>
        </div>
    </div>
@endcan
<div class="card">
    <div class="card-header">
        {{ trans('cruds.tizer.title_singular') }} {{ trans('global.list') }}
    </div>

    <div class="card-body">
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatable datatable-Tizer">
                <thead>
                    <tr>
                        <th width="10">

                        </th>
                        <th>
                            {{ trans('cruds.tizer.fields.id') }}
                        </th>
                        <th>
                            {{ trans('cruds.tizer.fields.image') }}
                        </th>
                        <th>
                            {{ trans('cruds.tizer.fields.title') }}
                        </th>
                        <th>
                            Гео
                        </th>
                        <!--th>
                            {{ trans('cruds.tizer.fields.desc') }}
                        </th-->
                        <th>
                            {{ trans('cruds.tizer.fields.aprove') }}
                        </th>
                        <th>
                            {{ trans('cruds.tizer.fields.price') }}
                        </th>
                        <th>
                            Статус
                        </th>   
                        <th>
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($tizers as $key => $tizer)
                        <tr data-entry-id="{{ $tizer->id }}">
                            <td>

                            </td>
                            <td>
                                {{ $tizer->id ?? '' }}
                            </td>
                            <td>
                                @if($tizer->image)
                                    <a href="{{ $tizer->image->getUrl() }}" target="_blank">
                                        <img src="{{ $tizer->image->getUrl('thumb') }}" width="50px" height="50px">
                                    </a>
                                @endif
                            </td>
                            <td>
                                {{ $tizer->title ?? '' }}
                            </td>
                            <td>
                                {{ $tizer->country ?? '' }}
                            </td>
                            <!--td>
                                {{ $tizer->desc ?? '' }}
                            </td-->
                            <td>
                                {{ $tizer->aprove ?? '' }}
                            </td>
                            <td>
                                {{ $tizer->price ?? '' }}
                            </td>
                            <td>
                                @can('tizer_delete')
                                <input data-id="{{$tizer->id}}" class="toggle-class" type="checkbox" data-onstyle="success" data-offstyle="danger" data-toggle="toggle" data-on="Активно" data-off="Пауза" {{ $tizer->is_active ? 'checked' : '' }}>
                                @endcan
                            </td>
                            <td>
                                @can('tizer_edit')
                                    <a class="btn btn-xs btn-info" href="{{ route('admin.tizers.edit', $tizer->id) }}">
                                        {{ trans('global.edit') }}
                                    </a>
                                @endcan

                                @can('tizer_delete')
                                    <form action="{{ route('admin.tizers.destroy', $tizer->id) }}" method="POST" onsubmit="return confirm('{{ trans('global.areYouSure') }}');" style="display: inline-block;">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="{{ trans('global.delete') }}">
                                    </form>
                                @endcan


                                @can('tizer_delete')
                                    <form action="{{ route('admin.tizers.null', $tizer->id) }}" method="GET" onsubmit="return confirm('Вы уверены. что хотите очистить CTR ?');" style="display: inline-block;">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="submit" class="btn btn-xs btn-danger" value="Очистить CTR">
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
        $('.toggle-class').change(function() {
        var is_active = $(this).prop('checked') == true ? 1 : 0; 
        var item_id = $(this).data('id'); 
         
        $.ajax({
            type: "GET",
            dataType: "json",
            url: 'tizers/active',
            data: {'is_active': is_active, 'item_id': item_id},
            success: function(data){
              console.log(data.success)
            }
        });
    })


  let dtButtons = $.extend(true, [], $.fn.dataTable.defaults.buttons)
@can('tizer_delete')
  let deleteButtonTrans = '{{ trans('global.datatables.delete') }}'
  let deleteButton = {
    text: deleteButtonTrans,
    url: "{{ route('admin.tizers.massDestroy') }}",
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
  $('.datatable-Tizer:not(.ajaxTable)').DataTable({ buttons: dtButtons })
    $('a[data-toggle="tab"]').on('shown.bs.tab', function(e){
        $($.fn.dataTable.tables(true)).DataTable()
            .columns.adjust();
    });
})

</script>
@endsection