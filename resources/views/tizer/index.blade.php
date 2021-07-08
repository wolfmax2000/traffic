@push('styles')
<link href="{{ asset('css/tizers.css') }}" rel="stylesheet">
@endpush

@extends('layouts.front')
@section('content')
<div id="app">
<div class="container-fluid">
    <div class="container-width">
<weather-component :data='{!! json_encode($weather) !!}' />
</div>
</div>
@if ($razgon)
<div class="container-fluid">
    <div class="container-width">        
        <div class="">
            <div class="row without_sliders no-gutters">
                @if (!$isMobile)
                @include('tizer._item2', ['item' => $razgon])
                @else
                @include('tizer._item', ['item' => $razgon])
                @endif

                @if (isset($nearRazgon[0]))
                @include('tizer._item', ['item' => $nearRazgon[0]])
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<div class="container-fluid">
    <div class="container-width">
        @if (count( $rows) > 0 )
        <div class="infinite-scroll">
            @foreach($rows as $row)
            <div class="row without_sliders no-gutters">
                @foreach ($row as $row_item)
                    @if (@$row_item['2col'] && !$isMobile )
                        @include('tizer._item2', ['item' => $row_item['t']])
                    @else
                        @include('tizer._item', ['item' => $row_item['t']])
                    @endif
                @endforeach
            </div>
            @endforeach            
            <ul class="pagination">
                <li class="active"></li>
                <li><a href="{{ $nextUrl }}">1</a></li>
            </ul>                                            
        </div>
        @else
        Нет записей
        @endif
    </div>
</div>

@endsection

@section('scripts')
<script>
    $('ul.pagination').hide();
    $(function() {
        
        $('.infinite-scroll').jscroll({
            autoTrigger: true,
            loadingHtml: '<div class="center-block"><img  src="/images/loading.gif" alt="Loading..." /></div>',
            padding: 100,
            nextSelector: '.pagination li.active + li a',
            contentSelector: 'div.infinite-scroll',
            callback: function() {                
                $('ul.pagination').remove();
            }
        });

        @if (isset($params['stat_id']))
        var timerId = setInterval(function() {
            $.get('/stat/heare/{{ @$params["stat_id"] }}',function(data){
                console.log(data);
            });
        }, 15000);
        @endif
    });
</script>

@endsection

@section('body_script')
    @if ( isset($params['body_script']) )
    {!! $params['body_script'] !!}
    @endif
@endsection

@section('head_script')
    @if ( isset($params['head_script']) )
    {!! $params['head_script'] !!}
    @endif
@endsection