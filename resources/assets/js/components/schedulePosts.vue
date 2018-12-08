<template>
    <v-container grid-list-md text-xs-center class="pa-0 pb-4">
        <v-layout row wrap>
            <v-flex xs12>
                <v-card flat>
                    <v-card-text class="pl-0 pb-3 step-heading">
                        <h2 class="textColor--text mb-2">Scheduled Posts</h2>
                        <p>Choose which networks you want to promote your products on, how often and at what time!</p>
                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex xs10 class="offset-xs1">
                <v-layout row wrap>
                    <v-flex sm6>
                        <v-card-text class="text-xs-left" style="font-size: 19px;">Your Scheduled Posts:</v-card-text>
                    </v-flex>
                    <v-flex sm6>
                        <v-layout row wrap>
                            <v-flex sm8>
                                <v-select
                                        :items="daysListed"
                                        label="Show posts for"
                                        outline
                                        hide-details
                                        class="sg-app-textfield"
                                ></v-select>
                            </v-flex>
                            <v-flex sm4>
                                <v-select
                                        :items="socialListed"
                                        label="Filter by"
                                        outline
                                        hide-details
                                        class="sg-app-textfield"
                                ></v-select>
                            </v-flex>
                        </v-layout>
                    </v-flex>
                </v-layout>
            </v-flex>
            <v-flex xs10 class="offset-xs1" my-4>
                <v-layout row wrap pa-3 class="listed-sch-posts">
                    <v-flex md2>
                        <img class="post-img" src="../../images/product-4.jpg">
                    </v-flex>
                    <v-flex md7>
                        <h3 class="post-title font-weight-light textColor--text text-xs-left">Bleach Hoodie Hollow Ichigo </h3>
                        <h4 class="post-sch font-regular grey--text lighten-4 mt-2 text-xs-left">Scheduled for:</h4>
                        <v-card flat>
                            <v-card-text class="pb-0">
                                <v-layout row wrap>
                                    <v-flex sm3 class="text-xl-left" style="font-size: 16px">
                                        (Clothing)
                                    </v-flex>
                                    <v-flex sm5 class="text-xl-left" style="font-size: 17px">
                                        Oct 24, 2018 @ 05:53 PM
                                    </v-flex>
                                    <v-flex sm1 class="pa-0">
                                        <v-btn flat depressed class="ma-0 pa-0">
                                            <i class="material-icons black--text">
                                                delete
                                            </i>
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                        </v-card>
                        <v-card flat>
                            <v-card-text class="pb-0">
                                <v-layout row wrap>
                                    <v-flex sm3 class="text-xl-left" style="font-size: 16px">
                                        (Painting)
                                    </v-flex>
                                    <v-flex sm5 class="text-xl-left" style="font-size: 17px">
                                        Oct 25, 2018 @ 06:15 PM
                                    </v-flex>
                                    <v-flex sm1 class="pa-0">
                                        <v-btn flat depressed class="ma-0 pa-0">
                                            <i class="material-icons black--text">
                                                delete
                                            </i>
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                        </v-card>
                        <v-card flat>
                            <v-card-text class="pb-0">
                                <v-layout row wrap>
                                    <v-flex sm3 class="text-xl-left" style="font-size: 16px">
                                        (Clothing)
                                    </v-flex>
                                    <v-flex sm5 class="text-xl-left" style="font-size: 17px">
                                        Oct 24, 2018 @ 05:53 PM
                                    </v-flex>
                                    <v-flex sm1 class="pa-0">
                                        <v-btn flat depressed class="ma-0 pa-0">
                                            <i class="material-icons black--text">
                                                delete
                                            </i>
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                        </v-card>
                    </v-flex>
                    <v-flex md3 class="post-action-cta">
                        <v-btn flat class="ctaColor white--text mr-0 mt-0 right appCta without-icon right">Remove</v-btn>
                        <v-btn flat class="tableHead  appCta mt-0 backAppCta without-icon right">Edit</v-btn>
                    </v-flex>
                </v-layout>
            </v-flex>
            <v-flex xs10 class="offset-xs1">
                <v-layout row wrap pa-3 class="listed-sch-posts">
                    <v-flex md2>
                        <img class="post-img" src="../../images/product-4.jpg">
                    </v-flex>
                    <v-flex md7>
                        <h3 class="post-title font-weight-light textColor--text text-xs-left">Bleach Hoodie Hollow Ichigo </h3>
                        <h4 class="post-sch font-regular grey--text lighten-4 mt-2 text-xs-left">Scheduled for:</h4>
                        <v-card flat>
                            <v-card-text class="pb-0">
                                <v-layout row wrap>
                                    <v-flex sm3 class="text-xl-left" style="font-size: 16px">
                                        (Clothing)
                                    </v-flex>
                                    <v-flex sm5 class="text-xl-left" style="font-size: 17px">
                                        Oct 24, 2018 @ 05:53 PM
                                    </v-flex>
                                    <v-flex sm1 class="pa-0">
                                        <v-btn flat depressed class="ma-0 pa-0">
                                            <i class="material-icons black--text">
                                                delete
                                            </i>
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                        </v-card>
                        <v-card flat>
                            <v-card-text class="pb-0">
                                <v-layout row wrap>
                                    <v-flex sm3 class="text-xl-left" style="font-size: 16px">
                                        (Painting)
                                    </v-flex>
                                    <v-flex sm5 class="text-xl-left" style="font-size: 17px">
                                        Oct 25, 2018 @ 06:15 PM
                                    </v-flex>
                                    <v-flex sm1 class="pa-0">
                                        <v-btn flat depressed class="ma-0 pa-0">
                                            <i class="material-icons black--text">
                                                delete
                                            </i>
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                        </v-card>
                        <v-card flat>
                            <v-card-text class="pb-0">
                                <v-layout row wrap>
                                    <v-flex sm3 class="text-xl-left" style="font-size: 16px">
                                        (Clothing)
                                    </v-flex>
                                    <v-flex sm5 class="text-xl-left" style="font-size: 17px">
                                        Oct 24, 2018 @ 05:53 PM
                                    </v-flex>
                                    <v-flex sm1 class="pa-0">
                                        <v-btn flat depressed class="ma-0 pa-0">
                                            <i class="material-icons black--text">
                                                delete
                                            </i>
                                        </v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                        </v-card>
                    </v-flex>
                    <v-flex md3 class="post-action-cta">
                        <v-btn flat class="ctaColor white--text mr-0 mt-0 right appCta without-icon right">Remove</v-btn>
                        <v-btn flat class="tableHead  appCta mt-0 backAppCta without-icon right">Edit</v-btn>
                    </v-flex>
                </v-layout>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    export default {
        data(){
            return{
                daysListed: ['Today','Tomorrow','7 Days','15 Days','30 Days','All Time'],
                socialListed:['Facebook','Twitter','Instagram','Pinterest','All Network']
            }
        }
    }
</script>