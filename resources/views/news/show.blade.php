@push('styles')
<link href="{{ asset('css/tizers.css') }}" rel="stylesheet">
@endpush

@extends('layouts.front1')
@section('content')

<div class="container-fluid">
    <div class="container-width">
        <div class="row row-sticky without_sliders no-gutters">
            <div class="col-lg-8 col-md-8 col-sm-12 pl-0 pr-0 view-card"><a class="item item_bottom"  style="background: rgb({{ $item->getRGBText() }}); color: white;">
                    <div class="card_col_1">
                        @if ($item->image)
                        <img class="card-img" src="https://informerspro.ru/{{ $item->image->getUrl() }}" width="740px">
                        @endif
                        <div class="item__gradient" style="background: linear-gradient(rgba({{ $item->getRGBText() }}, 0) 0%, rgb({{ $item->getRGBText() }}) 100%);"></div>
                    </div>
                    <div class="card-img-out">
                        <h5 class="card-title">{{ $item->title }}</h5>
                    </div>
                </a>
                <div class="card-des">
                    @if ($show_as)
                        {!! $item->short_desc !!}
                    @else
                        {!! $item->desc !!}
                    @endif
                </div>
            </div>
            <div class="col-lg-4 col-md-4 col-sm-12 pl-0 pr-0 ">
                <div class="row row-sticky without_sliders no-gutters">

                    @if ($razgon)                        
                    <div class="col-lg-12 col-md-12 col-sm-12 d-flex">
                        <a href="{{ $razgon->getUrl($params['template_id'], $link) }}" target="_blank" class="item item_bottom" style="background: rgb({{ $razgon->getRGBText() }}); color: white;">
                            <div class="container__wrapper">
                                <div class="container__content">
                                    <div class="card_col_1">
                                        @if ($razgon->getImage())
                                        <img  class="card-img" src="https://informerspro.ru/{{ $razgon->getImage()->getUrl('thumb') }}?v=2">
                                        @endif
                                        <div class="item__gradient" style="background: linear-gradient(rgba({{ $razgon->getRGBText() }}, 0) 0%, rgb({{ $razgon->getRGBText() }}) 100%);"></div>
                                    </div>
                                    <div class="card-img-out">
                                        <h5 class="card-title"> {{ str_replace("[CITY]", $city, $razgon->getTitle()) }}</h5>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endif

                    @foreach ($tizers3 as $t)
                    <div class="col-lg-12 col-md-12 col-sm-12 d-flex">
                        <a href="{{ $t->getUrl($params['template_id'], $link) }}" target="_blank" class="item item_bottom" style="background: rgb({{ $t->getRGBText() }}); color: white;">
                            <div class="container__wrapper">
                                <div class="container__content">
                                    <div class="card_col_1">
                                        @if ($t->image)
                                        <img  class="card-img" src="https://informerspro.ru/{{ $t->image->getUrl('thumb') }}?v=2">
                                        @endif
                                        <div class="item__gradient" style="background: linear-gradient(rgba({{ $t->getRGBText() }}, 0) 0%, rgb({{ $t->getRGBText() }}) 100%);"></div>
                                    </div>
                                    <div class="card-img-out">
                                        <h5 class="card-title"> {{ str_replace("[CITY]", $city, $t->title) }}</h5>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>
</div>



<div class="container-fluid">
    <div class="container-width">
        @if (count( $rows) > 0 )
        <div class="infinite-scroll">
            @foreach($rows as $row)
            <div class="row without_sliders no-gutters">
                @foreach ($row as $row_item)
                @if (@$row_item['2col'] && !$isMobile)
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