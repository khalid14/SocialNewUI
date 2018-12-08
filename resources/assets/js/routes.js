import VueRouter from 'vue-router';

var routes = [
    { path: '*', redirect: '/' },
    {
        path: '/',
        component: require('./components/Dashboard'),
        meta: {

        }
    },
    {
        path: '/socialSprint',
        component: require('./components/socialSprint'),
        meta: {

        }
    },
    {
        path: '/manualScheduling',
        component: require('./components/manualScheduling'),
        meta: {

        }
    },
    {
        path: '/autoPilot',
        component: require('./components/autoPilot'),
        meta: {

        }
    },
    {
        path: '/socialSettings',
        component: require('./components/socialSettings'),
        meta: {

        }
    },
    {
        path: '/socialAnalytics',
        component: require('./components/socialAnalytics'),
        meta: {

        }
    },
    {
        path: '/singlePost',
        component: require('./components/singlePost'),
        meta: {

        }
    },
    {
        path: '/schedulePosts',
        component: require('./components/schedulePosts'),
        meta: {

        }
    },
];

export default new VueRouter({
    routes: routes
});