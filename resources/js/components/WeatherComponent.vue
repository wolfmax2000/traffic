<template>
    <div class="weather__content">        
        <h2>Погода в Москве</h2>
        
        <div class="weather__content_tabs clearfix">                
            <div v-for='day in data.forecast' :key="day.date" class="weather__content_tab dateFree"
                :class="{ current: isCurrent(day.date) }"
                @click="setCurrentDay(day.date)"
            >
                <div>
                    <p class="weather__content_tab-day">{{moment(day.date).format('dddd')}}</p>
                    <p class="weather__content_tab-date day_red">{{moment(day.date).format('DD')}}</p>
                    <p class="weather__content_tab-month">{{moment(day.date).format('MMM')}}</p>
                    <div data-title="Сплошная облачность" title="Сплошная облачность" class="weather__content_tab-icon m_out_tooltip" :class="day.symbol">
                        <label class="show-tooltip" style="display: block; margin: 0px 0px 0px 15px;">Сплошная облачность</label>
                    </div>            
                    <div class="weather__content_tab-temperature" style="width: 81px;">
                        <div class="min"><span>мин. </span> <b>{{day.minTemp}}°</b></div>
                        <div class="max"><span>макс. </span> <b>{{day.maxTemp}}°</b></div>
                    </div> 
                    <div class="weather__content_tab-stub"></div>
                </div>
            </div>            
        </div>

        <div class="weather__content_article clearfix">
            <div class="weather__content_article-left">
                <div class="weather__article_main clearfix">
                    <div class="weather__article_main_left">
                        <div class="weather__article_next_day">
                            <p class="infoDayweek">{{moment(currentDay).format('dddd')}}</p>
                            <p class="infoDate">{{moment(currentDay).format('DD')}}</p>
                            <p class="infoMonth">{{moment(currentDay).format('MMM')}}</p>
                        </div> 
                        
                        <div class="weather__article_main_subinfo">
                            
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
                            <li class="" v-for='h in currentDayInfo.hours' :key="h.time">
                                <div class="table__col">
                                    <div class="table__time_hours">{{moment(h.time).format('HH')}}<span>:00</span></div>
                                    <div class="table__time_img">
                                        <span data-title="Сплошная облачность, небольшой снег" title="Сплошная облачность, небольшой снег" class="weatherIco n412">
                                            <label class="show-tooltip" style="display: none;">Сплошная облачность, небольшой снег</label>
                                        </span>
                                    </div>
                                    <div class="table__temp">{{h.temperature}}°</div>
                                    <div class="table__felt">{{h.feelsLikeTemp}}°</div>
                                    <div class="table__pressure">768</div>
                                    <div class="table__humidity">93</div>
                                    <div data-tooltip="" class="table__wind wind-W"><i class="arrow"></i> <span>{{h.windSpeed}}</span>
                                        <label class="show-tooltip">{{h.windDirString}}, 5.6<span>м/с</span>
                                            <!----> <!----></label>
                                    </div> 
                                    <div class="table__precipitation">68</div>
                                </div>
                            </li>
                        </ul> <!---->                                                                                                         
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import moment from 'moment'



    export default {
        name: 'WeatherComponent',
        props: ['data'],
        data: () => ({
            moment: moment,
            currentDay: null,
        }),
        created: function () {
            this.currentDay = this.data.currentDay
        },
        computed: {
            currentDayInfo: function() {
                
                let neededHours = ['00', '03', '06', '09', '12', '15', '18', '21'];
                let hhhs = typeof this.data.forecast[this.currentDay].hours == 'object' ? Object.values(this.data.forecast[this.currentDay].hours) : this.data.forecast[this.currentDay].hours;               
                let info = {};
                          
                info.hours = hhhs.filter((h) => {
                    return  neededHours.indexOf(moment(h.time).format('HH')) !== -1;
                });
    console.log(info)
                return info;
            },
        },
        methods: {
            isCurrent: function(day) {
                return day == this.currentDay
            },
            setCurrentDay: function(day) {
               this.currentDay = day;
            },
        },        
        mounted () {
            //console.log(this.data)
        }
    }
</script>
