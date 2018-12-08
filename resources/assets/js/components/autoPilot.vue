<template>
    <v-container grid-list-md text-xs-center class="pa-0 pb-4">
        <v-layout row wrap v-show="showStep0">
            <v-flex xs12 class="step0">
                <v-card flat>
                    <h3 class="text-xs-center mb-4">Let's name your social sprint!</h3>
                    <v-text-field
                            v-model="settings.sname"
                            :rules="nameRules"
                            :counter="50"
                            label="Name Your Auto Sprint"
                            required
                            solo
                            flat
                            v-on:keyup.enter="StepOneActive"
                            class="getting-sprint-name"
                    ></v-text-field>
                    <v-layout row wrap class="tableHead pt-3 pl-3 pr-3 pb-2">
                        <v-flex sm1>
                            <img class="main-tip-icon" src="../../images/main-tip-icon.png">
                        </v-flex>
                        <v-flex sm11 class="pb-0 pt-0">
                            <p class="mb-0 pa-3 text-xs-left pl-5 main-tip-content" style="font-size: 18px;">
                                <strong style="display: block; font-size: 18px;" class="mr-2">Tip:</strong> If you are promoting a particular type of product or collection, try to use that in name to make your sprint more identifiable in the dashboard!
                            </p>
                        </v-flex>
                    </v-layout>
                    <v-btn @click="StepOneActive" depressed class="ctaColor white--text mr-0 mt-4 right appCta">
                        Next Step
                        <v-icon right class="ml-1">arrow_right</v-icon>
                    </v-btn>
                </v-card>
                <loading style="float:right"
                         :show="loadingShow"
                         :label="label">
                </loading>
            </v-flex>
            <!--<v-flex xs12 class="next-step-cta">-->
                <!--<v-progress-linear height="18" v-model="sprintProgress"></v-progress-linear>-->
                <!--<p>{{sprintProgress}}%</p>-->
                <!--<v-btn @click="StepOneActive" depressed color="orange darken-3">Next Step: Select Products</v-btn>-->
            <!--</v-flex>-->
        </v-layout>
        <v-layout row wrap v-show="showStep1">
            <v-flex xs12>
                <v-card flat>
                    <v-card-text class="pl-0 pb-3 step-heading">
                        <h2 class="textColor--text mb-2">Populate Your Sprint With Products!</h2>
                        <p>Add the products you would like to promote on your social networks over here! </p>
                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex xs12 class="sprint-tooltip">
                <v-tooltip bottom color="orange darken-2">
                    <v-icon
                            slot="activator"
                            color="orange darken-2"
                            dark
                            size="32px"
                    >whatshot</v-icon>
                    <span>You can create a sprint to promote your best-selling products, a specific collection or ones with a specific tag!</span>
                </v-tooltip>
            </v-flex>
            <v-flex xs12>
                <v-card-text class="top-search-bar">
                    <v-text-field type="text" 
                        v-model="psearch"
                        color="primary"
                        hide-no-data
                        hide-selected
                        item-text="Description"
                        item-value="API"
                        label=""
                        placeholder="Type To Search Your Products"
                        prepend-icon="mdi-database-search"
                        return-object
                        class="product-search-bar"
                     ></v-text-field>
                </v-card-text>
            </v-flex>
            <v-flex md2>
                <v-card flat class="filter-portion">
                    <v-card-text class="px-0 text-xs-left textColor--text" style="font-size: 18px">Filter Your Products</v-card-text>
                    <!--<v-select :items="filterOptionsFunc" v-model="selectedFilteredOption" box label="Select Filter" @change="filterChange()" ></v-select>-->
                    <v-expansion-panel class="listed-tags-down">
                        <v-expansion-panel-content>
                            <div slot="header">General</div>
                            <v-card>
                                <v-card-text>
                                    <v-radio-group v-model="filteredString" :mandatory="false">
                                        <v-radio  color="primary" class="mt-0" v-for="(filter, index) in filters" :key="index" :value="filter.value" :label="filter.value" @change="CheckboxFilteration(filter)"></v-radio>
                                    </v-radio-group>
                                </v-card-text>
                            </v-card>
                        </v-expansion-panel-content>
                        <v-expansion-panel-content>
                            <div slot="header">Collections</div>
                            <v-card>
                                <v-card-text>
                                    <v-radio-group v-model="filteredString" :mandatory="false">
                                        <v-radio  color="primary" class="mt-0" v-for="(filter, index) in collections" :key="index" :value="filter.value" :label="filter.value" @change="CheckboxFilteration(filter)"></v-radio>
                                    </v-radio-group>
                                </v-card-text>
                            </v-card>
                        </v-expansion-panel-content>
                    </v-expansion-panel>
                </v-card>
            </v-flex>
            <v-flex md10 style="margin-bottom: 60px;">
                <v-checkbox
                        label="Select All Shown"
                        v-model="selectAll"
                        @change="selectAllProducts"
                        color="primary"
                ></v-checkbox>
                 <loading style="float:right"
                    :show="loadingShow"
                    :label="label">
                </loading>
                <v-card flat>
                    <v-layout row wrap>
                        <v-flex v-for="(product, index) in productFilters" :key="index" lg3>
                            <v-card class="listed-products-shopify">
                                <v-card-text class="px-0">
                                    <v-btn @click="productSelected(index)" flat>
                                        <img :src="product.image.src"/>
                                        <p>{{product.title}}</p>
                                        <span class="beforeAdded" v-if="!product.isSelected">Add To Stack!</span>
                                        <span class="afterAdded" v-else>Added!</span>
                                    </v-btn>
                                    <span class="beforeAdded removeProduct" @click="removeFromStack(index)"  v-if="product.isSelected">
                                        <i class="material-icons close-icon">
                                            close
                                        </i>
                                    </span>
                                </v-card-text>
                            </v-card>
                            <v-flex xs12 class="next-step-cta">
                                <v-progress-linear height="18" v-model="sprintProgress"></v-progress-linear>
                                <p>{{sprintProgress}}%</p>
                                <v-btn style="float:left" @click="setStep0" flat depressed class="orange--text darken-3 back-cta">Back To Sprint Name</v-btn>
                                <v-btn :disabled="makeACtive" @click="nextStep" depressed color="orange darken-3">Next Step: Let's Schedule Your Post!</v-btn>
                            </v-flex>
                        </v-flex>
                    </v-layout>
                </v-card>
            </v-flex>
        </v-layout>
        <v-layout row wrap v-show="showStep2" class="step-two">
            <v-flex xs12>
                <v-card flat>
                    <v-card-text class="pl-0 pb-3 step-heading">
                        <h2 class="darken-3 primary--text">Autopilot Configuration</h2>
                        <p>Choose which networks you want to promote your products on, how often and at what time!</p>
                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex xs12 class="sprint-tooltip">
                <v-tooltip bottom color="orange darken-2">
                    <v-icon
                            slot="activator"
                            color="orange darken-2"
                            dark
                    >whatshot</v-icon>
                    <span>You can create a sprint to promote your best-selling products, a specific collection or ones with a specific tag!</span>
                </v-tooltip>
            </v-flex>
            <v-flex sm6>
                <v-card flat>
                    <v-layout row wrap class="inner-config-heading">
                        <v-flex xs1>
                            <v-card-text class="icon-header">
                                <i class="material-icons orange--text text--darken-2">
                                    share
                                </i>
                            </v-card-text>
                        </v-flex>
                        <v-flex xs11>
                            <v-card-text class="text-xs-left head-portion">
                                To get started, let's select the social platforms that you want to promote your store on:
                            </v-card-text>
                            <v-card-text>
                                <v-layout row wrap>
                                    <v-flex sm6>
                                        <v-layout row wrap>
                                            <v-flex xs2>
                                                <v-checkbox
                                                        v-model="settings.social.facebook.status"
                                                        color="primary"
                                                        value=""
                                                        hide-details
                                                ></v-checkbox>
                                            </v-flex>
                                            <v-flex xs1 class="pt-4">
                                                <i style="font-size:20px" class="fab fa-facebook-f fbColor--text"></i>
                                            </v-flex>
                                            <v-flex xs8 class="pl-2">
                                                <v-select
                                                        :disabled="!settings.social.facebook.status"
                                                        :items="settings.social.facebook.pages"
                                                        v-model="settings.social.facebook.page"
                                                        label="Select Page"
                                                ></v-select>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-layout row wrap>
                                            <v-flex xs2>
                                                <v-checkbox
                                                        v-model="settings.social.twitter.status"
                                                        color="primary"
                                                        value=""
                                                        hide-details
                                                ></v-checkbox>
                                            </v-flex>
                                            <v-flex xs1 class="pt-4">
                                                <i style="font-size: 20px" class="fab fa-twitter twitterColor--text"></i>
                                            </v-flex>
                                            <v-flex xs8 class="pl-2">
                                                <v-select
                                                        :disabled="!settings.social.twitter.status"
                                                        :items="settings.social.twitter.pages"
                                                        v-model="settings.social.twitter.page"
                                                        label="Select Page"
                                                ></v-select>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-layout row wrap>
                                            <v-flex xs2>
                                                <v-checkbox
                                                        v-model="settings.social.instagram.status"
                                                        color="primary"
                                                        value=""
                                                        hide-details
                                                ></v-checkbox>
                                            </v-flex>
                                            <v-flex xs1 class="pt-4">
                                                <i style="font-size: 20px" class="fab fa-instagram instaColor--text"></i>
                                            </v-flex>
                                            <v-flex xs8 class="pl-2">
                                                <v-select
                                                        :disabled="!settings.social.instagram.status"
                                                        :items="settings.social.instagram.pages"
                                                        v-model="settings.social.instagram.page"
                                                        label="Select Page"
                                                ></v-select>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                    <v-flex xs6>
                                        <v-layout row wrap>
                                            <v-flex xs2>
                                                <v-checkbox
                                                        v-model="settings.social.pinterest.status"
                                                        color="primary"
                                                        value=""
                                                        hide-details
                                                ></v-checkbox>
                                            </v-flex>
                                            <v-flex xs1 class="pt-4">
                                                <i style="font-size: 20px" class="fab fa-pinterest pintColor--text"></i>
                                            </v-flex>
                                            <v-flex xs8 class="pl-2">
                                                <v-select
                                                        :disabled="!settings.social.pinterest.status"
                                                        :items="settings.social.pinterest.pages"
                                                        v-model="settings.social.pinterest.page"
                                                        label="Select Page"
                                                ></v-select>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                        </v-flex>
                    </v-layout>
                </v-card>
            </v-flex>
            <v-flex sm6>
                <v-card flat>
                    <v-layout row wrap class="inner-config-heading">
                        <v-flex xs1>
                            <v-card-text class="icon-header">
                                <i class="material-icons orange--text text--darken-2">
                                    share
                                </i>
                            </v-card-text>
                        </v-flex>
                        <v-flex xs11>
                            <v-card-text class="text-xs-left head-portion">
                                What type of format should we use to promote your products?
                            </v-card-text>
                            <v-card-text class="prod-weights">
                                <v-layout row wrap>
                                    <v-flex xs12>
                                        <v-layout row wrap class="primary cal-weighted">
                                            <v-flex xs8>
                                                <v-card-text class="text-xs-left white--text hed-cal">Single Image</v-card-text>
                                            </v-flex>
                                            <v-flex xs2 offset-xs2 class="label-perct">
                                                <v-text-field
                                                        label="0"
                                                        solo
                                                        v-bind:value="settings.format[0].single"
                                                ></v-text-field>
                                                <span>%</span>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                    <v-flex xs12 class="mt-1 mb-1">
                                        <v-layout row wrap class="primary cal-weighted">
                                            <v-flex xs8>
                                                <v-card-text class="text-xs-left white--text hed-cal">GIFs</v-card-text>
                                            </v-flex>
                                            <v-flex xs2 offset-xs2 class="label-perct">
                                                <v-text-field
                                                        label="0"
                                                        solo
                                                        v-bind:value="settings.format[1].gif"
                                                ></v-text-field>
                                                <span>%</span>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                    <v-flex xs12>
                                        <v-layout row wrap class="primary cal-weighted">
                                            <v-flex xs8>
                                                <v-card-text class="text-xs-left white--text hed-cal">Collage</v-card-text>
                                            </v-flex>
                                            <v-flex xs2 offset-xs2 class="label-perct">
                                                <v-text-field
                                                        label="0"
                                                        solo
                                                        v-bind:value="settings.format[2].collage"
                                                ></v-text-field>
                                                <span>%</span>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                        </v-flex>
                    </v-layout>
                </v-card>
            </v-flex>
            <v-flex sm6>
                <v-card flat>
                    <v-layout row wrap class="inner-config-heading">
                        <v-flex xs1>
                            <v-card-text class="icon-header">
                                <i class="material-icons orange--text text--darken-2">
                                    send
                                </i>
                            </v-card-text>
                        </v-flex>
                        <v-flex xs11>
                            <v-card-text class="text-xs-left head-portion">
                                How Often do you want to post your products:
                            </v-card-text>
                            <v-card-text>
                                <v-layout row wrap class="post-days">
                                    <v-flex xs3>
                                        <v-text-field
                                                v-model="settings.post_product[0].no_of_post"
                                                :rules="nameRules"
                                                label=""
                                                required
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex xs4 pt-4>
                                        # of Post every
                                    </v-flex>
                                    <v-flex xs3>
                                        <v-text-field
                                                v-model="settings.post_product[1].every_days"
                                                :rules="nameRules"
                                                label=""
                                                required
                                        ></v-text-field>
                                    </v-flex>
                                    <v-flex xs1 pt-4>
                                        Days
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                        </v-flex>
                    </v-layout>
                </v-card>
            </v-flex>
            <v-flex sm6>
                <v-card flat>
                    <v-layout row wrap class="inner-config-heading">
                        <v-flex xs1>
                            <v-card-text class="icon-header">
                                <i class="material-icons orange--text text--darken-2">
                                    access_time
                                </i>
                            </v-card-text>
                        </v-flex>
                        <v-flex xs11>
                            <v-card-text class="text-xs-left head-portion">
                                What time do you want us to promote your products?
                            </v-card-text>
                            <v-card-text>
                                <v-layout row wrap class="post-days">
                                    <v-flex sm5>
                                        <v-layout row wrap>
                                            <v-flex xs11 sm5>
                                                <v-dialog
                                                    ref="dialog1"
                                                    v-model="start_time"
                                                    :return-value.sync="settings.cron_time.from"
                                                    persistent
                                                    lazy
                                                    full-width
                                                    width="290px">
                                                    <v-text-field
                                                        slot="activator"
                                                        v-model="settings.cron_time.from"
                                                        label="Start Time"
                                                        prepend-icon="access_time"
                                                        readonly></v-text-field>
                                                    <v-time-picker
                                                        v-if="start_time"
                                                        v-model="settings.cron_time.from"
                                                        full-width>
                                                    <v-spacer></v-spacer>
                                                    <v-btn flat color="primary" @click="start_time = false">Cancel</v-btn>
                                                    <v-btn flat color="primary" @click="$refs.dialog1.save(settings.cron_time.from)">OK</v-btn>
                                                    </v-time-picker>
                                                </v-dialog>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                    <v-flex sm2>
                                        <v-card-text>Between</v-card-text>
                                    </v-flex>
                                    <v-flex sm5>
                                        <v-layout row wrap>
                                            <v-flex xs11 sm5>
                                                <v-dialog
                                                    ref="dialog"
                                                    v-model="end_time"
                                                    :return-value.sync="settings.cron_time.to"
                                                    persistent
                                                    lazy
                                                    full-width
                                                    width="290px">
                                                    <v-text-field
                                                        slot="activator"
                                                        v-model="settings.cron_time.to"
                                                        label="End Time"
                                                        prepend-icon="access_time"
                                                        readonly></v-text-field>
                                                    <v-time-picker
                                                        v-if="end_time"
                                                        v-model="settings.cron_time.to"
                                                        full-width>
                                                    <v-spacer></v-spacer>
                                                    <v-btn flat color="primary" @click="end_time = false">Cancel</v-btn>
                                                    <v-btn flat color="primary" @click="$refs.dialog.save(settings.cron_time.to)">OK</v-btn>
                                                    </v-time-picker>
                                                </v-dialog>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                        </v-flex>
                    </v-layout>
                </v-card>
            </v-flex>
            <v-flex sm6>
                <v-card flat>
                    <v-layout row wrap class="inner-config-heading">
                        <v-flex xs1>
                            <v-card-text class="icon-header">
                                <i class="material-icons orange--text text--darken-2">
                                    settings
                                </i>
                            </v-card-text>
                        </v-flex>
                        <v-flex xs11>
                            <v-card-text class="text-xs-left head-portion">
                                Would you like to post on weekends!
                            </v-card-text>
                            <v-card-text>
                                <v-layout row wrap>
                                    <v-flex xs12>
                                        <v-layout row wrap>
                                            <v-flex xs3>
                                                <v-card-text class="week-head text-xs-left">
                                                    Include Weekends:
                                                </v-card-text>
                                            </v-flex>
                                            <v-flex xs2>
                                                <v-switch class="switch-week"
                                                          v-model="settings.alow_weekend"
                                                          color="primary"
                                                ></v-switch>
                                                <span class="yesoption" v-if="switch1">Yes</span>
                                                <span class="yesoption" v-else>No</span>
                                            </v-flex>
                                            <v-flex xs7>
                                                <v-card-text class="text-xs-left">
                                                    Schedule for Saturday and Sundays also
                                                </v-card-text>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                    <v-flex xs12>
                                        <v-layout row wrap>
                                            <v-flex xs3>
                                                <v-card-text class="week-head text-xs-left">
                                                    Allow Reposting
                                                </v-card-text>
                                            </v-flex>
                                            <v-flex xs2>
                                                <v-switch class="switch-week"
                                                          v-model="settings.alow_repetation"
                                                          color="primary"
                                                ></v-switch>
                                                <span class="yesoption" v-if="switch2">Yes</span>
                                                <span class="yesoption" v-else>No</span>
                                            </v-flex>
                                            <v-flex xs7>
                                                <v-card-text class="text-xs-left">
                                                    Repeat Autopilot when every product has been shared/posted at least once
                                                </v-card-text>
                                            </v-flex>
                                        </v-layout>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                        </v-flex>
                    </v-layout>
                </v-card>
            </v-flex>
            <v-flex xs12 class="next-step-cta">
                <v-progress-linear height="18" v-model="sprintProgress"></v-progress-linear>
                <p>{{sprintProgress}}%</p>
                <v-btn style="float:left" @click="StepOneActive" flat depressed class="orange--text darken-3 back-cta">Back To Products</v-btn>
                <v-btn @click="stepThirdActive" depressed color="orange darken-3">Next Step: Messaging</v-btn>
            </v-flex>
        </v-layout>
        <v-layout row wrap v-show="showStep3" class="step-two">
            <v-flex xs12>
                <v-card flat>
                    <v-card-text class="pl-0 pb-3 step-heading">
                        <h2 class="darken-3 primary--text">Autopilot Messaging</h2>
                        <p>Over here, you can determine the message that goes with each product post.</p>
                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex xs12 class="sprint-tooltip">
                <v-tooltip bottom color="orange darken-2">
                    <v-icon
                            slot="activator"
                            color="orange darken-2"
                            dark
                    >whatshot</v-icon>
                    <span>You can create a sprint to promote your best-selling products, a specific collection or ones with a specific tag!</span>
                </v-tooltip>
            </v-flex>
            <v-flex xs6 class="pr-4">
                <v-radio-group v-model="settings.selected_template" column>
                    <div class="single-radio default-tem">
                        <v-radio value="default" color="primary" label="I would like to use the default template "></v-radio>
                        <div>
                            <v-textarea style="padding-left: 32px;" rows="2"
                                    solo
                                    :disabled="settings.selected_template == 'custom'"
                                    v-model="settings.template_default.format"
                                    name="input-7-4"
                                    label="Solo textarea"
                                    value="PRODUCT TITLE-PRICE-HASHTAG-URL"
                            ></v-textarea>
                            <v-checkbox 
                                    v-if="settings.template_default.extra_mode.status"
                                    :disabled="settings.selected_template == 'custom'"
                                    class="mt-0 pt-0"
                                    style="padding-left: 32px"
                                    label="I would like to add more messaging to my autopilot campaign"
                                    v-model="settings.template_default.extra_mode.status"
                                    color="primary"
                            ></v-checkbox>
                            <v-checkbox 
                                    v-else
                                    :disabled="settings.selected_template == 'custom'"
                                    class="mt-0 pt-0"
                                    style="padding-left: 32px"
                                    label="I would like to add more messaging to my autopilot campaign"
                                    v-model="settings.template_default.extra_mode.status"
                                    color="primary"
                            ></v-checkbox>
                            <div class="show-messaging" v-show="settings.template_default.extra_mode.status && settings.selected_template != 'custom'">
                                <v-layout row wrap>
                                    <v-flex xs10 class="offset-xs1">
                                        <div class="addMesg">
                                            <v-layout align-center v-for="(msg,index) in settings.template_default.extra_msg_obj" :key="index">
                                                <v-checkbox v-model="msg.check" hide-details class="shrink mr-2" color="orange darken-3"></v-checkbox>
                                                <v-text-field label="" placeholder="Add Your Custom Message" v-model="msg.message" :value="msg.message"></v-text-field>
                                            </v-layout>
                                        </div>
                                        <div class="text-xs-center">
                                            <v-btn color="orange darken-3 white--text" @click="addMoreDefaulMsg">
                                                <v-icon left color="white">add_circle_outline</v-icon>Add Another
                                            </v-btn>
                                        </div>
                                    </v-flex>
                                </v-layout>
                            </div>
                        </div>
                    </div>
                    <div class="single-radio mt-3">
                        <v-radio value="custom" color="primary" label="I would like to use a customized post template"></v-radio>
                        <v-textarea style="padding-left: 32px;" rows="2"
                                    solo
                                    :disabled="settings.selected_template == 'default'"                                    
                                    v-model="settings.template_custom.format"
                                    name="input-7-4"
                                    label="Solo textarea"
                                    value="PRODUCT TITLE-PRICE-HASHTAG-URL"
                        ></v-textarea>
                        <v-checkbox
                                v-if="settings.template_custom.extra_mode.status"
                                :disabled="settings.selected_template == 'default'"                                                                    
                                class="mt-0 pt-0"
                                style="padding-left: 32px"
                                label="I would like to add more messaging to my autopilot campaign"
                                v-model="settings.template_custom.extra_mode.status"
                                color="primary"
                        ></v-checkbox>
                        <v-checkbox
                                v-else
                                :disabled="settings.selected_template == 'default'"                                                                    
                                class="mt-0 pt-0"
                                style="padding-left: 32px"
                                label="I would like to add more messaging to my autopilot campaign"
                                v-model="settings.template_custom.extra_mode.status"
                                color="primary"
                        ></v-checkbox>
                        <div class="show-messaging" v-show="settings.template_custom.extra_mode.status && settings.selected_template != 'default'">
                            <v-layout row wrap>
                                <v-flex xs10 class="offset-xs1">
                                    <div class="addMesg">
                                        <v-layout align-center v-for="(msg,index) in settings.template_custom.extra_msg_obj" :key="index">
                                            <v-checkbox v-model="msg.check" hide-details class="shrink mr-2" color="orange darken-3"></v-checkbox>
                                            <v-text-field label="" v-model="msg.message" placeholder="Add Your Custom Message" :value="msg.message"></v-text-field>
                                        </v-layout>
                                    </div>
                                    <div class="text-xs-center">
                                        <v-btn color="orange darken-3 white--text" @click="addMoreCustomMsg">
                                            <v-icon left color="white">add_circle_outline</v-icon>Add Another
                                        </v-btn>
                                    </div>
                                </v-flex>
                            </v-layout>
                        </div>
                    </div>
                </v-radio-group>
            </v-flex>
            <v-flex xs6>
                <v-card flat>
                    <v-card-text class="text-xs-left" style="font-weight: bold;font-size: 16px">Preview:</v-card-text>
                    <v-tabs grow v-model="active" color="primary" dark slider-color="blue lighten-2">
                        <v-tab ripple>Facebook</v-tab>
                        <v-tab-item>
                            <v-card>
                                <v-card-text class="mt-2" style="border: 1px solid #ccc;border-radius: 3px;">
                                    <v-layout row wrap>
                                        <v-flex xs1>
                                            <img style="width:100%;border-radius: 100%;" src="../../images/tk.png">
                                        </v-flex>
                                        <v-flex xs11>
                                            <v-layout row wrap>
                                                <v-flex xs9>
                                                    <h3 class="text-xs-left">My Store Name</h3>
                                                    <span class="text-xs-left grey--text" style="display: block;">Today at {{new Date().toLocaleTimeString().split(" ")[0].replace(/(:\d{2}| [AP]M)$/, "")}}</span>
                                                </v-flex>
                                                <v-flex xs3>
                                                    <v-btn style="background-color:transparent !important" flat depressed class="ma-0 pa-0 right text-xs-right">
                                                        <i class="material-icons">
                                                            more_horiz
                                                        </i>
                                                    </v-btn>
                                                </v-flex>
                                            </v-layout>
                                        </v-flex>
                                        <v-flex xs12>
                                            <p class="text-xs-left" v-if="settings.selected_template == 'default'">
                                                {{settings.template_default.format.split("-")[0]}}
                                                <span class="primary--text">{{settings.template_default.format.split("-")[2]}}</span>
                                                <a :href="settings.template_default.format.split('-')[3]" v-if="settings.template_default.format.split('-')[3]">{{settings.template_default.format.split("-")[3]}}</a>
                                            </p>
                                            <p class="text-xs-left" v-if="settings.selected_template == 'custom'">
                                                {{settings.template_custom.format.split("-")[0]}}
                                                <span class="primary--text">{{settings.template_custom.format.split("-")[2]}}</span>
                                                <a :href="settings.template_custom.format.split('-')[3]" v-if="settings.template_custom.format.split('-')[3]">{{settings.template_custom.format.split("-")[3]}}</a>
                                            </p>
                                        </v-flex>
                                        <v-flex xs12>
                                            <img style="width: 100%;max-width: 329px;" v-if="settings.products.ids.length > 0" :src="getProductDetail('src')">
                                        </v-flex>
                                    </v-layout>
                                </v-card-text>
                            </v-card>
                        </v-tab-item>
                        <v-tab ripple>Twitter</v-tab>
                        <v-tab-item>
                            <v-card>
                                <v-card-text class="mt-2" style="border: 1px solid #ccc;border-radius: 3px;">
                                    <v-layout row wrap>
                                        <v-flex xs1>
                                            <img style="width:100%;border-radius: 100%" src="../../images/tk.png">
                                        </v-flex>
                                        <v-flex xs11 v-if="settings.selected_template == 'default'">
                                            <h3 class="text-xs-left">My Store Name <span class="text-xs-left grey--text" style="font-size: 12px">@userName</span></h3>
                                            <p class="text-xs-left">
                                                {{settings.template_default.format.split("-")[0]}}
                                                <span class="primary--text">{{settings.template_default.format.split("-")[2]}}</span>
                                                <a :href="settings.template_default.format.split('-')[3]" v-if="settings.template_default.format.split('-')[3]">
                                                    {{settings.template_default.format.split("-")[3]}}
                                                </a>
                                            </p>
                                            <img style="width: 100%;max-width: 329px;" v-if="settings.products.ids.length > 0" :src="getProductDetail('src')">
                                        </v-flex>
                                        <v-flex xs11 v-if="settings.selected_template == 'custom'">
                                            <h3 class="text-xs-left">My Store Name <span class="text-xs-left grey--text" style="font-size: 12px">@userName</span></h3>
                                            <p class="text-xs-left">
                                                {{settings.template_custom.format.split("-")[0]}}
                                                <span class="primary--text">{{settings.template_custom.format.split("-")[2]}}</span>
                                                <a :href="settings.template_custom.format.split('-')[3]" v-if="settings.template_custom.format.split('-')[3]">
                                                    {{settings.template_custom.format.split("-")[3]}}
                                                </a>
                                            </p>
                                            <img style="width: 100%;max-width: 329px;" v-if="settings.products.ids.length > 0" :src="getProductDetail('src')">
                                        </v-flex>
                                    </v-layout>
                                </v-card-text>
                            </v-card>
                        </v-tab-item>
                        <v-tab ripple>Instagram</v-tab>
                        <v-tab-item>
                            <v-card>
                                <v-card-text class="mt-2" style="border: 1px solid #ccc;border-radius: 3px;">
                                    <v-layout row wrap class="mt-3">
                                        <v-flex xs7>
                                            <img style="width: 100%;max-width: 329px;" v-if="settings.products.ids.length > 0" :src="getProductDetail('src')">
                                        </v-flex>
                                        <v-flex xs5>
                                            <v-layout row wrap>
                                                <v-flex xs2>
                                                    <img style="width:100%;border-radius: 100%;" src="../../images/tk.png">
                                                </v-flex>
                                                <v-flex xs10>
                                                    <h3 class="text-xs-left">My Store Name</h3>
                                                </v-flex>
                                                <v-flex xs12>
                                                    <p class="text-xs-left">{{getProductDetail('desc')}}</p>
                                                </v-flex>
                                            </v-layout>
                                        </v-flex>
                                    </v-layout>
                                </v-card-text>
                            </v-card>
                        </v-tab-item>
                        <v-tab ripple>Pinterest</v-tab>
                        <v-tab-item>
                            <v-card>
                                <v-card-text>
                                    <v-layout row wrap style="margin: 24px auto;border: 1px solid #ccc;border-radius: 3px;">
                                        <v-flex sm6 class="pb-0">
                                            <img style="width: 100%;max-width: 329px;" v-if="settings.products.ids.length > 0" :src="getProductDetail('src')">
                                        </v-flex>
                                        <v-flex sm6 v-card class="pt-3 pin-head">
                                            <p class="text-xs-left pin-price mb-0">{{getProductDetail('price')}}</p>
                                            <h3 class="text-xs-left pin-prod">{{getProductDetail('title')}}</h3>
                                            <p class="text-xs-left mt-4 pin-desc">{{getProductDetail('desc')}}</p>
                                            <v-btn large flat depressed color="grey darken-2" style="text-transform: lowercase">
                                                <i class="material-icons">
                                                call_made
                                                </i>
                                                sacrifice-live.myshopify.com
                                            </v-btn>
                                        </v-flex>
                                    </v-layout>
                                </v-card-text>
                            </v-card>
                        </v-tab-item>
                    </v-tabs>
                </v-card>
            </v-flex>
            <v-flex xs12 class="next-step-cta">
                <v-progress-linear height="18" v-model="sprintProgress"></v-progress-linear>
                <p>{{sprintProgress}}%</p>
                <v-btn style="float:left" @click="StepTwoActive" flat depressed class="orange--text darken-3 back-cta">Back To Configuation</v-btn>
                <v-btn @click="StepFourActive" depressed color="orange darken-3">Calender View</v-btn>
            </v-flex>
        </v-layout>
        <v-layout row wrap v-show="showStep4">
            <v-flex xs12>
                <v-card flat>
                    <v-card-text class="pl-0 pb-3 step-heading">
                        <h2 class="darken-3 primary--text">Review Autopilot Calendar</h2>
                        <p>Almost there! Take a lot at the tentative calendar before we schedule them!</p>
                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex xs12 class="sprint-tooltip">
                <v-tooltip bottom color="orange darken-2">
                    <v-icon
                            slot="activator"
                            color="orange darken-2"
                            dark
                    >whatshot</v-icon>
                    <span>You can create a sprint to promote your best-selling products, a specific collection or ones with a specific tag!</span>
                </v-tooltip>
            </v-flex>
            <v-flex xs12>
                <v-container>
                    <my-calendar></my-calendar>
                </v-container>
            </v-flex>
            <v-flex xs12 class="next-step-cta">
                <v-progress-linear height="18" v-model="sprintProgress"></v-progress-linear>
                <p>{{sprintProgress}}%</p>
                <v-btn style="float:left" @click="stepThirdActive" flat depressed class="orange--text darken-3 back-cta">Back To Messaging</v-btn>
                <v-btn to="/dashboard" @click="createSprint" color="orange darken-3">Save & Close</v-btn>
            </v-flex>
            <v-flex>
                <v-snackbar
                    v-model="snackbar1"
                    :bottom=true
                    :multi-line=true
                    :timeout=6000
                    :vertical=true
                    >
                    {{ snackbarText }}
                    <v-btn color="pink" flat @click="snackbar1 = false" >
                        Close
                    </v-btn>
                </v-snackbar>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>

    import sprint from '../config/sprints.json';

    export default{
        data(){
             self = this;
            return{
                loadingShow: false,
                label: 'Loading...',
                snackbar1 : true,
                snackbarText : 'Some thing went wrong!',
                start_time: false,
                end_time: false,
                settings: {},
                selectAll:false,
                listedProducts: [],
                types: [],
                vendor: [],
                tags: [],
                filters: [],
                collections: [],
                filteredString: '',
                filteredStringCol: '',
                sprintProgress:0,
                showMessages: false,
                showMessagesCustom: false,
                selectedFilteredOption: '',
                filterOptions: ['Vendors', 'Collections', 'Type'],
                makeACtive:true,
                active:true,

                switch1: true,
                switch2: true,
                valid: false,
                postNo: '',
                nameRules: [
                    v => !!v || 'Field is required',
                ],
                DayNo: '',
                chipTiming: false,
                isSelectedCount:0,
                hour: '1',
                min: '1',
                morning: 'AM',
                dialog: false,
                date: '2018-10-13',
                showStep0: true,
                showStep1:false,
                showStep2: false,
                showStep3: false,
                showStep4:false,
                selected: [],
                descriptionLimit: 60,
                entries: [],
                isLoading: false,
                model: null,
                search: null,
                psearch: ''
            }
        },
        created: function () {
            self.settings = _.clone(sprint);
            this.getProducts();
        },
        methods : {
            getProducts: function(){
                var self = this;
                this.makeACtive = true;
                self.loadingShow = true;
                axios.get('sprint').then(function (response) {

                    self.listedProducts = response.data.results.products;
                    self.types = response.data.p_types;
                    self.vendor = response.data.vendor;
                    // self.tags = response.data.tags;
                    self.filters = response.data.filters;
                    self.collections = response.data.collections;
                    self.loadingShow = false;

                    console.log('self.collections: ',self.collections)
                }).catch((error) => {

                    self.loadingShow = false;
                    self.snackbar1 = true;
                });
            },
            createSprint: function(){

                this.customizeSettingObj();
                this.makeACtive = true;
                var self = this;
                var status = true;
                var type = 'autopilot';
                var sname = this.settings.sname;
                var products_ids = this.settings.products.ids;
                var post = this.settings.post_product[0].no_of_post;
                var every_days = this.settings.post_product[1].every_days;
                var cron_time_from = this.settings.cron_time.from;
                var cron_time_to = this.settings.cron_time.to;
             
                var obj = {
                    "status": status,
                    "type": type,
                    "sname": sname,
                    "post": post,
                    "every_days": every_days,
                    "products_ids": products_ids,
                    "cron_time_from": cron_time_from,
                    "cron_time_to": cron_time_to,
                    "settings": this.settings,
                }

                axios.post('sprint', obj).then(function (response) {

                    console.log('response: ', response);
                }).catch((error) => {

                    self.snackbar1 = true;
                });
            },
            customizeSettingObj: function(){
                var social_obj = this.settings.social;
                for (var key in social_obj) {

                    if (key === 'facebook' && !social_obj[key].status) {
                        this.settings.social.facebook = {};
                    }else if(key == 'instagram' && !social_obj[key].status){
                        this.settings.social.instagram = {};                        
                    }else if(key == 'twitter' && !social_obj[key].status){
                        this.settings.social.twitter = {};
                    }else if(key == 'pinterest' && !social_obj[key].status){
                        this.settings.social.pinterest = {};
                    }
                }

                var template = this.settings.selected_template;
                if(template == 'default'){
                    this.settings.template_custom = {};
                    var messages = this.settings.template_default.extra_msg_obj;
                    for (let obj of messages) {
                        if(!obj.check){
                            const index = messages.indexOf(obj);
                            messages.splice(index, 1);
                        }
                    }
                }else{
                    this.settings.template_default = {};
                    var messages = this.settings.template_custom.extra_msg_obj;
                    for (let obj of messages) {
                        if(!obj.check){
                            const index = messages.indexOf(obj);
                            messages.splice(index, 1);
                        }
                    }
                }

                delete this.settings.social.twitter.pages;
                delete this.settings.social.facebook.pages;
                delete this.settings.social.instagram.pages;
                delete this.settings.social.pinterest.pages;
            },
            CheckboxFilteration: function(filter){
                var self = this;
                self.loadingShow = true;
                self.makeACtive = true;
                if(!filter.id || !filter.hasOwnProperty('id')){
                    filter.id = '';
                    self.filteredStringCol = '';
                }else{
                    self.filteredString = '';
                }
                axios.post('filter_products', 
                { 
                    "prodFilter": filter.value,
                    "type": filter.type,
                    "id": filter.id,
                }).then(function (response) {
                    self.listedProducts = response.data.results.products;
                    self.types = response.data.p_types;
                    self.vendor = response.data.vendor;
                    // self.tags = response.data.tags;
                    self.filters = response.data.filters;
                    self.collections = response.data.collections;

                    self.loadingShow = false;
                }).catch((error) => {

                    self.loadingShow = false;
                    self.snackbar1 = true;
                });
            },
            filterChange: function(){

                console.log('selectedFilter: ', this.selectedFilteredOption);
                var self = this;
                self.makeACtive = true;
                self.loadingShow = true;
                axios.post('filter_type', 
                { 
                    "selectedFilter": this.selectedFilteredOption,
                }).then(function (response) {

                    self.listedProducts = response.data.results.products;
                    self.types = response.data.p_types;
                    self.vendor = response.data.vendor;
                    self.collections = response.data.collections;
                    self.filters = response.data.filters;
                    // self.tags = response.data.tags;

                    console.log('self.collections: ' , self.collections);
                    if(self.selectedFilteredOption === 'Type'){
                        self.listedProducts = response.data.p_typeRes;
                    }else if(self.selectedFilteredOption === 'Vendors'){
                        self.listedProducts = response.data.vendorRes;
                    }
                    // else if(self.selectedFilteredOption === 'Tags'){
                    //     self.listedProducts = response.data.tagRes;
                    // }

                    self.loadingShow = false;
                }).catch((error) => {

                    self.loadingShow = false;
                    self.snackbar1 = true;
                });
            },
            getProductDetail(type) {

                var self = this, data = '';
                self.listedProducts.filter(product => {
                    if(self.settings.products.ids[0] == product.id){
                        if(type == 'src'){
                            if(product.image.src){
                                data = product.image.src;
                            }else{
                                data = product.images[0].src;
                            }
                        }else if(type == 'desc'){
                            if(product.body_html){
                                data = product.body_html;
                            }
                        }else if(type == 'price'){
                            if(product.variants[0]){
                                data = product.variants[0].price;
                            }
                        }else if(type == 'title'){
                            if(product.title){
                                data = product.title;
                            }
                        }
                    }
                });
                return data;
            },
            productSelected : function (index) {
                if(this.listedProducts[index].isSelected){
                    const p_id = this.listedProducts.indexOf(this.listedProducts[index].id);
                    this.settings.products.ids.splice(p_id, 1);
                    
                    this.listedProducts[index].isSelected = false;
                    this.isSelectedCount--;
                    console.log('this.isSelectedCount if: ', this.isSelectedCount)
                    if(this.isSelectedCount > 0)
                        this.makeACtive = false;
                    else
                        this.makeACtive = true;
                }else{

                    this.settings.products.ids.push(this.listedProducts[index].id);
                    this.listedProducts[index].isSelected = true;
                    this.isSelectedCount++;
                    console.log('this.isSelectedCount else: ', this.isSelectedCount)
                    if(this.isSelectedCount > 0)
                        this.makeACtive = false;
                    else
                        this.makeACtive = true;
                }
            },
            nextStep: function () {
                this.showStep1 = false;
                this.showStep2 = true;
                this.sprintProgress = 40;
            },
            setStep0: function(){
                this.showStep0 = true;
                this.showStep1 = false;
                this.showStep2 = false;
                this.showStep3 = false;
                this.showStep4 = false;
                this.sprintProgress = 0;
            },
            StepOneActive : function () {
                this.showStep1 = true;
                this.showStep0 = false;
                this.showStep2 = false;
                this.showStep3 = false;
                this.showStep4 = false;
                this.sprintProgress = 20;
            },
            StepTwoActive: function(){
                this.showStep2 = true;
                this.showStep0 = false;
                this.showStep1 = false;
                this.showStep3 = false;
                this.showStep4 = false;
                this.sprintProgress = 40;
            },
            stepThirdActive: function () {
                this.showStep3 = true;
                this.showStep0 = false;
                this.showStep1 = false;
                this.showStep2 = false;
                this.showStep4 = false;
                this.sprintProgress = 60;
            },
            StepFourActive: function(){
                this.showStep4 = true;
                this.showStep0 = false;
                this.showStep1 = false;
                this.showStep2 = false;
                this.showStep3 = false;
                this.sprintProgress = 80;
            },
            addMoreDefaulMsg: function () {
                this.settings.template_default.extra_msg_obj.push({message:'', check:false})
            },
            addMoreCustomMsg: function () {
                this.settings.template_template_custom.extra_msg_obj.push({message:'', check:false})
            },
            removeFromStack: function (index){
                let j=0;
                this.listedProducts[index].isSelected = false;
                for (var i = 0; i < this.listedProducts.length; i++) {
                    if(this.listedProducts[i].isSelected===true){
                        j = ++j;
                    }
                }
                if( j>=1 ){
                    this.makeACtive = false;
                }else{
                    this.makeACtive = true;
                }
            },
            selectAllProducts: function () {
                if(this.selectAll === true){
                    for (var i = 0; i < this.listedProducts.length; i++) {
                        this.listedProducts[i].isSelected = true;
                        this.isSelectedCount = this.listedProducts.length;
                        this.makeACtive = false;
                    }
                }else{
                    for (var i = 0; i < this.listedProducts.length; i++) {
                        this.listedProducts[i].isSelected = false;
                        this.isSelectedCount = 0;
                        this.makeACtive = true;
                    }
                }
            }
        },
        computed: {
            productFilters() {

                console.log('productFilters: ', this.listedProducts)
                return this.listedProducts.filter(product => {
                    return product.title.toLowerCase().includes(this.psearch.toLowerCase())
                })
            },
            filterOptionsFunc() {

                if(this.selectedFilteredOption == 'Vendors'){
                    this.filters = this.vendor;
                }else if(this.selectedFilteredOption == 'Type'){
                    this.filters = this.types;
                }else if(this.selectedFilteredOption == 'Collections'){
                    this.collections = this.collections;
                }
                // else if(this.selectedFilteredOption == 'Tags'){
                //     this.filters = this.tags;
                // }      

                return this.filterOptions.filter(fillterType => {
                    return fillterType;
                })
            }
        },
        watch: {
            
        }
    }

</script>