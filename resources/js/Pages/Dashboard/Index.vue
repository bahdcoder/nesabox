<template>
    <layout>
        <div class="flex w-full justify-between items-center">
            <h3 class="text-xl font-semibold">Servers</h3>

            <router-link
                to="/servers/create"
                class="inline-flex items-center px-4 py-2 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-sha-green-500 hover:bg-sha-green-400 focus:outline-none focus:border-sha-green-600 focus:shadow-outline-sha-green active:bg-sha-green-600 transition ease-in-out duration-150"
            >
                Add new server
            </router-link>
        </div>
    </layout>
</template>

<script>
export default {
    mounted() {
        axios.get('/api/servers')
    }
}
</script>
