
import _ from 'lodash';

import Vue from 'vue';

import VueRouter from 'vue-router';

import Vuetify from 'vuetify';

import j from "jquery";

import axios from 'axios';




window._ = _;

window.$ = j;

window.Vue = Vue;

Vue.use(VueRouter);

// Helpers
import colors from 'vuetify/es5/util/colors'

Vue.use(Vuetify, {
    theme: {
        primary: "#2196F3",
        secondary: "#e57373",
        accent: "#9c27b0",
        error: "#f44336",
        warning: "#ffeb3b",
        info: "#2196f3",
        success: "#4caf50",
        ideaCore: "689f38",
        greyText: '#4b4b4b',
        fbColor: '#3B5998',
        twitterColor: '#1DA1F2',
        instaColor:'#262626',
        pintColor:'#BD081C',
        socialGenie: '#3f3f3f',
        primaryHover:'#ffbb41',
        lightGreenCta:'#d0d0d0',
        tableHead: '#f3f3f3',
        ctaColor: '#f59c0e',
        textColor: '#1e1e1e'
      }
});


/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

window.axios = axios;

