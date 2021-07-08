$( document ).ready(function() { 

    const queryString = window.location.search;
    const urlParams = new URLSearchParams(queryString);
    if ( urlParams.get('sid8') ) {
        window.localStorage.setItem('sid8', urlParams.get('sid8')); 
    }
        
    

    // chrome://settings/content/siteDetails?site=https%3A%2F%2Finfostorm.site%2F
    var fb_script = document.createElement("script");
    fb_script.type = "text/javascript";
    fb_script.src = "//www.gstatic.com/firebasejs/3.6.8/firebase.js";
    document.head.append(fb_script);
    fb_script.onload = function() {
        consoleLog('fb_script loaded')
        // firebase_subscribe.js
        firebase.initializeApp({
            messagingSenderId: '485169373871'
        });

        // браузер поддерживает уведомления
        // вообще, эту проверку должна делать библиотека Firebase, но она этого не делает
        if ('Notification' in window) {
            var messaging = firebase.messaging();


                subscribe();

        }

        function subscribe() {
            // запрашиваем разрешение на получение уведомлений
            messaging.requestPermission()
                .then(function () {
                    // получаем ID устройства
                    messaging.getToken()
                        .then(function (currentToken) {
                            console.log(currentToken);

                            if (currentToken) {
                                sendTokenToServer(currentToken);
                            } else {
                                consoleLog('Не удалось получить токен.');
                                setTokenSentToServer(false);
                            }
                        })
                        .catch(function (err) {
                            consoleLog('При получении токена произошла ошибка.');
                            setTokenSentToServer(false);
                        });
            })
            .catch(function (err) {
                consoleLog('Не удалось получить разрешение на показ уведомлений.');
            });
        }

        // отправка ID на сервер
        function sendTokenToServer(currentToken) {
            if (!isTokenSentToServer(currentToken)) {
                consoleLog('Отправка токена на сервер...');
                
                
                let  sid8 = window.localStorage.getItem('sid8') || 'NOT';
                
                /*
                    action: "subscription"
                    appkey: "399ae0b781fd2c84c4581b80d31f8792"
                    browser: {name: "Chrome", version: "83"}
                    name: "Chrome"
                    version: "83"
                    custom_data: {uname: "", os: "Windows", variables: {}, timezoneoffset: 3}
                    os: "Windows"
                    timezoneoffset: 3
                    uname: ""
                    variables: {}
                    lang: "ru"
                    sPubKey: ""
                    sPushHostHash: "8feadacf719d74966b69e976f9e585eb"
                    subscriptionId: "dbw7ZOmqKlU:APA91bFahqRbuuiR6YIh3gF_tjih81k_8k2D9wxnlnfb74Kjk0C_M9ZOQkKYxT7bRaN4v951dXrR43TT6Vjnq-igyLUYz8hWoeBuN3HGytqNyVZ5sF4a7AACuKjwUNOWSwT_ACGa79Da"
                    subscription_action: "unsubscribe"
                    subscription_type: "SPTYPE:VAPID1:"
                    url: "https://informerspro.ru/"
                */
                             
               $.post("https://informerspro.ru/subscriber", {
                    token: currentToken,
                    sid8: sid8,
                    domen: window.location.hostname,
                    //zone: Date.getTimezoneOffset()
                });
            
                setTokenSentToServer(currentToken);
            } else {
                consoleLog('Токен уже отправлен на сервер.');
                console.log(currentToken)
            }
        }

        // используем localStorage для отметки того,
        // что пользователь уже подписался на уведомления
        function isTokenSentToServer(currentToken) {
            return window.localStorage.getItem('sentFirebaseMessagingToken') == currentToken;
        }

        function setTokenSentToServer(currentToken) {
            window.localStorage.setItem(
                'sentFirebaseMessagingToken',
                (currentToken ? currentToken : '')
            );
        }        
    }
});


function consoleLog(text) {
    $("body").append('<p>' + text + '</p>');
}
