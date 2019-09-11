module.exports = {
    name: "ban",
    props: {
        data: {
            type: Object,
        }
    },

    computed: {
        bantimeClass() {
            let $class = '';
            if( Number(this.data.timeleft) >= 0 )
                $class = 'banned';
            else
                $class = 'unbanned';
            return $class;
        },

        bantimeReadable() {
            let text = '';
            let timeleft = Number(this.data.timeleft);
            if ( timeleft > 0)
                text = timeleft + ' mins. left of ' + this.data.bantime;
            else if( timeleft === 0 )
                text = 'permanent';
            else
                text = 'unbanned';
            return text;
        },
    },

    methods: {
        isAdmin() {
            return typeof IS_ADMIN !== 'undefined' && IS_ADMIN;
        },

        showDeleteBanModal(event, banId) {
            this.$parent.$emit('showModal', {
                modalName: "delete",
                banId: banId
            });
        },

        showModifyBanModal(event, banId) {
            this.$parent.$emit('showModal', {
                modalName: "modify",
                banId: banId
            });
        },

        showUnbanModal(event, banId) {
            this.$parent.$emit('showModal', {
                modalName: "unban",
                banId: banId
            });
        }
    }
};