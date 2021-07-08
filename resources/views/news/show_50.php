        <?php include('header.php') ?>
        
            <div class="row row-sticky without_sliders no-gutters">
                <div class="col-lg-8 col-md-8 col-sm-12 pl-0 pr-0 view-card">
                    
                    <a class="item double news-place double item_bottom"  v-bind:style="{
                        background: 'rgb(' + newsOne.rgb + ')',
                        color: 'white'
                    }"
                    style="margin:4px;"
                    >
                        <div class="card_col_1">                        
                            <img class="card-img" v-bind:src="newsOne.image" width="740px">                                                
                            <div class="item__gradient" v-bind:style="{'background': 'linear-gradient(rgba(' + newsOne.rgb + ', 0) 0%, rgb(' + newsOne.rgb + ') 100%)'}"></div>
                        </div>
                        <div class="card-img-out">
                            <h5 class="card-title">{{newsOne.title}}</h5>
                        </div>
                    </a>
                    <div class="card-des" v-html="newsOne.desc"></div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12 pl-0 pr-0 ">
                    <div class="row row-sticky without_sliders no-gutters">
                        <div class="col-lg-12 col-md-12 col-sm-12 d-flex">
                            <div   class="items right" style="flex-direction: column;">
                                <div class="item" v-for="rItem in right">
                                <a   v-bind:href="rItem.href" target="_blank" class="item" v-bind:style="{
                                    background: 'rgb(' + rItem.rgb + ')',
                                    color: 'white'
                                }">
                                    <div class="container__wrapper">
                                        <div class="container__content">
                                            <div class="card_col_1">
                                                <img class="card-img" v-bind:src="rItem.image" width="312px" />          
                                                <div class="item__gradient" v-bind:style="{'background': 'linear-gradient(rgba(' + rItem.rgb + ', 0) 0%, rgb(' + rItem.rgb + ') 100%)'}"></div>
                                            </div>
                                            <div class="card-img-out">
                                                <h5 class="card-title">{{rItem.title}}</h5>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="items" >
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
        cacheNextItem: null,
        right: [],
        header: <?=json_encode($header)?>
    },
    mounted: function () {

        let r1 = this.nextItem();
        if ( r1.hasOwnProperty('rgb') )
            this.right.push(r1);

        let r2 = this.nextItem();
        if ( r2.hasOwnProperty('rgb') )
            this.right.push(r2);

        let r3 = this.nextItem();
        if ( r3.hasOwnProperty('rgb') )
            this.right.push(r3);


  

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
            this.cacheNextItem = this.next_news || this.tizers.length === 0 ? this.getOneNews() : this.getOneTizer();
            return this.cacheNextItem;
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

