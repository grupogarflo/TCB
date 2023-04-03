import Vue from 'vue';
//import Moment from 'nuxt-moment';

Vue.filter('currencyFormat', (value, currency = null) => {
    if (typeof value !== "number") {
        return value;
    }
    let formatter = new Intl.NumberFormat('en-US');
    return (currency === null) ? `$${formatter.format(value.toFixed(2))} ` : `$${formatter.format(value.toFixed(2))} ${currency}`;

})

Vue.filter('showDate', (value, context) => {

    /*let date = new Date(value);
    let year = date.getFullYear();
    let m = date.getMonth() + 1;
    let d = date.getDate();


    if (m < 10) m = '0' + m;
    if (d < 10) d = '0' + d;

    return ` ${d}-${m}-${year}`;*/
    // console.log(context);
    if (value == null) { return value }
    return context.$moment(value).locale('es').format('L');

});

Vue.filter('truncate', function (text, length, suffix) {
    if (text.length > length) {
        return text.substring(0, length) + suffix;
    } else {
        return text;
    }
});
