<template>

    <div>
        <v-container grid-list-md text-xs-center>
            <v-layout row wrap>
                <v-flex xs12 class="mb-5">
                    <v-card>
                        <v-card-text class="text-xs-left tableHead pl-5" style="font-size: 20px;">Social Connection:</v-card-text>
                        <v-card-text class="pb-4 pt-4">
                            <v-layout row wrap class="connection-cta">
                                <v-flex md3>
                                    <i class="fab fa-facebook-f mb-2" style="font-size: 26px;display:block;color:#3b5999"></i>
                                    <v-btn flat class="back-cta pt-2 pb-2 pl-3 pr-4 " @click.prevent="loginPopup($event)">
                                        <v-icon class="pa-1 mr-3" style="border-radius: 100%">link</v-icon>
                                        Connected
                                    </v-btn>
                                    <div id="logout-facebook" @click.prevent="logoutPopup($event)">
                                    </div>
                                </v-flex>
                                <v-flex md3>
                                    <i class="fab fa-instagram mb-2" style="font-size: 26px;display:block;color:#a82faf"></i>
                                    <v-btn  flat class="back-cta pt-2 pb-2 pl-3 pr-4 ">
                                        <v-icon class=" pa-1 mr-3" style="border-radius: 100%">link</v-icon>
                                        Connected
                                    </v-btn>
                                </v-flex>
                                <v-flex md3>
                                    <i class="fab fa-twitter mb-2" style="font-size: 26px;display:block;color:rgb(181, 181, 181)"></i>
                                    <v-btn  flat class="red--text back-cta pt-2 pb-2 pl-3 pr-4 ">
                                        <v-icon class=" pa-1 mr-3" style="border-radius: 100%">link_off</v-icon>
                                        Not Connected
                                    </v-btn>
                                </v-flex>
                                <v-flex md3>
                                    <i class="fab fa-pinterest mb-2" style="font-size: 26px;display:block;color:#bd071d"></i>
                                    <v-btn  flat class="back-cta pt-2 pb-2 pl-3 pr-4 ">
                                        <v-icon class=" pa-1 mr-3" style="border-radius: 100%">link</v-icon>
                                        Connected
                                    </v-btn>
                                </v-flex>
                            </v-layout>
                        </v-card-text>
                    </v-card>

                </v-flex>
            </v-layout>
            <v-toolbar flat color="white" class="my-sprint-listing">
                <v-toolbar-title>My Sprints</v-toolbar-title>
                <v-divider
                        class="mx-2"
                        inset
                        vertical
                ></v-divider>
                <v-spacer></v-spacer>
                <v-menu offset-y>
                    <v-btn
                            slot="activator"
                            color="ctaColor"
                            dark
                            class="pl-4 pr-4 pt-3 pb-3 mb-3"
                            style="height: auto"
                    >
                        Create Sprint
                    </v-btn>
                    <v-list>
                        <v-list-tile to="/singlePost">
                            <v-list-tile-title>Single Post</v-list-tile-title>
                        </v-list-tile>
                        <v-list-tile to="/manualScheduling">
                            <v-list-tile-title>Manual Sprint</v-list-tile-title>
                        </v-list-tile>
                        <v-list-tile to="/autoPilog">
                            <v-list-tile-title>Auto Sprint</v-list-tile-title>
                        </v-list-tile>
                    </v-list>
                </v-menu>
            </v-toolbar>
            <v-data-table
                    :headers="headers"
                    :items="desserts"
                    hide-actions
                    class="elevation-1 sprint-db"
            >
                <template slot="items" slot-scope="props">
                    <td class="text-xs-left">{{ props.item.name }}</td>
                    <td class="text-xs-center">{{ props.item.calories }}</td>
                    <td class="text-xs-center">{{ props.item.fat }}</td>
                    <td class="text-xs-center">{{ props.item.carbs }}</td>
                    <td class="text-xs-center">{{ props.item.protein }}</td>
                    <td class="text-xs-center">{{ props.item.shares }}</td>
                    <td class="justify-center layout px-0">
                        <v-icon
                                small
                                class="mr-2"
                                @click="editItem(props.item)"
                        >
                            edit
                        </v-icon>
                        <v-icon
                                small
                                @click="deleteItem(props.item)"
                        >
                            delete
                        </v-icon>
                    </td>
                </template>
                <template slot="no-data">
                    <v-btn color="primary" @click="initialize">Reset</v-btn>
                </template>
            </v-data-table>
        </v-container>

    </div>
