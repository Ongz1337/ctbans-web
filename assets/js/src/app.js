// @todo transpile.
const moment = require('moment');
const Vue = require('vue/dist/vue');
const VTooltip = require('v-tooltip');
const sweetalert2 = require('sweetalert2');

// Vue Components
const Ban = require('./components/ban/Ban.vue');
const Search = require('./components/search/Search.vue');

// Modals
const deleteBanModal = require('./modals/delete');

// Date Formatter
const DateFilter = value => moment.unix(value).format('LLLL');

// Setup
const toast = sweetalert2.mixin({
  toast: true,
  position: 'top-end',
  showConfirmButton: false,
  timer: 10000,
});

const swal = sweetalert2.mixin({
    showCloseButton: true
});

Vue.use(VTooltip);
Vue.filter('dateformat', DateFilter);

let App = new Vue({
    'el': '#app',

    data: {
        bans: [],
        errors: [],
        loading: true,
        showSearch: false,
    },

    methods: {
        showModal(modalName, id){
            switch (modalName) {
                case "delete":
                    deleteBanModal(this, id);
                    break;
            }
        },

        hideLoading(){
            this.loading = false;
        },

        showLoading(){
          this.loading = true;
        },

        hideSearchbox(){
            this.showSearch = false;
        },

        showSearchbox(){
          this.showSearch = true;
        },

        playerSearch(resource){
            this.showLoading();
            this.bans = [];
            fetch(resource.url + encodeURIComponent(resource.steamid))
                .then(resp => {
                    return resp.json();
                })
                .then(resp => {
                    if(resp.error){
                        throw new Error('Server error. Please try again.');
                    }
                    this.bans = resp.data;
                })
                .catch(reason => {
                    this.errors.push('Error fetching data (' + reason + ').');
                })
                .finally(() => {
                   this.hideLoading();
                });
        },

        getPlayerData(banId){
            return this.bans.filter(el => el.ban_id === banId);
        },
    },

    mounted(){
        // let me = this;
        this.$on("showModal", data => {
            this.showModal(data.modalName, data.banId)
        });
        fetch(AJAX_URL+'?action=getBans&val=all')
            .then(resp => resp.json())
            .then(resp => {
                if(resp.error){
                    throw new Error('Server error. Please try again.');
                }
                this.bans = resp.data;
            })
            .catch(reason => {
                // this.errors.push('Error getting bans (' + reason + ').');
                toast({type: 'error', text: 'Error getting bans (' + reason + ').'});
            })
            .finally(() => {
                this.hideLoading();
            });
    },

    components: {
        Ban,
        Search,
    },
});

window.App = App;