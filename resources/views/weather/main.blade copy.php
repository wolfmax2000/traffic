@extends('layouts.weather')
@section('content')

@php
$current = $forecast["data"]['current'];

@endphp

<div id="app">
    <div class="container">        
        <div class="weather__content">
            <h2>Погода в Ростове-на-Дону</h2>
            
            <div class="weather__content_tabs clearfix">                
                @foreach ($forecast["data"]['forecast'] as $day)
                <div class="weather__content_tab @if($day->date=='2021-03-04') current @endif dateFree">
                    <div>
                        <p class="weather__content_tab-day">{{date('D',strtotime($day->date))}}</p>
                        <p class="weather__content_tab-date day_red">{{date('d',strtotime($day->date))}}</p>
                        <p class="weather__content_tab-month">{{date('M',strtotime($day->date))}}</p>
                        <div data-title="Сплошная облачность" title="Сплошная облачность" class="weather__content_tab-icon {{$day->symbol}} m_out_tooltip">
                            <label class="show-tooltip" style="display: block; margin: 0px 0px 0px 15px;">Сплошная облачность</label>
                        </div>
                
                        <div class="weather__content_tab-temperature" style="width: 81px;">
                            <div class="min"><span>мин. </span> <b>{{$day->minTemp}}°</b></div>
                            <div class="max"><span>макс. </span> <b>{{$day->maxTemp}}°</b></div>
                        </div> 
                        <div class="weather__content_tab-stub"></div>
                    </div>
                </div>
                @endforeach
                
            </div>


            <div class="weather__content_article clearfix">
                <div class="weather__content_article-left">
                    <div class="weather__article_main clearfix">
                        <div class="weather__article_main_left">
                            <div class="weather__article_next_day">
                                <p class="infoDayweek">{{date('D',strtotime($current->time))}}</p>
                                <p class="infoDate">{{date('d',strtotime($current->time))}}</p>
                                <p class="infoMonth">{{date('M',strtotime($current->time))}}</p>
                            </div> 
                            
                            <div class="weather__article_main_subinfo">
                                <div class="sunrise-sunset-info ru">
                                    <!--div class="ss_wrap ru">
			                            Восход <span>7:04</span>
			                            Закат <span>18:03</span>
                                    </div-->
                                </div>
                            </div>
                            <div class="weather__article_main-titles">
                                <p class="temp">Температура,<span>°C</span></p>
                                <p class="felt">
                                    <span data-tooltip="" class="Tooltip not_hide_tooltip">чувствуется как
			                            <label class="show-tooltip">как будет ощущаться<br> температура воздуха<br> человеком, одетым по сезону</label>
                                    </span>
                                </p>
                                <p class="pressure">Давление,
			                        <span>мм</span> </p>
                                <p class="humidity">Влажность, %</p>
                                <p class="wind">Ветер,<span>м/с</span></p>
                                <p class="precipitation">Вероятность<br> осадков, %</p>
                            </div>
                        </div>
                        
                        <div class="weather__article_main_right">
                            <ul class="weather__article_main_right-titles clearfix">
                                <li>Ночь</li>
                                <li>Утро</li>
                                <li>День</li>
                                <li>Вечер</li>
                            </ul>
                            <ul class="weather__article_main_right-table clearfix">
                                @foreach ($forecast["data"]["forecast"]['2021-03-05']->hours as $h)
                                <li class="short-day">
                                    <div class="table__col">
                                        <div class="table__time_hours">3<span>:00</span></div>
                                        <div class="table__time_img">
                                            <span data-title="Сплошная облачность, небольшой снег" title="Сплошная облачность, небольшой снег" class="weatherIco n412">
                                                <label class="show-tooltip" style="display: none;">Сплошная облачность, небольшой снег</label>
                                            </span>
                                        </div>
                                        <div class="table__temp">-1°</div>
                                        <div class="table__felt">-7°</div>
                                        <div class="table__pressure">768</div>
                                        <div class="table__humidity">93</div>
                                        <div data-tooltip="" class="table__wind wind-W"><i class="arrow"></i> <span>5.6</span>
                                            <label class="show-tooltip">Западный, 5.6<span>м/с</span>
                                             <!----> <!----></label>
                                        </div> 
                                        <div class="table__precipitation">68</div>
                                    </div>
                                </li>    
                                @endforeach                           
                            </ul> <!---->                                                                                                         
                        </div>
                    </div>
                </div>
            </div>
        </div>


        </div>
    </div>
</div>

@endsection