<template>
    <v-container grid-list-md text-xs-center class="pa-0">
        <v-layout row wrap v-show="showStep0">
            <v-flex xs12 class="step0">
                <v-card flat>
                    <h3 class="text-xs-center mb-4">Let's name your social sprint!</h3>
                    <v-text-field
                            v-model="name"
                            :rules="nameRules"
                            :counter="50"
                            label="Name Your Manual Sprint"
                            required
                            solo
                            v-on:keyup.enter="setStep1"
                            flat
                            class="getting-sprint-name"
                    ></v-text-field>
                    <v-layout row wrap class="tableHead py-3 px-0">
                        <v-flex sm1>
                            <img class="main-tip-icon" src="../../images/main-tip-icon.png">
                        </v-flex>
                        <v-flex sm11 class="pb-0 pt-0">
                            <p class="mb-0 pa-3 text-xs-left pl-5 main-tip-content" style="font-size: 18px;">
                                <strong style="display: block; font-size: 18px;" class="mr-2">Tip:</strong> If you are promoting a particular type of product or collection, try to use that in name to make your sprint more identifiable in the dashboard!
                            </p>
                        </v-flex>
                    </v-layout>
                    <v-btn @click="setStep1" depressed class="ctaColor white--text mr-0 mt-4 right appCta">
                        Next Step
                        <v-icon right class="ml-1">arrow_right</v-icon>
                    </v-btn>
                </v-card>
            </v-flex>
            <!--<v-flex xs12 class="next-step-cta">-->
                <!--<v-progress-linear height="18" v-model="sprintProgress"></v-progress-linear>-->
                <!--<p>{{sprintProgress}}%</p>-->
                <!--<v-btn @click="setStep1" depressed color="orange darken-3">Next Step: Select Products</v-btn>-->
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
                            class="ctaColor--text"
                            dark
                            size="32px"
                    >whatshot</v-icon>
                    <span>You can create a sprint to promote your best-selling products, a specific collection or ones with a specific tag!</span>
                </v-tooltip>
            </v-flex>
            <v-flex xs12>
                <v-card-text class="top-search-bar">
                    <v-autocomplete
                            v-model="model"
                            :items="items"
                            :loading="isLoading"
                            :search-input.sync="search"
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
                    ></v-autocomplete>
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
            <v-flex md10 style="margin-bottom: 60px;" class="pl-5 pr-1 listing-of-products">
                <v-checkbox
                        label="Select All Shown"
                        v-model="selectAll"
                        @change="selectAllProducts"
                        color="primary"
                ></v-checkbox>
                <v-card flat>
                    <v-layout row wrap>
                        <v-flex v-for="(products, index) in listedProducts" :key="index" lg3 class="mb-5">
                            <v-card flat class="listed-products-shopify">
                                <v-card-text class="pa-0">
                                    <v-btn :disabled="products.isSelected" @click="productSelected(index)" flat class="ma-0 py-4">
                                        <img :src="products.productImg"/>
                                        <p>{{products.productName}}</p>
                                        <span class="beforeAdded" v-if="!products.isSelected">Add To Stack!</span>
                                        <span class="afterAdded" v-else>Added!</span>
                                    </v-btn>
                                    <span class="beforeAdded removeProduct" @click="removeFromStack(index)"  v-if="products.isSelected">
                                        <i class="material-icons close-icon">
                                            close
                                        </i>
                                    </span>
                                </v-card-text>
                            </v-card>
                            <!--<v-flex xs12 class="next-step-cta">-->
                                <!--<v-progress-linear height="18" v-model="sprintProgress"></v-progress-linear>-->
                                <!--<p>{{sprintProgress}}%</p>-->
                                <!--<v-btn style="float:left" @click="setStep0" flat depressed class="orange&#45;&#45;text darken-3 back-cta">Back To Sprint Name</v-btn>-->
                                <!--<v-btn :disabled="makeACtive" @click="setStep2" depressed color="orange darken-3">Next Step: Let's Schedule Your Post!</v-btn>-->
                            <!--</v-flex>-->
                        </v-flex>
                        <v-flex xs12 class="pa-0 pr-4">
                            <v-btn :disabled="makeACtive" @click="setStep2" depressed class="ctaColor white--text mr-0 mt-0 right appCta">
                                Next Step
                                <v-icon right class="ml-1">arrow_right</v-icon>
                            </v-btn>
                            <v-btn  @click="setStep0" flat depressed class="tableHead right appCta mt-0 backAppCta">Back</v-btn>
                        </v-flex>
                    </v-layout>
                </v-card>
            </v-flex>
        </v-layout>
        <v-layout row wrap v-show="showStep2" class="step-two">
            <v-flex xs12>
                <v-card flat>
                    <v-card-text class="pl-0 pb-3 step-heading">
                        <h2 class="darken-3 primary--text">Schedule Your Sprint</h2>
                        <p>Let's schedule your posts by dragging and dropping each product on the calendar!</p>
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
            <v-flex xs2 class="selected-products">
                <h3 class="text-xs-left">Selected Products:</h3>
                <v-card flat v-for="(products,index) in listedProducts" :key="index" v-if="products.isSelected">
                    <v-card-text class="listed-small">
                        <v-btn flat depressed @click="postSelected(index)" :disabled="products.postSelected">
                            <img :src="products.productImg">
                            <p>{{products.productName}}</p>
                        </v-btn>
                    </v-card-text>
                </v-card>
            </v-flex>
            <v-flex xs10>
                <v-card flat>
                    <v-card-text>
                        <v-date-picker
                                v-model="date"
                                full-width
                                landscape
                                class="mt-3"
                                color="primary"
                        ></v-date-picker>
                    </v-card-text>
                </v-card>
                <v-layout row wrap>
                    <v-flex xs3 v-for="(products,index) in listedProducts" :key="index" v-if="products.postSelected">
                        <v-card flat>
                            <v-card-text class="listed-small">
                                <v-btn @click="showDialog(index)" flat depressed>
                                    <img :src="products.productImg">
                                    <p>{{products.productName}}</p>
                                </v-btn>
                            </v-card-text>
                            <v-card-text>
                                <v-chip @click="showChipTiming">{{getPostHours}}:{{getPostMin}} {{morning}}</v-chip>
                            </v-card-text>
                            <v-card-text v-show="chipTiming">
                                <v-layout row wrap>
                                    <v-flex xs4>
                                        <v-combobox
                                                v-model="getPostHours"
                                                :items="hourDetails"
                                                label="Hour"
                                        ></v-combobox>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-combobox
                                                v-model="getPostMin"
                                                :items="minDetails"
                                                label="Min"
                                        ></v-combobox>
                                    </v-flex>
                                    <v-flex xs4>
                                        <v-combobox
                                                v-model="morning"
                                                :items="morningDetails"
                                                label=""
                                        ></v-combobox>
                                    </v-flex>
                                    <v-flex xs12>
                                        <v-btn @click="chipTiming=false" color="primary" depressed>Set</v-btn>
                                    </v-flex>
                                </v-layout>
                            </v-card-text>
                        </v-card>
                        <v-dialog v-model="dialog" width="900">
                            <v-card>
                                <v-card-title class="headline light-blue lighten-1 white--text">Schedule Your Post</v-card-title>
                                <v-layout row wrap>
                                    <v-flex sm6>
                                        <v-card-text>
                                            <img style="width: 100%" :src="products.productImg">
                                        </v-card-text>
                                        <v-card-text>
                                            <p>{{products.productName}}</p>
                                        </v-card-text>
                                    </v-flex>
                                    <v-flex sm6>
                                        <v-card-text>
                                            <v-textarea
                                                    name="input-7-1"
                                                    label="Post Description"
                                                    value=""
                                                    hint=""
                                                    placeholder="What Would You Like To Share"
                                            ></v-textarea>
                                        </v-card-text>
                                        <v-card-text>Social Sharing Networks</v-card-text>
                                        <v-card-text>
                                            <v-layout row wrap>
                                                <v-flex xs6>
                                                    <v-layout row wrap>
                                                        <v-flex xs2>
                                                            <v-checkbox
                                                                    v-model="ex4"
                                                                    label=""
                                                                    color="primary"
                                                                    value="Facebook"
                                                                    hide-details
                                                            ></v-checkbox>
                                                        </v-flex>
                                                        <v-flex xs1 class="pt-4">
                                                            <i class="fab fa-facebook-f primary--text"></i>
                                                        </v-flex>
                                                        <v-flex xs8>
                                                            <v-select
                                                                    :items="fbPages"
                                                                    label="Select Page"
                                                            ></v-select>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-flex>
                                                <v-flex xs6>
                                                    <v-layout row wrap>
                                                        <v-flex xs2>
                                                            <v-checkbox
                                                                    v-model="ex4"
                                                                    label=""
                                                                    color="primary"
                                                                    value="Twitter"
                                                                    hide-details
                                                            ></v-checkbox>
                                                        </v-flex>
                                                        <v-flex xs1 class="pt-4">
                                                            <i class="fab fa-twitter primary--text"></i>
                                                        </v-flex>
                                                        <v-flex xs8>
                                                            <v-select
                                                                    :items="fbPages"
                                                                    label="Select Page"
                                                            ></v-select>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-flex>
                                                <v-flex xs6>
                                                    <v-layout row wrap>
                                                        <v-flex xs2>
                                                            <v-checkbox
                                                                    v-model="ex4"
                                                                    label=""
                                                                    color="primary"
                                                                    value="Instagram"
                                                                    hide-details
                                                            ></v-checkbox>
                                                        </v-flex>
                                                        <v-flex xs1 class="pt-4">
                                                            <i class="fab fa-instagram primary--text"></i>
                                                        </v-flex>
                                                        <v-flex xs8>
                                                            <v-select
                                                                    :items="fbPages"
                                                                    label="Select Page"
                                                            ></v-select>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-flex>
                                                <v-flex xs6>
                                                    <v-layout row wrap>
                                                        <v-flex xs2>
                                                            <v-checkbox
                                                                    v-model="ex4"
                                                                    label=""
                                                                    color="primary"
                                                                    value="Pinterest"
                                                                    hide-details
                                                            ></v-checkbox>
                                                        </v-flex>
                                                        <v-flex xs1 class="pt-4">
                                                            <i class="fab fa-pinterest primary--text"></i>
                                                        </v-flex>
                                                        <v-flex xs8>
                                                            <v-select
                                                                    :items="fbPages"
                                                                    label="Select Page"
                                                            ></v-select>
                                                        </v-flex>
                                                    </v-layout>
                                                </v-flex>
                                            </v-layout>
                                        </v-card-text>
                                        <v-card-text>
                                            <v-layout row wrap>
                                                <v-flex xs4>
                                                    <v-combobox
                                                            v-model="getPostHours"
                                                            :items="hourDetails"
                                                            label="Hour"
                                                    ></v-combobox>
                                                </v-flex>
                                                <v-flex xs4>
                                                    <v-combobox
                                                            v-model="getPostMin"
                                                            :items="minDetails"
                                                            label="Min"
                                                    ></v-combobox>
                                                </v-flex>
                                                <v-flex xs4>
                                                    <v-combobox
                                                            v-model="morning"
                                                            :items="morningDetails"
                                                            label=""
                                                    ></v-combobox>
                                                </v-flex>
                                            </v-layout>
                                        </v-card-text>
                                        <v-card-text>
                                            <v-btn color="primary" @click="dialog = false">
                                                Post Now!
                                            </v-btn>
                                            <v-btn color="primary" @click="dialog = false">
                                                Schedule It For Later
                                            </v-btn>
                                        </v-card-text>
                                    </v-flex>
                                </v-layout>
                                <!--<v-card-actions>-->
                                    <!--<v-spacer></v-spacer>-->
                                    <!--<v-btn color="primary" @click="dialog = false">-->
                                        <!--Schedule-->
                                    <!--</v-btn>-->
                                <!--</v-card-actions>-->
                            </v-card>
                        </v-dialog>
                    </v-flex>
                </v-layout>
            </v-flex>
            <!--<v-flex xs12 class="next-step-cta">-->
                <!--<v-progress-linear height="18" v-model="sprintProgress"></v-progress-linear>-->
                <!--<p>{{sprintProgress}}%</p>-->
                <!--<v-btn style="float:left" @click="setStep1" flat depressed class="orange&#45;&#45;text darken-3 back-cta">Back To Products</v-btn>-->
                <!--<v-btn to="/dashboard" depressed color="orange darken-3">Save & Close</v-btn>-->
            <!--</v-flex>-->
            <v-flex xs12 class="pa-0 pr-4 pt-4">
                <v-btn to="/dashboard" :disabled="makeACtive" depressed class="ctaColor white--text mr-0 mt-0 right appCta">
                    Save & Close
                </v-btn>
                <v-btn  @click="setStep1" flat depressed class="tableHead right appCta mt-0 backAppCta">Back</v-btn>
            </v-flex>
        </v-layout>
    </v-container>
