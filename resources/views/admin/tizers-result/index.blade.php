@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        Выдача тизеров 
    </div>

    <div class="card-body">
        
        <form id="templ_form">
            <div class="row">
            <div class="col-md-6">
            <select name='current_template' id="current_template" class="select2">
            @foreach($templates as  $templ)
                <option value="{{$templ->id}}" @if( $current_template==$templ->id ) selected=selected @endif>{{$templ->title}}</option>
            @endforeach
            </select>
            </div>
            <div class="col-md-6">
            <select name='current_country' id="current_country" class="select2">
            @foreach($countries as  $c)
                <option value="{{$c}}" @if( $current_country==$c ) selected=selected @endif>{{$c}}</option>
            @endforeach
            </select>
            </div>
            </div>
        </form>
        <br>
        <div class="table-responsive">
            <table class=" table table-bordered table-striped table-hover datatat">
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
                            {{ trans('cruds.tizer.fields.views') }}
                        </th>
                        <th>
                            {{ trans('cruds.tizer.fields.clicks') }}
                        </th>
                        <th>
                            {{ trans('cruds.tizer.fields.ctr') }}
                        </th>
                        <th>
                            {{ trans('cruds.tizer.fields.aprove') }}
                        </th>
                        <th>
                            {{ trans('cruds.tizer.fields.price') }}
                        </th>
                        <th>
                            {{ trans('cruds.tizer.fields.prior') }}
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
                                {{ $tizer->views ?? '' }}
                            </td>
                            <td>
                                {{ $tizer->clicks ?? '' }}
                            </td>
                            <td>
                                {{ $tizer->getCtr() ?? '' }}%
                            </td>
                            <td>
                                {{ $tizer->aprove ?? '' }}
                            </td>
                            <td>
                                {{ $tizer->price ?? '' }}
                            </td>
                            <td>
                                {{ $tizer->getPrior() ?? '' }}
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
        $('#current_template, #current_country').on('change' , function(e) {
            form = $("#templ_form");
            form[0].submit()
        });



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