<template>
    <v-app id="inspire">
        <v-navigation-drawer
                :clipped="$vuetify.breakpoint.lgAndUp"
                v-model="drawer"
                fixed
                app
                class="backend-left-menu socialGenie"
                width="260"
        >


            <!-- Testing -->

            <v-menu offset-x class="top-left-user" transition="scale-transition">
                <v-btn
                        slot="activator"
                        flat
                        depressed
                        class="pl-0"
                >
                    <v-avatar tile>
                        <img src="/images/social-genie-brand-logo.png" alt="Social Genie By 1dea">
                    </v-avatar>
                    <!--<div class="user-drop-down">-->
                        <!--<span>Tariq Khursheed</span>-->
                        <!--<i class="material-icons">-->
                            <!--arrow_drop_down-->
                        <!--</i>-->
                    <!--</div>-->
                </v-btn>
                <!--<v-list>-->
                    <!--<v-list-tile @click="">-->
                        <!--<v-list-tile-title>Link One</v-list-tile-title>-->
                    <!--</v-list-tile>-->
                    <!--<v-list-tile @click="">-->
                        <!--<v-list-tile-title>Link Two</v-list-tile-title>-->
                    <!--</v-list-tile>-->
                    <!--<v-list-tile @click="">-->
                        <!--<v-list-tile-title>Link Three</v-list-tile-title>-->
                    <!--</v-list-tile>-->
                <!--</v-list>-->
            </v-menu>

            <!-- Testing end -->




            <v-list dense class="left-side-bar">
                <template v-for="item in items">
                    <v-list-group
                        v-if="item.children"
                        v-model="item.model"
                        :key="item.text"
                        :append-icon="item.model ? item.icon : item['icon-alt']"
                        :prepend-icon="item.model ? item.textIcon : item['textIcon-alt']"
                    >
                        <v-list-tile slot="activator" class="hello asdf">
                            <v-list-tile-content>
                                <v-list-tile-title>
                                    {{ item.text }}
                                </v-list-tile-title>
                            </v-list-tile-content>
                        </v-list-tile>
                        <v-list-tile
                            v-for="(child, i) in item.children"
                            :key="i"
                            :to="child.url == '#' ? '' : child.url"
                            @click="dashboardActive"
                        >

                            <v-list-group
                                    sub-group
                                    :prepend-icon="child.model ? child.icon : child['icon-alt']"
                                    class="sub-child-start"
                            >
                                <v-list-tile slot="activator" class="broooook">
                                    <v-list-tile-content>
                                        <v-list-tile-title>
                                            <v-list-tile-title class="pl-5">
                                                <!--<v-avatar size="32px" class="mr-2">-->
                                                    <!--<img :src="child.seal" :alt="child.text">-->
                                                <!--</v-avatar>-->
                                                <!--<v-icon class="mr-3">-->
                                                    <!--{{child.icon}}-->
                                                <!--</v-icon>-->
                                                {{ child.text }}
                                            </v-list-tile-title>
                                        </v-list-tile-title>
                                    </v-list-tile-content>
                                </v-list-tile>


                                <v-list-tile
                                        v-for="(bChild, i) in child.subChildren"
                                        @click=""
                                        v-model="bChild.model"
                                        :key="bChild.text"
                                        :append-icon="bChild.model ? bChild.icon : bChild['icon-alt']"

                                        class="choty-bachy"
                                        :to="bChild.url == '#' ? '' : bChild.url"
                                        exact
                                >
                                    <v-list-tile-title>
                                        <v-icon>
                                            {{bChild.icon}}
                                        </v-icon>
                                        <span>{{ bChild.text }}</span>
                                    </v-list-tile-title>
                                </v-list-tile>
                            </v-list-group>

                            <!--<v-list-tile-action v-if="child.icon">-->
                                <!--<v-icon>{{ child.icon }}</v-icon>-->
                            <!--</v-list-tile-action>-->

                        </v-list-tile>
                    </v-list-group>

                    <v-list-tile  v-else :key="item.text" @click="dashboardActive" class="no-child" :to="item.url == '#' ? '' : item.url"  exact>
                        <v-list-tile-action>
                            <v-icon>{{ item.icon }}</v-icon>
                        </v-list-tile-action>
                        <v-list-tile-content>
                            <v-list-tile-title>
                                {{ item.text }}
                            </v-list-tile-title>
                        </v-list-tile-content>
                    </v-list-tile>
                </template>
            </v-list>

            <!-- Social Points -->
            <v-card class="score-pad darken-1 mt-4 socialGenie">
                <v-card-text class="pt-4 white--text">
                    <p class="text-xs-center">Your current social points<span class="pt-2 text-xs-center">520</span></p>
                    <div class="scorepad-ctas">
                        <v-btn color="lightGreenCta">Add More</v-btn>
                        <v-btn color="primaryHover">Win More</v-btn>
                    </div>
                </v-card-text>
            </v-card>

        </v-navigation-drawer>
        <v-toolbar
                :clipped-left="$vuetify.breakpoint.lgAndUp"
                color="socialGenie"
                dark
                app
                fixed
                class="main-jedi-menu"
                flat
        >
            <v-toolbar-title class="ml-0 pl-3 main-burger-menu">
                <v-toolbar-side-icon @click.stop="drawer = !drawer"></v-toolbar-side-icon>
                <p class="brand-logo">
                    Social Genie <span>By 1dea</span>
                </p>
                <!--<span class="hidden-sm-and-up brand-logo">-->
                    <!--<img src="../../images/small-grel-logo.png" alt="Global Real Estate Licence">-->
                <!--</span>-->
            </v-toolbar-title>

            <v-spacer></v-spacer>
            <v-btn icon>
                <v-icon>notifications</v-icon>
            </v-btn>
            <v-menu offset-y>
                <v-btn
                        slot="activator"
                        flat
                        depressed
                        class="pl-0 store-details mr-0"
                >
                    <v-avatar size="32px" tile class="mr-2">
                        <img src="../../images/tk.png" alt="Tariq Khurshid">
                    </v-avatar>
                    <i class="material-icons">
                        arrow_drop_down
                    </i>
                </v-btn>
                <v-list>
                    <v-list-tile @click="">
                        <v-list-tile-title>Store 2</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile @click="">
                        <v-list-tile-title>Upgrade</v-list-tile-title>
                    </v-list-tile>
                    <v-list-tile @click="">
                        <v-list-tile-title>Log Out</v-list-tile-title>
                    </v-list-tile>
                </v-list>
            </v-menu>
            <v-btn
                    dark
                    icon
                    class="ma-0"
                    style="width:45px; height:45px"
            >
                <v-icon size="36px">apps</v-icon>
            </v-btn>
        </v-toolbar>
        <v-content>
            <v-container fluid fill-height class="pa-5 main-top-app-container">
                <v-layout justify-center align-center style="display: block">
                    <router-view></router-view>
                </v-layout>
            </v-container>
        </v-content>

    </v-app>
