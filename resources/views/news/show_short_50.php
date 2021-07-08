        <?php include('header.php') ?>
    
            <div class="items" >
                <div class="item double">
                    <a v-bind:href="newsOne.href" target="_blank" class="" v-bind:style="{
                        background: 'rgb(' + newsOne.rgb + ')',
                        color: 'white'
                    }">
                        <div class="card_col_1">
                            <img class="card-img" v-bind:src="newsOne.image" width="312px" />
                        </div>
                        <div class="card-img-out">
                            <h5 class="card-title">{{newsOne.title}}</h5>
                            <div class="item__gradient" v-bind:style="{'background': 'radial-gradient(100% 500% at 100% center, rgba(' + newsOne.rgb + ', 0) 55%, rgb('+ newsOne.rgb +') 75%)'}"></div>
                        </div>
                        <div class="container__wrapper">
                            <div class="container__content">
                                <div class="card_col_1">
                                    <img class="card-img" v-bind:src="newsOne.image" width="312px" />          
                                    <div class="item__gradient" v-bind:style="{'background': 'linear-gradient(rgba(' + newsOne.rgb + ', 0) 0%, rgb(' + newsOne.rgb + ') 100%)'}"></div>
                                </div>
                                <div class="card-img-out">
                                    <h5 class="card-title">{{newsOne.title}}</h5>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="item">
                    <a v-bind:href="right_item[0].href" target="_blank" class="" v-bind:style="{
                                        background: 'rgb(' + right_item[0].rgb + ')',
                                        color: 'white'
                                    }">
                        <div class="card_col_1">
                            <img class="card-img" v-bind:src="right_item[0].image" width="312px" />
                        </div>
                        <div class="card-img-out">
                            <h5 class="card-title">{{right_item[0].title}}</h5>
                            <div class="item__gradient" v-bind:style="{'background': 'radial-gradient(100% 500% at 100% center, rgba(' + right_item[0].rgb + ', 0) 55%, rgb('+ right_item[0].rgb +') 75%)'}"></div>
                        </div>
                        <div class="container__wrapper">
                            <div class="container__content">
                                <div class="card_col_1">
                                    <img class="card-img" v-bind:src="right_item[0].image" width="312px" />          
                                    <div class="item__gradient" v-bind:style="{'background': 'linear-gradient(rgba(' + right_item[0].rgb + ', 0) 0%, rgb(' + right_item[0].rgb + ') 100%)'}"></div>
                                </div>
                                <div class="card-img-out">
                                    <h5 class="card-title">{{right_item[0].title}}</h5>
                                </div>
                            </div>
                        </div>
                    </a>
                </div> 

                <div v-for="(item, index) in items" class="item" v-bind:class="{
                        'double': isDouble(index)
                    }">
                    <a v-bind:href="item.href" target="_blank" class="" v-bind:style="{
                        background: 'rgb(' + item.rgb + ')',
                        color: 'white'
                    }">
                        <div class="card_col_1">
                            <img class="card-img" v-bind:src="item.image" width="312px" />
                        </div>
                        <div class="card-img-out">
                            <h5 class="card-title">{{item.title}}</h5>
                            <div class="item__gradient" v-bind:style="{'background': 'radial-gradient(100% 500% at 100% center, rgba(' + item.rgb + ', 0) 55%, rgb('+ item.rgb +') 75%)'}"></div>
                        </div>
                        <div class="container__wrapper">
                            <div class="container__content">
                                <div class="card_col_1">
                                    <img class="card-img" v-bind:src="item.image" width="312px" />          
                                    <div class="item__gradient" v-bind:style="{'background': 'linear-gradient(rgba(' + item.rgb + ', 0) 0%, rgb(' + item.rgb + ') 100%)'}"></div>
                                </div>
                                <div class="card-img-out">
                                    <h5 class="card-title">{{item.title}}</h5>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>        
            <div v-scroll="handleScroll" class="box"></div>
        </div>
    
    
        <?php include('footer.php') ?>
    
    </div>
 
 <script>

Vue.directive('scroll', {
  inserted: function (el, binding) {
    let f = function (evt) {
      if (binding.value(evt, el)) {
        window.removeEventListener('scroll', f)
      }
    }
    window.addEventListener('scroll', f)
  }
})

var listComponent = new Vue({
    el: '#app',
    data: {
        newsOne: <?= $item ?>,
        right_item: <?= $right_item ?>,
        news: <?= $news ?>,
        tizers: <?= $tizers ?>,
        url: '/news_short_50/<?=$id?>',
        link: '<?= $link ?>',
        page: 1,
        news_index: 0,
        tizers_index: 0,
        next_news: true,
        items: [],
        loadingNext: false,
        tryHandle: false,
        header: <?=json_encode($header)?>
    },
    mounted: function () {
        this.getNextItems(14);
    },
    methods: {
        handleScroll: function(ev) {
            let bottomOfWindow = document.documentElement.scrollTop + window.innerHeight + 1 > document.documentElement.offsetHeight;
            if (bottomOfWindow && !this.loadingNext) {
                this.getNextItems(7);
            } else if ( bottomOfWindow ) {
                this.tryHandle = true;
            }
        },
        getNextItems: function(count ) {
            let _self = this;
            for (let index = 0; index < count; index++) {
                this.items.push(this.nextItem());
            }

            if ( !this.full ) {
                this.loadingNext = true;
                axios
                    .get(this.nextUrl())
                    .then(function(response) { 
                        if ( !response.data.news.length && !response.data.tizers.length  ) {
                            _self.full = true;
                        } else {
                            response.data.news.forEach((value, index) => {_self.news.push(value)});
                            response.data.tizers.forEach((value, index) => {_self.tizers.push(value)});                            
                        }
                        _self.loadingNext = false;
                        if ( _self.tryHandle ) {
                            _self.tryHandle = false;
                            _self.getNextItems(7);
                        }
                    });
            }
        },
        nextItem: function() {            
            return this.next_news || this.tizers.length === 0 ? this.getOneNews() : this.getOneTizer();
        },
        getOneNews: function() {
            let next = this.news[this.news_index];
            this.news_index++;
            if ( this.news_index + 1 > this.news.length ) {
                this.news_index = 0;
            }
            this.next_news = this.tizers.length === 0;
            return next;
        },
        getOneTizer: function() {   
            let next = this.tizers[this.tizers_index];
            this.tizers_index++;
            if ( this.tizers_index + 1 > this.tizers.length ) {
                this.tizers_index = 0;
            }
            this.next_news = true;
            return next;
        },
        isDouble: function(idx) {
            let idxNormal = idx + 1;
            if ( idxNormal%7 > 0 ) {
                idxNormal = idxNormal%7;
            }            
            return  idxNormal%4 === 0 || idxNormal%7 === 0;
        },
        nextUrl: function() {
            this.page++;
            let axLink = this.url + "?page=" + this.page;
            if ( this.link ) {
                axLink += "&" + this.link;
            }
            return axLink;
        },
    },
});
</script>

</body>

</html>

