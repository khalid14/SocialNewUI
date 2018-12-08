
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

import router from './routes';
Vue.component('app-header', require("./components/header.vue"));

import DaySpanVuetify from 'dayspan-vuetify'
Vue.component('my-calendar', require("./components/MyCalendar.vue"));
import 'dayspan-vuetify/dist/lib/dayspan-vuetify.min.css'

// import { library } from '@fortawesome/fontawesome-svg-core'
// import { faCoffee } from '@fortawesome/free-solid-svg-icons'
// import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

// library.add(faCoffee)
// Vue.component('font-awesome-icon', FontAwesomeIcon)

import loading from 'vue-full-loading'
Vue.component('loading', loading)

import VueGoogleCharts from 'vue-google-charts'
Vue.use(VueGoogleCharts);

Vue.use(DaySpanVuetify, {
    methods: {
        getDefaultEventColor: () => '#1976d2'
    }
});

//
// import Raphael from 'raphael/raphael'
// global.Raphael = Raphael;
//
// import  DonutChart  from 'vue-morris'
// Vue.use(DonutChart);

const gdpr = new Vue({
    el: '.pit',
    data: {
        pageTitle: 'Home'
    },
    router: router,
    methods: {
        redirect(target){

        }
    },
    watch: {
        '$route'(to, from) {
            // ---- another place to watch router redirect
            /*console.info('from = ', from);
            console.info('to = ', to);*/
        }
    },
});


// ----- axios CSRF interceptor
axios.interceptors.response.use(function success(response){
    return response;
}, function failure(error) {
    // ------ if request expired, update CSRF
    if(error.response.data.status == 'expired') {
        window.axios.defaults.headers.common['X-CSRF-TOKEN'] = error.response.data.token;
        delete error.response.data.token;
    }

    return Promise.reject(error);


});