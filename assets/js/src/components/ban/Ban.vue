<template>
    <div class="ban">
        <div class="avatar">
            <img :src="data.avatar" :title="data.perp_name">
        </div>
        <div class="info">
            <div class="ban-info">
                <span class="name"><strong>{{ data.perp_name }}</strong></span>
                <span class="steamid">
                        {{ data.perp_steamid }}
                </span>
                <span class="bantime-date">
                    <span :class="'bantime ' + bantimeClass" :title="'Length of ban'">{{ bantimeReadable }}</span>
                    <span class="date" :title="'Date of ban'">
                        {{ data.timestamp | dateformat }}
                    </span>
                </span>
            </div>
            <div class="reason">
                {{ data.reason }}
            </div>
            <div class="admin">
                Admin:
                <span class="name">
                    {{ data.admin_name }}
                </span>
            </div>
        </div>
        <div class="icons" v-if="isAdmin()">
            <ul>
                <li class="icon">
                    <a @click.prevent='showUnbanModal($event, data.ban_id)'>
                        <img src="assets/img/icons/unban.png" alt="" v-tooltip="'unban'">
                    </a>
                </li>
                <li class="icon">
                    <a @click.prevent='showModifyBanModal($event, data.ban_id)'>
                        <img src="assets/img/icons/modify.png" alt="" v-tooltip.left="'modify'">
                    </a>
                </li>
                <li class="icon">
                    <a @click.prevent='showDeleteBanModal($event, data.ban_id)'>
                        <img src="assets/img/icons/delete.png" alt="" v-tooltip="'delete'">
                    </a>
                </li>
            </ul>
        </div>
    </div>
</template>

<script src="ban.js"></script>