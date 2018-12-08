<template>
    <v-container grid-list-md text-xs-center class="pa-0 pb-5">
        <v-layout row wrap>
            <v-flex xs12>
                <v-card flat>
                    <v-card-text class="pl-0 pb-3 step-heading">
                        <h2 class="textColor--text mb-2">Social Configuration</h2>
                        <p>Choose which networks you want to promote your products on, how often and at what time!</p>
                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex sm12>
                <v-card flat>
                    <v-layout row wrap class="inner-config-heading">
                        <v-flex xs1>
                            <v-card-text class="icon-header">
                                <i class="material-icons black--text pt-1">
                                    store
                                </i>
                            </v-card-text>
                        </v-flex>
                        <v-flex xs11 sm8>
                            <v-card-text class="text-xs-left head-portion">
                                Connect Your Store
                            </v-card-text>
                            <v-card-text class="pt-2 pl-2 pb-0">
                                <v-layout row wrap class="connect-store">
                                    <v-flex xs6 sm4 md3 v-for="(stores,index) in listedStores" :key="index">
                                        <v-btn flat>
                                            <img :src="stores.logo">
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                            <v-card-text class="text-xs-left">
                                <v-btn flat dark color="black darken-3" class="back-cta add-shop-cta right mr-3">
                                    <v-icon dark class="mr-4">add</v-icon>
                                    Add Another Shop!
                                </v-btn>
                            </v-card-text>
                        </v-flex>
                    </v-layout>
                </v-card>
            </v-flex>
            <v-flex sm12>
                <v-card flat>
                    <v-layout row wrap class="inner-config-heading">
                        <v-flex xs1>
                            <v-card-text class="icon-header">
                                <i class="material-icons black--text pt-1">
                                    share
                                </i>
                            </v-card-text>
                        </v-flex>
                        <v-flex xs11>
                            <v-card-text class="text-xs-left head-portion">
                                Connect Your Social Networks
                            </v-card-text>
                            <v-card-text class="pt-2 pl-2 pb-0">
                                <v-layout row wrap class="connect-store">
                                    <v-flex xs6 sm2 v-for="(social,index) in listedSocial" :key="index">
                                        <v-btn flat @click.prevent="loginPopup($event)">
                                            <img :src="social.logo">
                                        </v-btn>
                                        <div class="user-info mt-2">
                                            <span class="user-name">Liza Cohen</span>
                                            <a href="#">Logout</a>
                                            <div class="clearfix"></div>
                                        </div>
                                        <div :id="'logout-'+social.name" @click.prevent="logoutPopup($event)">
                                        </div>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                        </v-flex>
                    </v-layout>
                </v-card>
            </v-flex>
            <v-flex sm12>
                <v-card flat>
                    <v-layout row wrap class="inner-config-heading">
                        <v-flex xs1>
                            <v-card-text class="icon-header">
                                <i class="material-icons black--text pt-1">
                                    insert_chart
                                </i>
                            </v-card-text>
                        </v-flex>
                        <v-flex xs11>
                            <v-card-text class="text-xs-left head-portion">
                                Connect Your Analytics
                            </v-card-text>
                            <v-card-text class="pt-2 pl-2 pb-0">
                                <v-layout row wrap class="connect-store">
                                    <v-flex xs6 sm2>
                                        <v-btn flat>
                                            <img style="max-width: none;" src="../../images/google-analytics-logo.png">
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                        </v-flex>
                    </v-layout>
                </v-card>
            </v-flex>
            <v-flex sm12>
                <v-card flat>
                    <v-layout row wrap class="inner-config-heading">
                        <v-flex xs1>
                            <v-card-text class="icon-header">
                                <i class="material-icons black--text pt-1">
                                    access_time
                                </i>
                            </v-card-text>
                        </v-flex>
                        <v-flex xs11>
                            <v-card-text class="text-xs-left head-portion">
                                Select Your Time Zone
                            </v-card-text>
                            <v-card-text class="pt-2 pl-2 pb-0">
                                <v-layout row wrap class="connect-store">
                                    <v-flex xs12 sm6 d-flex>
                                        <v-select
                                                :items="timeZone"
                                                label="Choose Time Zone"
                                                outline
                                                hide-details
                                                class="sg-app-textfield"
                                        ></v-select>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                        </v-flex>
                    </v-layout>
                </v-card>
            </v-flex>
            <v-flex sm12>
                <v-card flat>
                    <v-layout row wrap class="inner-config-heading">
                        <v-flex xs1>
                            <v-card-text class="icon-header">
                                <i class="material-icons black--text pt-1">
                                    cloud_circle
                                </i>
                            </v-card-text>
                        </v-flex>
                        <v-flex xs9>
                            <v-card-text class="text-xs-left head-portion">
                                Create Your Hashtag Clouds
                                <span>Get found by your customers by using the right hashtags with the right products!</span>
                            </v-card-text>
                            <v-card-text class="pt-2 pl-2 pb-0 all-listed-tags">
                                <v-layout v-for="(hashtags,index) in usersHashtags" :key="index" row wrap class="create-hashtags">
                                   <v-flex sm6>
                                       <v-card-text class="hastag-head">Conditions</v-card-text>
                                       <v-layout row wrap>
                                           <v-flex sm1 class="pt-0">
                                               <v-card-text style="font-size: 22px;">If</v-card-text>
                                           </v-flex>
                                           <v-flex sm3>
                                               <v-select
                                                       :items="collections"
                                                       label="Select"
                                                       outline
                                                       hide-details
                                                       class="sg-app-textfield"
                                               ></v-select>
                                           </v-flex>
                                           <v-flex sm3>
                                               <v-select
                                                       :items="condition"
                                                       label="Select"
                                                       outline
                                                       hide-details
                                                       class="sg-app-textfield"
                                               ></v-select>
                                           </v-flex>
                                           <v-flex sm5>
                                               <v-text-field
                                                       label="Enter Title"
                                                       outline
                                                       hide-details
                                                       class="sg-app-textfield"
                                               ></v-text-field>
                                           </v-flex>
                                       </v-layout>
                                   </v-flex>
                                    <v-flex sm5>
                                        <v-card-text style="padding-left: 16px;" class="hastag-head">Enter Hashtags</v-card-text>
                                        <v-card-text style="padding-top:4px" class="hastags-area">
                                            <v-combobox
                                                    v-model="model"
                                                    :filter="filter"
                                                    :hide-no-data="!search"
                                                    :items="items"
                                                    :search-input.sync="search"
                                                    hide-selected
                                                    label="Search for an option"
                                                    multiple
                                                    small-chips
                                                    outline
                                                    hide-details
                                                    class="sg-app-textfield"
                                            >
                                                <template slot="no-data">
                                                    <v-list-tile>
                                                        <span class="subheading">Create</span>
                                                        <v-chip
                                                                :color="`${colors[nonce - 1]} lighten-3`"
                                                                label
                                                                small
                                                        >
                                                            {{ search }}
                                                        </v-chip>
                                                    </v-list-tile>
                                                </template>
                                                <template
                                                        v-if="item === Object(item)"
                                                        slot="selection"
                                                        slot-scope="{ item, parent, selected }"
                                                >
                                                    <v-chip
                                                            :color="`${item.color} lighten-3`"
                                                            :selected="selected"
                                                            label
                                                            small
                                                    >
        <span class="pr-2">
          {{ item.text }}
        </span>
                                                        <v-icon
                                                                small
                                                                @click="parent.selectItem(item)"
                                                        >close</v-icon>
                                                    </v-chip>
                                                </template>
                                                <template
                                                        slot="item"
                                                        slot-scope="{ index, item, parent }"
                                                >
                                                    <v-list-tile-content>
                                                        <v-text-field
                                                                v-if="editing === item"
                                                                v-model="editing.text"
                                                                autofocus
                                                                flat
                                                                background-color="transparent"
                                                                hide-details
                                                                solo
                                                                @keyup.enter="edit(index, item)"
                                                        ></v-text-field>
                                                        <v-chip
                                                                v-else
                                                                :color="`${item.color} lighten-3`"
                                                                dark
                                                                label
                                                                small
                                                        >
                                                            {{ item.text }}
                                                        </v-chip>
                                                    </v-list-tile-content>
                                                    <v-spacer></v-spacer>
                                                    <v-list-tile-action @click.stop>
                                                        <v-btn
                                                                icon
                                                                @click.stop.prevent="edit(index, item)"
                                                        >
                                                            <v-icon>{{ editing !== item ? 'edit' : 'check' }}</v-icon>
                                                        </v-btn>
                                                    </v-list-tile-action>
                                                </template>
                                            </v-combobox>
                                        </v-card-text>
                                    </v-flex>
                                    <v-flex sm1 style="max-width:50px">
                                        <v-btn fab dark small color="primary remove-tags" @click="removeHastag(index)">
                                            <v-icon dark>remove</v-icon>
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                                <v-layout row wrap>
                                    <v-flex sm11>
                                        <v-btn flat class="add-shop-cta right back-cta" @click="addHashtag">
                                            <v-icon left>add</v-icon>Create Another Hastag Cloud
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                                <v-layout row wrap>
                                    <v-flex xs11>
                                        <v-btn depressed class="appCta without-icon mr-0 mt-4 right white--text" color="ctaColor" @click="snackbar = true">Save Settings</v-btn>
                                    </v-flex>
                                </v-layout>
                                <v-snackbar
                                        v-model="snackbar"
                                        bottom="true"
                                >
                                    Settings has been saved!
                                    <v-btn
                                            class="pt-0 pb-0"
                                            color="primary--text"
                                            flat
                                            @click="snackbar = false"
                                    >
                                        Close
                                    </v-btn>
                                </v-snackbar>
                            </v-card-text>
                        </v-flex>
                    </v-layout>
                </v-card>
            </v-flex>
            <!--<v-flex xs12 class="next-step-cta">-->
                <!--<v-btn depressed color="orange darken-3" @click="snackbar = true">Save Settings</v-btn>-->
                <!--<v-snackbar-->
                        <!--v-model="snackbar"-->
                        <!--bottom="true"-->
                <!--&gt;-->
                    <!--Settings has been saved!-->
                    <!--<v-btn-->
                            <!--class="pt-0 pb-0"-->
                            <!--color="primary&#45;&#45;text"-->
                            <!--flat-->
                            <!--@click="snackbar = false"-->
                    <!--&gt;-->
                        <!--Close-->
                    <!--</v-btn>-->
                <!--</v-snackbar>-->
            <!--</v-flex>-->
        </v-layout>
    </v-container>