</template>

<script>
    export default{
        data(){
            return{
                selectAll:false,
                sprintProgress: 0,
                fbPages:['Page 1','Page 2','Page 3','Page 4','Page 5'],
                chipTiming: false,
                makeACtive:true,
                hour: '1',
                hourDetails: ['1','2','3','4','5','6','7','8','9','10','11','12'],
                min: '1',
                minDetails: ['01', '02', '03', '04', '05', '06', '07', '08', '09', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20', '21', '22', '23', '24', '25', '26', '27', '28', '29', '30', '31', '32', '33', '34', '35', '36', '37', '38', '39', '40', '41', '42', '43', '44', '45', '46', '47', '48', '49', '50', '51', '52', '53', '54', '55', '56', '57', '58', '59', '60',],
                morning: 'AM',
                morningDetails: ['AM','PM'],
                dialog: false,
                date: '2018-10-13',
                showStep0:true,
                showStep1:false,
                showStep2:false,
                tagselected: ['John'],
                filterOptions: ['Tag', 'Collections', 'Type'],
                descriptionLimit: 60,
                entries: [],
                isLoading: false,
                model: null,
                search: null,
                listedProducts:[
                    {
                        productImg: '../../images/product-1.jpg',
                        productName: 'Unique Naruto Painting Graffiti Style 3D Printed Naruto Long Sleeve',
                        isSelected : false,
                        postSelected: false,
                    },
                    {
                        productImg: '../../images/product-2.jpg',
                        productName: 'Unique Naruto Painting Graffiti Style 3D Printed Naruto Long Sleeve',
                        isSelected : false,
                        postSelected: false,
                    },
                    {
                        productImg: '../../images/product-3.jpg',
                        productName: 'Unique Naruto Painting Graffiti Style 3D Printed Naruto Long Sleeve',
                        isSelected : false,
                        postSelected: false,
                    },
                    {
                        productImg: '../../images/product-4.jpg',
                        productName: 'Unique Naruto Painting Graffiti Style 3D Printed Naruto Long Sleeve',
                        isSelected : false,
                        postSelected: false,
                    },
                    {
                        productImg: '../../images/product-1.jpg',
                        productName: 'Unique Naruto Painting Graffiti Style 3D Printed Naruto Long Sleeve',
                        isSelected : false,
                        postSelected: false,
                    },
                    {
                        productImg: '../../images/product-2.jpg',
                        productName: 'Unique Naruto Painting Graffiti Style 3D Printed Naruto Long Sleeve',
                        isSelected : false,
                        postSelected: false,
                    },
                    {
                        productImg: '../../images/product-3.jpg',
                        productName: 'Unique Naruto Painting Graffiti Style 3D Printed Naruto Long Sleeve',
                        isSelected : false,
                        postSelected: false,
                    },
                    {
                        productImg: '../../images/product-4.jpg',
                        productName: 'Unique Naruto Painting Graffiti Style 3D Printed Naruto Long Sleeve',
                        isSelected : false,
                        postSelected: false,
                    },
                    {
                        productImg: '../../images/product-1.jpg',
                        productName: 'Unique Naruto Painting Graffiti Style 3D Printed Naruto Long Sleeve',
                        isSelected : false,
                        postSelected: false,
                    },
                    {
                        productImg: '../../images/product-2.jpg',
                        productName: 'Unique Naruto Painting Graffiti Style 3D Printed Naruto Long Sleeve',
                        isSelected : false,
                        postSelected: false,
                    },
                    {
                        productImg: '../../images/product-3.jpg',
                        productName: 'Unique Naruto Painting Graffiti Style 3D Printed Naruto Long Sleeve',
                        isSelected : false,
                        postSelected: false,
                    },
                    {
                        productImg: '../../images/product-4.jpg',
                        productName: 'Unique Naruto Painting Graffiti Style 3D Printed Naruto Long Sleeve',
                        isSelected : false,
                        postSelected: false,
                    },
                    {
                        productImg: '../../images/product-1.jpg',
                        productName: 'Unique Naruto Painting Graffiti Style 3D Printed Naruto Long Sleeve',
                        isSelected : false,
                        postSelected: false,
                    },
                    {
                        productImg: '../../images/product-2.jpg',
                        productName: 'Unique Naruto Painting Graffiti Style 3D Printed Naruto Long Sleeve',
                        isSelected : false,
                        postSelected: false,
                    },
                    {
                        productImg: '../../images/product-3.jpg',
                        productName: 'Unique Naruto Painting Graffiti Style 3D Printed Naruto Long Sleeve',
                        isSelected : false,
                        postSelected: false,
                    },
                    {
                        productImg: '../../images/product-4.jpg',
                        productName: 'Unique Naruto Painting Graffiti Style 3D Printed Naruto Long Sleeve',
                        isSelected : false,
                        postSelected: false,
                    },
                    {
                        productImg: '../../images/product-1.jpg',
                        productName: 'Unique Naruto Painting Graffiti Style 3D Printed Naruto Long Sleeve',
                        isSelected : false,
                        postSelected: false,
                    },
                    {
                        productImg: '../../images/product-2.jpg',
                        productName: 'Unique Naruto Painting Graffiti Style 3D Printed Naruto Long Sleeve',
                        isSelected : false,
                        postSelected: false,
                    },
                    {
                        productImg: '../../images/product-3.jpg',
                        productName: 'Unique Naruto Painting Graffiti Style 3D Printed Naruto Long Sleeve',
                        isSelected : false,
                        postSelected: false,
                    },
                    {
                        productImg: '../../images/product-4.jpg',
                        productName: 'Unique Naruto Painting Graffiti Style 3D Printed Naruto Long Sleeve',
                        isSelected : false,
                        postSelected: false,
                    },
                ],

            }
        },
        methods : {
            setStep0: function(){
                this.showStep0 = true;
                this.showStep1 = false;
                this.showStep2 = false;
                this.sprintProgress= 0;
            },
            setStep1: function(){
              this.showStep1 = true;
              this.showStep0 = false;
              this.showStep2 = false;
              this.sprintProgress= 33;
              // this.increaseProgress();
              //   console.log( this.sprintProgress);
            },
            setStep2: function(){
                this.showStep2 = true;
                this.showStep0 = false;
                this.showStep1 = false;
                this.sprintProgress= 66;
                // this.increaseProgress();
                // console.log( this.sprintProgress);
            },
            increaseProgress :function(){
                this.sprintProgress=+ 33;
                return this.sprintProgress;
            },
            showChipTiming: function(){
              this.chipTiming = true;
            },
            showDialog: function(index){
                this.dialog = true;
            },
            postSelected: function(index){
                this.listedProducts[index].postSelected = true;
                this.dialog = true;
            },
            productSelected : function (index) {
                this.listedProducts[index].isSelected = true;
                this.makeACtive = false;
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
            nextStep: function () {
                this.showStep1 = false;
            },
            selectAllProducts: function () {
                if(this.selectAll === true){
                    for (var i = 0; i < this.listedProducts.length; i++) {
                        this.listedProducts[i].isSelected = true;
                        this.makeACtive = false;
                    }
                }else{
                    for (var i = 0; i < this.listedProducts.length; i++) {
                        this.listedProducts[i].isSelected = false;
                        this.makeACtive = true;
                    }
                }

            }
        },
        mounted: function () {
            console.log( this.sprintProgress);
        },
        computed: {
            fields () {
                if (!this.model) return [];

                return Object.keys(this.model).map(key => {
                    return {
                        key: key,
                        value: this.model[key] || 'n/a'
                    }
                })
            },
            items () {
                return this.entries.map(entry => {
                    const Description = entry.Description.length > this.descriptionLimit
                        ? entry.Description.slice(0, this.descriptionLimit) + '...'
                        : entry.Description

                    return Object.assign({}, entry, { Description })
                })
            }
        },

        watch: {
            search (val) {
                // Items have already been loaded
                if (this.items.length > 0) return

                // Items have already been requested
                if (this.isLoading) return

                this.isLoading = true

                // Lazily load input items
                axios.get('https://api.publicapis.org/entries')
                    .then(res => {
                        const { count, entries } = res.data
                        this.count = count
                        this.entries = entries
                    })
                    .catch(err => {
                        console.log(err)
                    })
                    .finally(() => (this.isLoading = false))
            }
        }
    }
</script>