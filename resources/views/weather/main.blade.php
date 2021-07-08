@extends('layouts.weather')

@section('content')

<div id="app" class="main-page wcontainer-fluid">
    <nav-component :header='{!! json_encode($header) !!}'></nav-component>       
    <div class="container-width ">
        <div style="display: flex;" class="top-cont">
            <div style="width: 685px;" class="weather-comp">
                <weather-component :data='{!! json_encode($data) !!}'></weather-component>        
            </div>
            <div class="right-items">
                @if (count($news1)>0)
                <div class="items">
                    <div class="item">
                        <a href="{{$news1[0]->getUrl()}}" target="_blank" class="" style="background: rgb(200, 173, 147); color: white;">
                            <div class="card_col_1">
                                <img class="card-img" src="https://informerspro.ru/{{ $news1[0]->image->thumbnail }}"  width="312px" />
                            </div>
                            <div class="card-img-out">
                                <span class="card-title">{{$news1[0]['title']}}</span>
                                <div class="item__gradient"></div>
                            </div>
                            <div class="container__wrapper">
                                <div class="container__content">
                                    <div class="card_col_1">
                                        <img class="card-img"  src="https://informerspro.ru/{{ $news1[0]->image->thumbnail }}"  width="312px" />          
                                        <div class="item__gradient" style="background: linear-gradient(rgba(200, 173, 147, 0) 0%, rgb(200, 173, 147) 100%);"></div>
                                    </div>
                                    <div class="card-img-out">
                                        <span class="card-title">{{$news1[0]['title']}}</span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="container-width">
        <news-list-component :news='{!! json_encode($news) !!}' :tizers='{!! json_encode($tizers) !!}'></news-list-component>
    </div>
</div>
@endsection