</template>


<script>
    export default {
        data: () => ({
            dialog: false,
            btnVal:null,
            headers: [
                {text: 'Sprint Name', align: 'left', sortable: false, value: 'name'},
                { text: 'Status',sortable: false,align: 'center', value: 'calories' },
                { text: 'Impressions',sortable: false,align: 'center', value: 'fat' },
                { text: 'Clicks',sortable: false,align: 'center', value: 'carbs' },
                { text: 'Comments',sortable: false,align: 'center', value: 'protein' },
                { text: 'Shares|Repins',sortable: false,align: 'center', value: 'shares' },
                { text: 'Actions',sortable: false,align: 'center', value: 'name' }
            ],
            desserts: [],
            editedIndex: -1,
            editedItem: {
                name: '',
                calories: 0,
                fat: 0,
                carbs: 0,
                protein: 0,
                shares: 0
            },
            defaultItem: {
                name: '',
                calories: 0,
                fat: 0,
                carbs: 0,
                protein: 0,
                shares: 0
            }
        }),

        computed: {
            formTitle () {
                return this.editedIndex === -1 ? 'New Item' : 'Edit Item'
            }
        },

        watch: {
            dialog (val) {
                val || this.close()
            }
        },

        created () {
            let self = this;
            self.initialize();
        },
        mounted(){
            let self = this;
            self.loadSocial();
        },

        methods: {
            initialize () {
                this.desserts = [
                    {
                        name: 'Frozen Yogurt',
                        calories: 'Completed',
                        fat: 6.0,
                        carbs: 24,
                        protein: 4.0,
                        shares: 12
                    },
                    {
                        name: 'Ice cream sandwich',
                        calories: 'Draft',
                        fat: 9.0,
                        carbs: 37,
                        protein: 4.3,
                        shares: 14
                    },
                    {
                        name: 'Eclair',
                        calories: 'Running',
                        fat: 16.0,
                        carbs: 23,
                        protein: 6.0,
                        shares: 22
                    },
                    {
                        name: 'Cupcake',
                        calories: 'Completed',
                        fat: 3.7,
                        carbs: 67,
                        protein: 4.3,
                        shares: 16
                    },
                    {
                        name: 'Gingerbread',
                        calories: 'Draft',
                        fat: 16.0,
                        carbs: 49,
                        protein: 3.9,
                        shares: 19
                    },
                    {
                        name: 'Jelly bean',
                        calories: 'Running',
                        fat: 0.0,
                        carbs: 94,
                        protein: 0.0,
                        shares: 62
                    },
                ]
            },

            editItem (item) {
                this.editedIndex = this.desserts.indexOf(item);
                this.editedItem = Object.assign({}, item);
                this.dialog = true
            },

            deleteItem (item) {
                const index = this.desserts.indexOf(item)
                confirm('Are you sure you want to delete this item?') && this.desserts.splice(index, 1)
            },

            close () {
                this.dialog = false;
                setTimeout(() => {
                    this.editedItem = Object.assign({}, this.defaultItem)
                    this.editedIndex = -1
                }, 300);
            },

            save () {
                if (this.editedIndex > -1) {
                    Object.assign(this.desserts[this.editedIndex], this.editedItem)
                } else {
                    this.desserts.push(this.editedItem)
                }
                this.close()
            },
            loadSocial (){
                 let self = this;
                axios.get('/social-media-config').then(function (response) {
                    if (response.data != '') {
                        self.setSocialLink(response.data);
                    }
                });
            },
            setSocialLink(data){
                if ($('#logout-'+data.platform).length > 0){
                    $('#logout-'+data.platform).html(data.logoutLink);
                    $('#logout-'+data.platform).siblings('button').val(data.loginLink);
                }
            },
            loginPopup(event){
                var element = event.currentTarget;
                var url = $(element).val();
                window.open(url, "_blank", "location=0,menubar=0,toolbar=no,scrollbars=yes,resizable=yes,top=500,left=500,width=600,height=450");
                event.preventDefault();
            },
            logoutPopup(event){
                // get current clicked
                var element = event.currentTarget;
                var url = $(element).find('a').attr('href');
                window.open(url, "_blank", "location=0,menubar=0,toolbar=no,scrollbars=yes,resizable=yes,top=500,left=500,width=600,height=450");
                event.preventDefault();
            },
        }
    }
</script>