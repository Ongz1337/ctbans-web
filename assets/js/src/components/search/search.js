let lastQuery = null;
module.exports = {
    name: "search",

    data(){
        return {
            query: '',
            notValid: false,
        }
    },

    methods: {
        searchPlayer(e) {
            this.query = this.query.trim();
            if( this.length < 1 ) {
                this.notValid = true;
                return;
            }
            if( lastQuery === this.query )
                return;
            this.notValid = false;
            lastQuery = this.query;
            let url = e.target.action;
            this.$emit('player-search', {
                url: url,
                steamid:  this.query,
            });
        },

        hideSearchbox(e){
            this.$emit('hide-search');
        }
    }
}

function isValidSteamid($val) {
    re = new RegExp('^STEAM_[0-1]:[0-1]:[0-9]{7,8}$');
    return re.test($val.trim());
}