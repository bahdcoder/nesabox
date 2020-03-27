<template>
    <div>
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
                                <div class="flex items-center">
                                    <span
                                        class="inline-block mr-4 text-sm text-white"
                                    >
                                        {{ $root.auth.name }}
                                    </span>
                                    <button
                                        @click="open = !open"
                                        class="max-w-xs flex items-center text-sm rounded-full text-white focus:outline-none focus:shadow-solid"
                                    >
                                        <img
                                            class="h-8 w-8 rounded-full"
                                            :src="$root.auth.photo_url"
                                            :alt="$root.auth.name"
                                        />
                                    </button>
                                </div>
                                <div
                                    v-if="open"
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg"
                                >
                                    <div class="rounded-md bg-white shadow-xs">
                                        <div class="py-1">
                                            <router-link
                                                to="/account"
                                                class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                                                >Account</router-link
                                            >
                                        </div>
                                        <div
                                            class="border-t border-gray-100"
                                        ></div>
                                        <div class="py-1">
                                            <span
                                                class="w-full font-semibold inline-block mb-3 text-xs text-gray-900 px-4 py-3 border-b border-gray-100"
                                                >Projects</span
                                            >
                                            <a
                                                href="#"
                                                class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                                                >Archive</a
                                            >
                                            <a
                                                href="#"
                                                class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                                                >Move</a
                                            >
                                        </div>
                                        <div
                                            class="border-t border-gray-100"
                                        ></div>
                                        <div
                                            class="border-t border-gray-100"
                                        ></div>
                                        <div class="py-1 cursor-pointer">
                                            <a
                                                @click.prevent="signout"
                                                class="block px-4 py-2 text-sm leading-5 text-gray-700 hover:bg-gray-100 hover:text-gray-900 focus:outline-none focus:bg-gray-100 focus:text-gray-900"
                                                >Sign out</a
                                            >
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="-mr-2 flex md:hidden">
                        <button
                            @click="open = !open"
                            class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-white"
                        >
                            <svg
                                class="h-6 w-6"
                                stroke="currentColor"
                                fill="none"
                                viewBox="0 0 24 24"
                            >
                                <path
                                    :class="{
                                        hidden: open,
                                        'inline-flex': !open
                                    }"
                                    class="inline-flex"
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
                        <a
                            href="#"
                            class="block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700"
                            >Your Profile</a
                        >
                        <a
                            href="#"
                            class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700"
                            >Settings</a
                        >
                        <a
                            class="mt-1 block px-3 py-2 rounded-md text-base font-medium text-gray-400 hover:text-white hover:bg-gray-700 focus:outline-none focus:text-white focus:bg-gray-700"
                            >Sign out</a
                        >
                    </div>
                </div>
            </div>
        </nav>
        <main class="px-3">
            <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
                <slot />
            </div>
        </main>
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
        }
    }
}
</script>
