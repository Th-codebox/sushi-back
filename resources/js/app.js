require('./bootstrap');
import Echo from "laravel-echo"
window.io = require('socket.io-client');

window.tocken = "********";

let echo = new Echo({
    broadcaster: 'socket.io',
    host: window.location.hostname + ':6001', // значение должно быть равным authHost из конфига + порт,
    transports: ['websocket'],
    auth: {
        headers: {
            Authorization: 'Bearer ' + window.tocken
        }
    }
});

let processCallEvent = function (e) {
    window.console.log(e);
    //alert('Входящий звонок на апарат '+e.accountId+' с номера: '+e.clientPhone);
    alert('событие');
}


/*echo.channel('phone.account.2000')
    //.private('phone.1') Раскоментировать для авторизации в канале
     .listen('Phone\\IncomingCallEvent', processCallEvent);

echo.channel('phone.account.2000')
    //.private('phone.1') Раскоментировать для авторизации в канале
    .listen('Phone\\AnswerCallEvent', processCallEvent);

echo.channel('phone.account.2000')
    //.private('phone.1') Раскоментировать для авторизации в канале
    .listen('Phone\\HangupCallEvent', processCallEvent);*/




echo.channel('orders.filial.1')
    //.private('phone.1') Раскоментировать для авторизации в канале
    .listen('Order\\UpdateOrder', processCallEvent)
    .listen('Order\\UpdateStatusesOrder', processCallEvent);




