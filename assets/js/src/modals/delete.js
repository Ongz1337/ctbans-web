const swal = require('sweetalert2');
const post = require("./../request/post");

/**
 *
 * @param app Vue instance
 * @param banId
 */
module.exports = function (app, banId) {
    let url = SITE_URL + "/admin.php?action=remove&id=" + banId;
    let type;
    swal({
        title: "Remove ban",
        text: "Are you sure you want to remove this ban?",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Delete'
    }).then(r => {
        if(r.value){
            app.showLoading();
            post(url).then(data => {
                type = data.error ? "error" : "success";
                app.bans = app.bans.filter(ban => ban.ban_id !== banId);
                swal({
                   type: type,
                   text: data.msg,
                   title: "Remove ban"
                });
            }).finally(() => {
                app.hideLoading();
            });
        }
    });
}