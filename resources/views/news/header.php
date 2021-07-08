<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php if (isset($header['seo_title'])): ?><?=$header['seo_title']?><?php else:?>Новости<?php endif; ?></title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?= asset('css/adminltev3.css') ?>" rel="stylesheet" />
    <script src="/js/vue.min.js"></script>
    <script src="/js/axios.min.js"></script>    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <link href="<?= asset('css/news50.css') ?>" rel="stylesheet">
    <?php if (isset($header) &&  $header['head_script']): ?>
    <?=$header['head_script']?>
    <?php  endif; ?>
</head>

<body class="">
    <div id="app">
    
        <div v-if="header">
        <nav class="navbar navbar-expand-lg" 
            v-bind:class="{
                'navbar-dark bg-primary': !header['color1']
            }"

            v-bind:style="{
                'background-color': header['color1'],
                'color': header['color2'] ? header['color2'] : 'inherit',               
            }"
        >
            <div class="container">
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarTogglerDemo03" aria-controls="navbarTogglerDemo03" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <a class="navbar-brand" href="/"
                    v-bind:style="{
                        'color': header['color2'] ? header['color2'] : 'inherit',
                    }"
                >
                <img v-if="header['logo_img']" v-bind:src="header['logo_img']" />
                {{header['logo']}}</a>

                <div class="info_block">
                    <img v-if="header['info_img_1']" v-bind:src="header['info_img_1']" />
                    <span v-if="header['info_txt_1']" v-html="header['info_txt_1']"></span>
                    
                    <img v-if="header['info_img_2']" v-bind:src="header['info_img_2']" />
                    <span v-if="header['info_txt_2']" v-html="header['info_txt_2']"></span>

                    <img v-if="header['info_img_3']" v-bind:src="header['info_img_3']" />
                    <span v-if="header['info_txt_3']" v-html="header['info_txt_3']"></span>
                </div>
                
                
                
                <span><?=$country_en?></span>
            </div>
        </nav>
        <nav class="navbar navbar-expand-lg  sub-menu"
            v-bind:class="{
                'navbar-dark bg-dark': !header['color3']
            }"

            v-bind:style="{
                'background-color': header['color3'],
                'color': header['color4'] ? header['color4'] : 'inherit',               
            }"
        >
            <div class="container">
                <div class="collapse navbar-collapse" id="navbarTogglerDemo03">
                    <ul class="navbar-nav mr-auto mt-2 mt-lg-0" >
                        <li class="nav-item" v-for="(item, index) in header['menu']">
                            <a class="nav-link" v-bind:href="item.link"
                                v-bind:style="{
                                    'color': header['color4'] ? header['color4'] : 'inherit',
                                }"
                            >{{item.title}}</a>
                        </li>
                        
                    
                    </ul>    
                </div>
            </div>
        </nav>
    </div>
    <div class="container-width">
        <?php if (isset($header) &&  $header['banner']): ?>
        <a href="<?=$header['banner_url']?>">
        <img class="banner" src="<?=$header['banner']?>" width="268" height="460" /> 
        </a>
        <?php  endif; ?>
                            
        

    