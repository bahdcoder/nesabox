<template>
    <div class="flex flex-col min-h-screen">
        <nav class="bg-gray-800">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <img
                                class="h-12 w-12"
                                src="/assets/images/logo-light-small.svg"
                                alt=""
                            />
                        </div>
                        <div class="hidden md:block">
                            <div class="ml-10 flex items-baseline">
                                <router-link
                                    to="/dashboard"
                                    class="px-3 py-2 rounded-md text-sm font-medium text-white focus:outline-none focus:text-white focus:bg-gray-700"
                                    >Dashboard</router-link
                                >
                            </div>
                        </div>
                    </div>
                    <div class="hidden md:block">
                        <div class="ml-4 flex items-center md:ml-6">
                            <button
                                class="p-1 border-2 border-transparent text-gray-400 rounded-full hover:text-white focus:outline-none focus:text-white focus:bg-gray-700"
                            ></button>
                            <div class="ml-3 relative">
                                <div
                                    class="flex items-center image-button"
                                    @click="open = !open"
                                >
                                    <button
                                        class="image-button max-w-xs flex items-center text-sm rounded-full text-white focus:outline-none focus:shadow-solid"
                                    >
                                        <img
                                            class="image-button h-8 w-8 rounded-full"
                                            :src="$root.auth.photo_url"
                                            :alt="$root.auth.name"
                                        />
                                    </button>
                                    <span
                                        class="inline-block ml-4 cursor-pointer image-button text-sm text-white"
                                    >
                                        {{ $root.auth.name }}
                                    </span>

                                    <svg
                                        class="image-button w-3 ml-2 cursor-pointer h-3 fill-current"
                                        version="1.1"
                                        id="Capa_1"
                                        xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink"
                                        x="0px"
                                        y="0px"
                                        viewBox="0 0 292.362 292.362"
                                        style="enable-background:new 0 0 292.362 292.362;"
                                        xml:space="preserve"
                                    >
                                        <g>
                                            <path
                                                fill="#fff"
                                                d="M286.935,69.377c-3.614-3.617-7.898-5.424-12.848-5.424H18.274c-4.952,0-9.233,1.807-12.85,5.424
                                                C1.807,72.998,0,77.279,0,82.228c0,4.948,1.807,9.229,5.424,12.847l127.907,127.907c3.621,3.617,7.902,5.428,12.85,5.428
                                                s9.233-1.811,12.847-5.428L286.935,95.074c3.613-3.617,5.427-7.898,5.427-12.847C292.362,77.279,290.548,72.998,286.935,69.377z"
                                            />
                                        </g>
                                    </svg>
                                </div>
                                <transition name="account-menu">
                                    <div
                                        v-show="open"
                                        v-click-outside="handleOutsideClick"
                                        class="account-menu origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg"
                                    >
                                        <div
                                            class="rounded-md bg-white shadow-xs"
                                        >
                                            <div class="py-1">
                                                <router-link
                                                    to="/account"
                                                    class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                                                    >Account</router-link
                                                >
                                            </div>
                                            <div class="py-1">
                                                <router-link
                                                    to="/account/teams"
                                                    class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                                                    >Teams</router-link
                                                >
                                            </div>
                                            <div class="py-1">
                                                <router-link
                                                    to="/account/subscription"
                                                    class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                                                    >Subscription</router-link
                                                >
                                            </div>
                                            <div class="py-1 cursor-pointer">
                                                <a
                                                    @click.prevent="signout"
                                                    class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                                                    >Sign out</a
                                                >
                                            </div>
                                        </div>
                                    </div>
                                </transition>
                            </div>
                        </div>
                    </div>
                    <div class="-mr-2 flex md:hidden">
                        <button
                            @click="open = !open"
                            class="image-button inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-white"
                        >
                            <svg
                                class="h-6 w-6 image-button"
                                stroke="currentColor"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    :class="{
                                        hidden: open,
                                        'inline-flex': !open
                                    }"
                                    class="image-button"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M4 6h16M4 12h16M4 18h16"
                                />
                                <path
                                    :class="{
                                        hidden: !open,
                                        'inline-flex': open
                                    }"
                                    class="hidden"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            <div :class="{ block: open, hidden: !open }" class="md:hidden">
                <div class="px-2 pt-2 pb-3 sm:px-3">
                    <router-link
                        to="/dashboard"
                        class="block px-3 py-2 rounded-md text-base font-medium text-white focus:outline-none focus:text-white focus:bg-gray-700"
                        >Dashboard</router-link
                    >
                </div>
                <div class="pt-4 pb-3 border-t border-gray-700">
                    <div class="flex items-center px-5">
                        <div class="flex-shrink-0">
                            <img
                                class="h-10 w-10 rounded-full"
                                :src="$root.auth.photo_url"
                                :alt="$root.auth.name"
                            />
                        </div>
                        <div class="ml-3">
                            <div
                                class="text-base font-medium leading-none text-white"
                            >
                                {{ $root.auth.name }}
                            </div>
                            <div
                                class="mt-1 text-sm font-medium leading-none text-gray-400"
                            >
                                {{ $root.auth.email }}
                            </div>
                        </div>
                    </div>
                    <div class="mt-3 px-2">
                        <router-link
                            to="/account"
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700"
                            >Your account</router-link
                        >
                        <router-link
                            to="/account/teams"
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700"
                            >Teams</router-link
                        >
                        <router-link
                            to="/account/subscription"
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700"
                            >Subscription</router-link
                        >
                        <a
                            @click.prevent="signout"
                            class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700"
                            >Sign out</a
                        >
                    </div>
                </div>
            </div>
        </nav>
        <main class="px-3 flex-grow mb-12 md:mb-24">
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <slot />
            </div>
        </main>

        <div class="bg-gray-800">
            <div
                class="max-w-screen-xl mx-auto py-8 px-4 sm:px-6 md:flex md:items-center md:justify-between lg:px-8"
            >
                <div class="flex justify-center md:order-2">
                    <a
                        href="https://twitter.com/bahdcoder"
                        class="ml-6 text-gray-400 hover:text-gray-500"
                    >
                        <span class="sr-only">Twitter</span>
                        <svg
                            class="h-6 w-6"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84"
                            />
                        </svg>
                    </a>
                    <a
                        href="https://github.com/bahdcoder"
                        class="ml-6 text-gray-400 hover:text-gray-500"
                    >
                        <span class="sr-only">GitHub</span>
                        <svg
                            class="h-6 w-6"
                            fill="currentColor"
                            viewBox="0 0 24 24"
                        >
                            <path
                                fill-rule="evenodd"
                                d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                                clip-rule="evenodd"
                            />
                        </svg>
                    </a>
                </div>
                <div class="mt-8 md:mt-0 md:order-1">
                    <p class="text-center text-base leading-6 text-gray-400">
                        &copy; {{ new Date().getFullYear() }} A Project by Kati
                        Frantz
                    </p>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data() {
        return {
            open: false,
            projectsOpen: false
        }
    },
    methods: {
        signout() {
            axios.post('/logout').then(() => {
                window.location.href = '/'
            })
        },
        handleOutsideClick(event) {
            if (event.target.className) {
                if (
                    typeof event.target.className === 'string' &&
                    event.target.className.match(/image-button/)
                ) {
                    return
                }

                if (
                    event.target.className.baseVal &&
                    event.target.className.baseVal.match(/image-button/)
                ) {
                    return
                }
            }

            if (!this.open) {
                return
            }

            this.open = !this.open
        }
    }
}
</script>
