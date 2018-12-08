<template>
    <v-container grid-list-md text-xs-center class="pa-0 pb-5">
        <v-layout row wrap>
            <v-flex xs12>
                <v-card flat>
                    <v-card-text class="pl-0 pb-3 step-heading">
                        <h2 class="textColor--text mb-2">Populate Your Sprint With Products!</h2>
                        <p>Add the products you would like to promote on your social networks over here! </p>
                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex lg6 class="pr-4">
                <v-card flat>
                    <v-layout row wrap class="inner-config-heading">
                            <v-flex xs1>
                                <v-card-text class="icon-header">
                                    <i class="material-icons black--text pt-0">
                                        send
                                    </i>
                                </v-card-text>
                            </v-flex>
                            <v-flex xs11>
                                <v-card-text class="text-xs-left head-portion">
                                    Post Messaging
                                </v-card-text>
                                <v-card-text class="pt-2 pl-2 pb-0">
                                    <v-layout row wrap class="">
                                        <v-flex xs12 class="pa-0">
                                            <v-card flat color="tableHead" class="text-xs-left tableHead pl-4 textColor--text pt-1 pb-1 post-img-upload" style="border-radius:0">
                                                <span>Write below what you would like to share</span>
                                                <v-btn color="black" dark flat class="right">
                                                    <v-icon class="mr-2 ml-0" dark >backup</v-icon>Upload Image
                                                </v-btn>
                                                <div class="clearfix" style="clear:both"></div>
                                            </v-card>
                                        </v-flex>
                                        <v-flex xs12>
                                            <v-layout row wrap class="main-post-area">
                                                <v-flex xs8>
                                                    <v-textarea
                                                            solo
                                                            name="input-7-4"
                                                            label="Solo textarea"
                                                            placeholder="Write what you would like to share"
                                                            no-resize="true"
                                                            rows="6"
                                                            flat
                                                    ></v-textarea>
                                                </v-flex>
                                                <v-flex xs4>
                                                    <div class="post-avatar mt-3 mr-3">
                                                        <img src="../../images/imgPlaceHolder.png">
                                                    </div>
                                                    </div>
                                                </v-flex>
                                            </v-layout>
                                        </v-flex>
                                    </v-layout>
                                </v-card-text>
                            </v-flex>
                        </v-layout>
                </v-card>
                <v-card flat>
                        <v-layout row wrap class="inner-config-heading">
                            <v-flex xs1>
                                <v-card-text class="icon-header">
                                    <i class="material-icons black--text pt-0">
                                        share
                                    </i>
                                </v-card-text>
                            </v-flex>
                            <v-flex xs11>
                                <v-card-text class="text-xs-left head-portion">
                                    To get started, let's select the social platforms that you want to promote your store on:
                                </v-card-text>
                                <v-card-text>
                                    <v-layout row wrap class="listed-social-pages">
                                        <v-flex>
                                            <v-layout row wrap>
                                            <v-flex xs2 class="selected-platform">
                                                <v-checkbox
                                                        v-model="ex4"
                                                        label=""
                                                        color="primary"
                                                        value="Facebook"
                                                        hide-details
                                                ></v-checkbox>
                                                <i style="font-size:20px" class="fab fa-facebook-f primary--text"></i>
                                            </v-flex>
                                            <v-flex xs8 class="pl-2">
                                                <v-select
                                                        :items="fbPages"
                                                        item-text="value"
                                                        item-value="key"
                                                        label="Select Page"
                                                        outline
                                                        hide-details
                                                ></v-select>
                                            </v-flex>
                                        </v-layout>
                                        </v-flex>
                                        <v-flex>
                                            <v-layout row wrap>
                                                <v-flex xs2 class="selected-platform">
                                                    <v-checkbox
                                                            v-model="ex4"
                                                            label=""
                                                            color="primary"
                                                            value="Twitter"
                                                            hide-details
                                                    ></v-checkbox>
                                                    <i style="font-size: 20px" class="fab fa-twitter primary--text"></i>
                                                </v-flex>
                                                <v-flex xs8 class="pl-2">
                                                    <v-select
                                                            :items="fbPages"
                                                            label="Select Page"
                                                            outline
                                                            hide-details
                                                    ></v-select>
                                                </v-flex>
                                            </v-layout>
                                        </v-flex>
                                        <v-flex>
                                            <v-layout row wrap>
                                                <v-flex xs2 class="selected-platform">
                                                    <v-checkbox
                                                            v-model="ex4"
                                                            label=""
                                                            color="primary"
                                                            value="Instagram"
                                                            hide-details
                                                    ></v-checkbox>
                                                    <i style="font-size: 20px" class="fab fa-instagram primary--text"></i>
                                                </v-flex>
                                                <v-flex xs8 class="pl-2">
                                                    <v-select
                                                            :items="fbPages"
                                                            label="Select Page"
                                                            outline
                                                            hide-details
                                                    ></v-select>
                                                </v-flex>
                                            </v-layout>
                                        </v-flex>
                                        <v-flex>
                                            <v-layout row wrap>
                                                <v-flex xs2 class="selected-platform">
                                                    <v-checkbox
                                                            v-model="ex4"
                                                            label=""
                                                            color="primary"
                                                            value="Pinterest"
                                                            hide-details
                                                    ></v-checkbox>
                                                    <i style="font-size: 20px" class="fab fa-pinterest primary--text"></i>
                                                </v-flex>
                                                <v-flex xs8 class="pl-2">
                                                    <v-select
                                                            :items="fbPages"
                                                            label="Select Page"
                                                            outline
                                                            hide-details
                                                    ></v-select>
                                                </v-flex>
                                            </v-layout>
                                        </v-flex>
                                    </v-layout>
                                </v-card-text>
                            </v-flex>
                        </v-layout>
                    </v-card>
                <v-card flat>
                    <v-layout row wrap class="inner-config-heading pt-0">
                        <v-flex xs1>
                            <v-card-text class="icon-header pt-0">
                                <i class="material-icons black--text pt-0">
                                    label
                                </i>
                            </v-card-text>
                        </v-flex>
                        <v-flex xs11 class="pt-0">
                            <v-card-text class="text-xs-left head-portion pb-0 pt-0">
                                Suggested Hastags
                            </v-card-text>
                            <v-card-text class="pb-0">
                                <v-textarea
                                        solo
                                        name="input-7-4"
                                        label=""
                                        placeholder="#Hastag1 #Hashtag2 #Hastag3"
                                        no-resize="true"
                                        rows="5"
                                        value=""
                                ></v-textarea>
                            </v-card-text>
                        </v-flex>
                        <v-flex xs11 class="offset-xs1 pa-0 pr-4 pl-2">
                            <v-btn style="width:100%" @click="snackbar = true" depressed class="ctaColor white--text mr-0 appCta sched-post-cta">
                                Schedule Your Post
                            </v-btn>
                        </v-flex>
                    </v-layout>
                </v-card>
            </v-flex>
            <v-flex lg6 class="mt-3 inner-config-heading">
                <v-card flat height="500">
                    <v-card-text class="text-xs-left pl-0 pb-2 head-portion">Preview:</v-card-text>
                    <v-tabs grow v-model="active" color="primary" dark slider-color="blue lighten-2">
                        <v-tab ripple>Facbook</v-tab>
                        <v-tab-item>
                            <v-card flat>
                                <v-card-text>
                                    <img style="width:100%" src="../../images/preview-socail.png">
                                </v-card-text>
                            </v-card>
                        </v-tab-item>
                        <v-tab ripple>Twitter</v-tab>
                        <v-tab-item>
                            <v-card flat>
                                <v-card-text>
                                    <img style="width:100%" src="../../images/preview-socail.png">
                                </v-card-text>
                            </v-card>
                        </v-tab-item>
                        <v-tab ripple>Instagram</v-tab>
                        <v-tab-item>
                            <v-card flat>
                                <v-card-text>
                                    <img style="width:100%" src="../../images/preview-socail.png">
                                </v-card-text>
                            </v-card>
                        </v-tab-item>
                        <v-tab ripple>Pinterest</v-tab>
                        <v-tab-item>
                            <v-card flat>
                                <v-card-text>
                                    <img style="width:100%" src="../../images/preview-socail.png">
                                </v-card-text>
                            </v-card>
                        </v-tab-item>
                    </v-tabs>
                </v-card>
                <v-card>

                </v-card>
            </v-flex>
            <v-flex xs12>
                <!--<v-btn depressed color="orange darken-3" @click="snackbar = true">Schedule Post!</v-btn>-->
                <v-snackbar
                        v-model="snackbar"
                        bottom="true"
                >
                    Your post has been scheduled!
                    <v-btn
                            class="pt-0 pb-0"
                            color="primary--text"
                            flat
                            @click="snackbar = false"
                    >
                        Close
                    </v-btn>
                </v-snackbar>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    export default {
        data(){
            return{
                ex4 : '',
                snackbar : false,
                fbPages:['Page 1','Page 2','Page 3','Page 4','Page 5'],
                selectedPage : '',
            }
        },
        methods: {
            loadFbpages (){
                let self = this;
                axios.get('/fb-pages').then(function (response) {
                    if (response.data.status == 'success'){
                        console.log(response.data.pages);
                        self.fbPages = JSON.parse(response.data.pages);
                    } else {
                        self.fbPages = [{'key' : '', 'value' : 'No Page Found'}];
                    }
                });
            },
        },
        created(){
            this.loadFbpages();
        }
    }
</script>