</template>

<script>
    export default {
        data() {
            return {
                snackbar : false,
                activator: null,
                attach: null,
                colors: ['green', 'purple', 'indigo', 'cyan', 'teal', 'orange'],
                editing: null,
                index: -1,
                items: [
                    {header: 'Select an option or create one'},
                    {
                        text: 'Hashtag1',
                        color: 'blue'
                    },
                    {
                        text: 'Hashtag2',
                        color: 'red'
                    }
                ],
                nonce: 1,
                menu: false,
                model: [
                    {
                        text: 'Hashtag3',
                        color: 'blue'
                    }
                ],
                x: 0,
                search: null,
                y: 0,
                collections: ['Collection', 'Tag', 'Type'],
                condition: ['Contains', 'Equals', 'Does not contain'],
                listedStores: [
                    {
                        logo: '../../images/shopify-logo.png'
                    },
                    {
                        logo: '../../images/big-comm.png'
                    },
                    {
                        logo: '../../images/ebay-logo.png'
                    },
                    {
                        logo: '../../images/woo-comm.png'
                    }
                ],
                listedSocial: [
                    {
                        logo: '../images/fb-logo.png',
                        link : '',
                        name: 'facebook',
                    },
                    {
                        logo: '../images/twitter-logo.png',
                        link : '',
                        name: 'twitter'
                    },
                    {
                        logo: '../images/instagram.png',
                        link : '',
                        name: 'instagram'
                    },
                    {
                        logo: '../images/pinterest-logo.png',
                        link : '',
                        name: 'pinterest'
                    }
                ],
                usersHashtags: [
                    {
                        conditionType: '',
                        actualCondtion: '',
                        hastagTitle: '',
                        hastags: []
                    }
                ],
                 timeZone : [
                     "(GMT-12:00) International Date Line West",
                     "(GMT-11:00) Midway Island, Samoa",
                     "(GMT-10:00) Hawaii",
                     "(GMT-09:00) Alaska",
                     "(GMT-08:00) Pacific Time (US & Canada)",
                     "(GMT-12:00) International Date Line West",
                     "(GMT-11:00) Midway Island, Samoa",
                     "(GMT-10:00) Hawaii",
                     "(GMT-09:00) Alaska",
                     "(GMT-08:00) Pacific Time (US & Canada)"
                ]
            }
        },
        watch: {
            model(val, prev) {
                if (val.length === prev.length) return;

                this.model = val.map(v => {
                    if (typeof v === 'string') {
                        v = {
                            text: v,
                            color: this.colors[this.nonce - 1]
                        }

                        this.items.push(v);

                        this.nonce++
                    }

                    return v;
                })
            }
        },

        methods: {
            loginPopup(event){
                var element = event.currentTarget;
                var url = $(element).val();
                window.open(url, "_blank", "location=0,menubar=0,toolbar=no,scrollbars=yes,resizable=yes,top=500,left=500,width=600,height=450");
            },
            logoutPopup(event){
                // get current clicked
                var element = event.currentTarget;
                var url = $(element).find('a').attr('href');
                window.open(url, "_blank", "location=0,menubar=0,toolbar=no,scrollbars=yes,resizable=yes,top=500,left=500,width=600,height=450");
                //this.loadSocial();
            },
            edit(index, item) {
                if (!this.editing) {
                    this.editing = item;
                    this.index = index;
                } else {
                    this.editing = null;
                    this.index = -1
                }
            },
            filter(item, queryText, itemText) {
                if (item.header) return false;

                const hasValue = val => val != null ? val : '';

                const text = hasValue(itemText);
                const query = hasValue(queryText);

                return text.toString()
                    .toLowerCase()
                    .indexOf(query.toString().toLowerCase()) > -1
            },
            addHashtag: function(){
                this.usersHashtags.push({
                    conditionType:'',
                    actualCondtion:'',
                    hastagTitle: '',
                    hastags: []
                });
            },
            removeHastag : function(index){
                this.usersHashtags.splice(index,1);
            },
            loadSocial (){
                let self = this;
                axios.get('/social-media-config').then(function (response) {
                    if (response.data != '') {
                        $.each(response.data, function( index, value ) {
                            self.setSocialLink(value);
                        });
                    }
                });
            },
            setSocialLink(data){
                if ($('#logout-'+data.platform).length > 0){
                    $('#logout-'+data.platform).html(data.logoutLink);
                    $('#logout-'+data.platform).siblings('button').val(data.loginLink);
                }
            },
        },
        mounted(){
            let self = this;
            self.loadSocial();
        },
    }
</script>