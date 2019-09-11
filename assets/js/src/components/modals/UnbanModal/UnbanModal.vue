<template>
    <modal name="unbanModal"
           :adaptive="true">
        <div class="modal">
            <div class="modal-heading">
                Unban {{ data.perp_name }}
            </div>
            <div class="modal-content">
                Are you sure you want to unban this player?
            </div>
            <div class="modal-footing">
                <button class="btn btn-warning">Yes</button>
                <button class="btn btn-default">Nope, Cancel</button>
            </div>
        </div>
    </modal>
</template>
<script src="unbanmodal.js"></script>