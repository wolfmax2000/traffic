<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>User Guide</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet" />
    <link href="<?= asset('css/adminltev3.css') ?>" rel="stylesheet" />
    <script src="/js/vue.min.js"></script>
    <script src="/js/axios.min.js"></script>    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>

    <link href="<?= asset('css/news50.css') ?>" rel="stylesheet">
</head>

<body class="">

    <div id="app">
        <?php include('header.php') ?>
        <div class="container-width" style="min-height: 500px;">
            <?=$domain->user_accept?>
        </div>
        <?php include('footer.php') ?>
    </div>
    
 <script>


var listComponent = new Vue({
    el: '#app',
    data: {       
        header: <?=json_encode($header)?>
    }, 
    mounted: function() {
        console.log(this.header);
    }
});
</script>

</body>

</html>

