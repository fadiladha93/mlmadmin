<template>
    <div class="right-sidebar">
        <div class="search-wrap open">
            <div class="search-header">
                <div class="circle-btn">
                    <i class="la la-angle-right arrow"></i>
                </div>
                <span class="title">Downline Search</span>
            </div>
            <div class="search-section">
                <div class="title">Downline TSA Search</div>
                <div class="m-input-icon m-input-icon--right">
                    <input id="search-input" type="text" class="form-control m-input m-input--pill" placeholder="Search" v-on:keypress="onSearchKeyPress">
                    <span class="m-input-icon__icon m-input-icon__icon--right" v-on:click="sendSearchQuery"><span><i class="la la-search"></i></span></span>
                </div>
                <div class="list-header">
                    <!--<div class="level-header"></div>-->
                    <div class="leg-direction-checkboxes">
                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="radio" class="custom-control-input" id="legDirectionLeft"  name="leg" value="1" checked v-if="leg == 1">
                            <input type="radio" class="custom-control-input" id="legDirectionLeft"  name="leg" value="1" v-else>
                            <label class="custom-control-label" for="legDirectionLeft">LEFT</label>
                        </div>

                        <div class="custom-control custom-checkbox custom-control-inline">
                            <input type="radio" class="custom-control-input" id="legDirectionRight" name="leg" value="2" checked v-if="leg == 2">
                            <input type="radio" class="custom-control-input" id="legDirectionRight" name="leg" value="2" v-else>
                            <label class="custom-control-label" for="legDirectionRight">RIGHT</label>
                        </div>
                    </div>
                </div>
                <div class="list-wrap">
                    <div class="item" v-for="distributor in visibleDistributorsStart">
                        <User v-bind:distributor="distributor" v-bind:ranks="userRanks" v-bind:packs="userPacks" v-bind:level="level"/>
                    </div>
                    <button class="load-more-btn" v-on:click="onLoadMoreBtnClick" v-bind:class="{'loading': loading, 'visible': isLoadMoreBtnVisible}">
                        <span class="btn-txt">+ Show more</span>
                        <div class="ball-loader">
                            <div class="ball-loader-ball ball1"></div>
                            <div class="ball-loader-ball ball2"></div>
                            <div class="ball-loader-ball ball3"></div>
                        </div>
                    </button>
                    <div class="item" v-for="distributor in visibleDistributorsEnd">
                        <User v-bind:distributor="distributor" v-bind:ranks="userRanks" v-bind:packs="userPacks" v-bind:level="level"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import User from './SearchUser'
    import LevelIcon from './LevelIcon'

    export default {
        name: 'Search',
        props: {
            distributors: Array,
            end: Array,
            total: 0,
            ranks: Object,
            packs: Object,
            leg: 0,
            node: 0,
            level: 0
        },
        components: {
            User,
            LevelIcon
        },
        data() {
            return {
                loading: false,
                isLoadMoreBtnVisible: true,
                totalDistributorsAmount: 0,
                visibleDistributorsAmount: 0,
                visibleDistributorsStart: [],
                visibleDistributorsEnd: [],
                userPacks: [],
                userRanks: [],
                listPortionCount: 10,
                searchPortionCount: 5,
                legKey: 0
            }
        },
        beforeMount() {
            this.visibleDistributorsStart = this.distributors;
            this.visibleDistributorsEnd = this.end;
            this.totalDistributorsAmount = this.total;
            this.userRanks = this.ranks;
            this.userPacks = this.packs;
            this.legKey = this.leg;

            this.toggleLoadMoreBtn();
        },
        methods: {
            onSearchKeyPress(e) {
                // on Enter press
                if(e.keyCode === 13) {
                    this.sendSearchQuery();
                }
            },
            sendSearchQuery() {
                this.visibleDistributorsStart = [];
                this.visibleDistributorsEnd = [];
                this.visibleDistributorsAmount = 0;
                this.isLoadMoreBtnVisible = true;
                const me = this,
                    query = $('#search-input')[0].value;

                if ($('input[name=leg]:checked').length) {
                    $.ajax({
                        type: 'POST',
                        url: baseUrl + '/binary-viewer/init-search',
                        data: JSON.stringify({
                            search: query,
                            offset: 0,
                            limit: me.searchPortionCount,
                            leg: $('input[name=leg]:checked')[0].value,
                            currentNode: this.node
                        }),
                        cache: false,
                        dataType: 'json',
                        success: function (data) {
                            me.onSearchSuccess(data.distributors, data.total, data.distributorsEnd);
                        }
                    });
                } else {
                    this.isLoadMoreBtnVisible = false;
                    this.toggleLoadMoreBtn();
                }
            },
            onSearchSuccess(distributors, total, endDistributors) {
                this.visibleDistributorsStart = distributors;
                this.visibleDistributorsEnd = endDistributors;
                this.totalDistributorsAmount = total;
                this.visibleDistributorsAmount = distributors.length + endDistributors.length;
                this.toggleLoadMoreBtn();
            },
            toggleLoadMoreBtn() {
                this.visibleDistributorsAmount = this.visibleDistributorsStart.length + this.visibleDistributorsEnd.length;

                if ((this.totalDistributorsAmount - this.visibleDistributorsAmount) > 0) { return; }

                this.isLoadMoreBtnVisible = false;
            },
            onLoadMoreBtnClick() {
                if (this.loading) { return; }

                const me = this;

                this.loading = true;

                $.ajax({
                    type: 'POST',
                    url: baseUrl + '/binary-viewer/search',
                    data: JSON.stringify({
                        offset: me.visibleDistributorsStart.length,
                        limit: me.listPortionCount,
                        search:  $('#search-input')[0].value,
                        currentNode: this.node,
                        leg: $('input[name=leg]:checked')[0].value
                    }),
                    cache: false,
                    dataType: 'json',
                    success: function (data) {
                        me.addDistributors(data.distributors);

                        me.toggleLoadMoreBtn();
                    }
                });
            },
            addDistributors(distributors) {
                this.visibleDistributorsStart = this.visibleDistributorsStart.concat(distributors);
                this.visibleDistributorsAmount += distributors.length;
                this.loading = false;
            },
        }
    }
</script>
