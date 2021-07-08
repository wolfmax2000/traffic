<template>
    <nav class="navbar navbar-expand-lg" 
            v-bind:class="{
                'navbar-dark bg-primary': !header['color1']
            }"

            v-bind:style="{
                'background-color': header['color1'],
                'color': header['color2'] ? header['color2'] : 'inherit',               
            }"
        >
            <div class="container" style="justify-content: start;">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand" href="/"
                    v-bind:style="{
                        'color': header['color2'] ? header['color2'] : 'inherit',
                    }"
                >
                <img v-if="header['logo_img']" v-bind:src="header['logo_img']" />
                </a>

                <div class="info_block">
                    <img v-if="header['info_img_1']" v-bind:src="header['info_img_1']" />
                    <span v-if="header['info_txt_1']" v-html="header['info_txt_1']"></span>
                    
                    <img v-if="header['info_img_2']" v-bind:src="header['info_img_2']" />
                    <span v-if="header['info_txt_2']" v-html="header['info_txt_2']"></span>

                    <img v-if="header['info_img_3']" v-bind:src="header['info_img_3']" />
                    <span v-if="header['info_txt_3']" v-html="header['info_txt_3']"></span>
                </div>                                                                
            </div>
        </nav>
</template>

<script>
    export default {
        name: 'NavComponent',
        props: ['header'],
        data: () => ({

        }),
        created: function () {            
        },
        computed: {},
        methods: {},        
        mounted () {
            console.log(this.header)
        }
    }
</script>
