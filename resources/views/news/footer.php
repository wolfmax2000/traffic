<div v-if="header" style="background-color: gray;">  
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sub-menu">
    <div class="container" style="align-items: flex-start;">
        <a class="navbar-brand" href="/">{{header['logo']}}</a>
        <ul class="navbar-nav mr-auto mt-2 mt-lg-0">
            <li class="nav-item active" >
                <a href="/users_guide" class="nav-link">Пользовательское соглашение</a>
            </li>
        </ul>
        <div v-html="header['contacts']"></div>
    </div>
    </nav>        
</div>
    
<template v-if="header && header['show_coockie']">
    <div id="privacy-pop-up" class="eucookie-pop-up">        
        <div class="content">
            <a href="/users_coockie" v-html="header['coockie_text']"></a>
            <div style="dismiss">
                <button class="btn btn-small btn-primary" id="exit-popup">{{header['coockie_button']}}</button>
            </div>
        </div>
    </div>
</template>
<script>
    $(document).ready(function() {
        if ( $("#privacy-pop-up").length) {
            if(localStorage.getItem('eucookie') != 'close'){       
                $("#privacy-pop-up").delay(1).fadeIn(1000);                
            } 
            
            $('#exit-popup').click(function(e) { 
                $('#privacy-pop-up').fadeOut(1000);
                localStorage.setItem('eucookie','close');
            });    
        }        
    });
</script>
<?php if (isset($header) &&  $header['body_script']): ?>
    <?=$header['body_script']?>
<?php  endif; ?>