</template>

<style>
    .left-side-bar .choty-bachy a{
        height: auto !important;
    }
    .v-list--dense .v-list__tile:not(.v-list__tile--avatar) {
        height: auto;
        padding: 9px 0px;
    }
    .v-list--dense .v-list__tile:not(.v-list__tile--avatar) .v-list__tile {
        padding: 0;
    }
    .v-list__group .v-list__group .v-list__group__header:hover{background-color: transparent}
    .v-list__group .v-list__group--active:before,
    .v-list__group .v-list__group--active:after{
        background: none !important;
    }
    .v-list__group .v-list__group .choty-bachy{padding-left:0px}
    .v-list--dense .v-list__tile:not(.v-list__tile--avatar) .v-list__group{width: 100%}
</style>


<script>
    export default {
        data: () => ({
            drawer: null,
            items: [
                {
                    icon: 'dashboard',
                    text: 'Dashboard',
                    url: '/dashboard'
                },
                {
                    icon: 'keyboard_arrow_up',
                    'icon-alt': 'keyboard_arrow_down',
                    textIcon : 'edit',
                    'textIcon-alt' : 'edit',
                    text: 'Create A Sprint',
                    model: false,
                    children: [
                        {
                            text: 'Manual Scheduling',
                            // seal: '../../images/broker-seal.png',
                            icon:'explore',
                            url: '/manualScheduling'
                        },
                        {
                            text: 'Auto Pilot',
                            // seal: '../../images/broker-seal.png',
                            icon:'explore',
                            url: '/autoPilot'
                        },
                        {
                            text: 'Single Post',
                            // seal: '../../images/broker-seal.png',
                            icon:'explore',
                            url: '/singlePost'
                        },
                    ]
                },
                {
                    icon: 'map',
                    text: 'Social Roadmap',
                    url: '/dashboard'
                },
                {
                    icon: 'access_time',
                    text: 'Schedule Posts',
                    url: '/schedulePosts'
                },
                {
                    icon: 'timeline',
                    text: 'Analytics',
                    url: '/socialAnalytics'
                },
                {
                    icon: 'settings',
                    text: 'Social Settings',
                    url: '/socialSettings'
                },
                {
                    icon: 'live_help',
                    text: 'Help Center',
                    url: '/dashboard'
                },
                {
                    icon: 'verified_user',
                    text: 'Privacy Policy',
                    url: '/dashboard'
                },
                // {
                //     icon: 'keyboard_arrow_up',
                //     'icon-alt': 'keyboard_arrow_down',
                //     textIcon : 'card_membership',
                //     'textIcon-alt' : 'card_membership',
                //     text: 'Licence Editions',
                //     model: false,
                //     children: [
                //         {
                //             text: 'Broker Edition',
                //             model: false,
                //             seal: '../../public/images/broker-seal.png',
                //             subChildren : [
                //                 {
                //                     text: 'Training',
                //                     url: 'broker-1.1',
                //                     icon: 'gamepad'
                //                 },
                //                 {
                //                     text: 'Tools',
                //                     url: 'broker-tools',
                //                     icon: 'picture_as_pdf'
                //                 },
                //                 {
                //                     text: 'Branding',
                //                     url: 'broker-branding',
                //                     icon: 'sentiment_satisfied_alt'
                //                 }
                //             ],
                //
                //         },
                //         {
                //             text: 'Developer Edition',
                //             model:false,
                //             seal: '../../public/images/developer-seal.png',
                //             subChildren : [
                //                 {
                //                     text: 'Training',
                //                     url: 'developer-training',
                //                     icon: 'gamepad',
                //                 },
                //                 {
                //                     text: 'Tools',
                //                     url: 'developer-tools',
                //                     icon: 'picture_as_pdf'
                //                 },
                //                 {
                //                     text: 'Branding',
                //                     url: 'developer-branding',
                //                     icon: 'sentiment_satisfied_alt'
                //                 }
                //             ],
                //         },
                //         {
                //             text: 'Investor Edition',
                //             model:false,
                //             seal: '../../public/images/investor-seal.png',
                //             subChildren : [
                //                 {
                //                     text: 'Training',
                //                     url: 'investor-training',
                //                     icon: 'gamepad'
                //                 },
                //                 {
                //                     text: 'Tools',
                //                     url: 'investor-tools',
                //                     icon: 'picture_as_pdf'
                //                 },
                //                 {
                //                     text: 'Branding',
                //                     url: 'investor-branding',
                //                     icon: 'sentiment_satisfied_alt'
                //                 }
                //             ],
                //         }
                //     ]
                // },
                //
                // {
                //     icon: 'keyboard_arrow_up',
                //     'icon-alt': 'keyboard_arrow_down',
                //     textIcon : 'card_giftcard',
                //     'textIcon-alt' : 'card_giftcard',
                //     text: 'Speciality Designations',
                //     model: false,
                //     children: [
                //         {
                //             text: 'ACS',
                //             seal: '../../public/images/broker-seal.png',
                //             url: '/area'
                //         },
                //         {
                //             text: 'VS-REX',
                //             seal: '../../public/images/broker-seal.png',
                //             url: '/carema'
                //         },
                //         {
                //             text: 'CS-REX',
                //             seal: '../../public/images/broker-seal.png',
                //             url: '/css'
                //         },
                //         {
                //             text: 'LH-REX',
                //             seal: '../../public/images/broker-seal.png',
                //             url: '/css'
                //         },
                //         {
                //             text: 'AIPS',
                //             seal: '../../public/images/broker-seal.png',
                //             url: '/css'
                //         },
                //         {
                //             text: 'ARPM',
                //             seal: '../../public/images/broker-seal.png',
                //             url: '/css'
                //         },
                //         {
                //             text: 'CHFS',
                //             seal: '../../public/images/broker-seal.png',
                //             url: '/css'
                //         }
                //     ]
                // },
                // {
                //     icon: 'keyboard_arrow_up',
                //     'icon-alt': 'keyboard_arrow_down',
                //     textIcon : 'card_giftcard',
                //     'textIcon-alt' : 'card_giftcard',
                //     text: 'Skill Certifications',
                //     model: false,
                //     children: [
                //         {
                //             text: 'CREN',
                //             seal: '../../public/images/broker-seal.png',
                //             url: '/area'
                //         },
                //         {
                //             text: 'CRSS',
                //             seal: '../../public/images/broker-seal.png',
                //             url: '/carema'
                //         },
                //         {
                //             text: 'COL-REX',
                //             seal: '../../public/images/broker-seal.png',
                //             url: '/css'
                //         }
                //     ]
                // },
                // {
                //     icon: 'keyboard_arrow_up',
                //     'icon-alt': 'keyboard_arrow_down',
                //     textIcon : 'card_giftcard',
                //     'textIcon-alt' : 'card_giftcard',
                //     text: 'Assistants Certifications',
                //     model: false,
                //     children: [
                //         {
                //             text: 'AREA',
                //             seal: '../../public/images/broker-seal.png',
                //             url: '/area'
                //         },
                //         {
                //             text: 'CREMA',
                //             seal: '../../public/images/broker-seal.png',
                //             url: '/carema'
                //         },
                //         {
                //             text: 'CAREA',
                //             seal: '../../public/images/broker-seal.png',
                //             url: '/css'
                //         },
                //         {
                //             text: 'OMREA',
                //             seal: '../../public/images/broker-seal.png',
                //             url: '/css'
                //         }
                //     ]
                // },
                // {
                //     icon: 'keyboard_arrow_up',
                //     'icon-alt': 'keyboard_arrow_down',
                //     textIcon : 'card_membership',
                //     'textIcon-alt' : 'card_membership',
                //     text: 'Workout Scripts',
                //     model: false,
                //     children: [
                //         {
                //             text: 'Selling',
                //             url: '/sellingScript',
                //             seal: '../../public/images/broker-seal.png',
                //         },
                //         {
                //             text: 'Investment',
                //             url: '/investmentScript',
                //             seal: '../../public/images/developer-seal.png',
                //         },
                //         {
                //             text: 'Networking',
                //             url: '/networkingScript',
                //             seal: '../../public/images/investor-seal.png',
                //         },
                //         {
                //             text: 'Door Knocking',
                //             url: '/doorScript',
                //             seal: '../../public/images/investor-seal.png',
                //         },
                //         {
                //             text: 'Assistants',
                //             url: '/assistScript',
                //             seal: '../../public/images/investor-seal.png',
                //         },
                //         {
                //             text: 'Referrals',
                //             url: '/referralScript',
                //             seal: '../../public/images/investor-seal.png',
                //         },
                //         {
                //             text: 'Marketing',
                //             url: '/marketingScript',
                //             seal: '../../public/images/investor-seal.png',
                //         },
                //         {
                //             text: 'Prospecting',
                //             url: '/prospectingSCript',
                //             seal: '../../public/images/investor-seal.png',
                //         },
                //         {
                //             text: 'Calls',
                //             url: '/callScript',
                //             seal: '../../public/images/investor-seal.png',
                //         },
                //         {
                //             text: 'Buying',
                //             url: '/buyingScript',
                //             seal: '../../public/images/investor-seal.png',
                //         }
                //     ]
                // },
            ]
        }),
        props: {
            source: String
        },
        methods : {
            dashboardActive : function() {
                if(this.$router.currentRoute.path== '/' || this.$router.currentRoute.path== '/dashboard'){
                    $('.v-list .no-child:nth-child(1) .v-list__tile.v-list__tile--link').addClass('primary--text v-list__tile--active');
                }
                else {
                    $('.v-list .no-child:nth-child(1) .v-list__tile.v-list__tile--link').removeClass('primary--text v-list__tile--active');
                }
            }
        },
        mounted : function(){
            //console.log(this.$router.currentRoute.path);
            this.dashboardActive();
            if ($(window).width() > 1264) {
                $('.v-toolbar__side-icon').click(function () {

                    if($('.backend-left-menu').hasClass("v-navigation-drawer--open")){
                        $('.main-jedi-menu').css("cssText","padding-left: 0px !important");
                    }else{
                        $('.main-jedi-menu').css("cssText","padding-left: 260px !important");
                    }
                })
            }
            else {
                return false;
            }
        }
    }
</script>