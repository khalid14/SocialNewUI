<template>
    <v-container grid-list-md text-xs-center class="pa-0 pb-5">
        <v-layout row wrap>
            <v-flex xs12>
                <h3 class="text-xs-left">Social Analytics</h3>
            </v-flex>
            <v-flex xs12>
                <v-tabs color="cyan" dark slider-color="primary">
                    <v-tab ripple>
                        Overview
                    </v-tab>
                    <v-tab-item>
                        <v-card flat>
                            <v-card-text class="text-xs-left">
                                Track the performance of your social posts with our advanced analytics dashboard
                            </v-card-text>
                            <v-card-text>
                                <v-flex xs12 sm2 d-flex class="right">
                                    <v-select
                                            :items="filterOptions"
                                            label="Select Metric"
                                            solo
                                    ></v-select>
                                </v-flex>
                            </v-card-text>
                            <v-card-text class="mt-5">
                                <line-chart
                                        id="line" :data="lineData" xkey="year" ykeys='[ "a", "b" ]' resize="true"
                                        labels='[ "Serie A", "Serie B" ]' line-colors='[ "#FF6384", "#36A2EB" ]'
                                        grid="true" grid-text-weight="bold">
                                </line-chart>
                            </v-card-text>
                        </v-card>
                    </v-tab-item>
                    <v-tab ripple>
                        Growth Chart
                    </v-tab>
                    <v-tab-item>
                        <v-card flat>
                            <v-card-text class="text-xs-left">
                                Grow your social following month after month by sharing the awesome content
                            </v-card-text>
                        </v-card>
                    </v-tab-item>
                    <v-tab ripple>
                        Social Traffic
                    </v-tab>
                    <v-tab-item>
                        <v-card flat>
                            <v-card-text class="text-xs-left">
                                Get some insights into how much store visitors you are getting from your social
                            </v-card-text>
                        </v-card>
                    </v-tab-item>
                    <v-tab ripple>
                        Social Revenue
                    </v-tab>
                    <v-tab-item>
                        <v-card flat>
                            <v-card-text class="text-xs-left">
                                Analysis of your social sprints are helping your store revenue
                            </v-card-text>
                        </v-card>
                    </v-tab-item>
                </v-tabs>
            </v-flex>
        </v-layout>
    </v-container>
</template>


<script>
    import Raphael from 'raphael/raphael'
    global.Raphael = Raphael;
    import { DonutChart, BarChart, LineChart, AreaChart } from 'vue-morris'
    // donut-chart : 'donut-chart';
    export default {
        name: 'app',
        data () {
            return {
                filterOptions: ['Impressions','Clicks','Likes/React','Comments','Shares','Revenue'],
                donutData: [
                    { label: 'Red', value: 300 },
                    { label: 'Blue', value: 50 },
                    { label: 'Yellow', value: 100 }
                ],
                barData: [
                    { year: '2013', and: 10, ios: 5, win: 2 },
                    { year: '2014', and: 10, ios: 15, win: 3 },
                    { year: '2015', and: 20, ios: 25, win: 2 },
                    { year: '2016', and: 30, ios: 20, win: 1 },
                ],
                lineData: [
                    { year: '2013', a: 10, b: 5 },
                    { year: '2014', a: 40, b: 15 },
                    { year: '2015', a: 20, b: 25 },
                    { year: '2016', a: 30, b: 20 },
                ],
                areaData: [
                    { year: '2013', a: 30, b: 5 },
                    { year: '2014', a: 25, b: 15 },
                    { year: '2015', a: 29, b: 25 },
                    { year: '2016', a: 50, b: 20 },
                ]
            }
        },
        components: {
            DonutChart, BarChart, LineChart, AreaChart
        },
        mounted () {
            setInterval(() => {
                this.lineData = [
                    { year: '2013', a: this.rand(100), b: this.rand(100) },
                    { year: '2014', a: this.rand(100), b: this.rand(100) },
                    { year: '2015', a: this.rand(100), b: this.rand(100) },
                    { year: '2016', a: this.rand(100), b: this.rand(100) },
                ]
            }, 5000)
        },
        methods: {
            rand (limit) {
                return Math.round(Math.random() * limit)
            },
            osColors () {
                console.log('osColors');
                return []
            }
        }
    }
</script>