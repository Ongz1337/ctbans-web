<template>
    <div class="searchBox" @keyup.esc="hideSearchbox">
        <div class="close" @click="hideSearchbox">
            &times;
        </div>
        <form action="ajax.php?action=getBans&val=" method="POST" @submit.prevent="searchPlayer">
            <label>
                <input type="text" v-model="query" name="query" id="query" placeholder="steamid, or name">
            </label>
            <button type="submit" value=""></button>
        </form>
        <div class="error" v-show="notValid">
            sight... don't leave it empty
        </div>
    </div>
</template>

<script src="search.js"></script>