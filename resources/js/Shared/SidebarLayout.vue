<template>
    <layout>
        <slot name="header" />
        <div class="w-full flex flex-wrap">
            <div v-if="showNav" class="w-full md:w-1/5">
                <nav>
                    <router-link
                        :to="item.to"
                        :key="item.value"
                        v-for="item in nav"
                        class="mt-1 group flex items-center px-3 py-2 text-sm leading-5 font-medium rounded-md focus:outline-none transition ease-in-out duration-150"
                        :class="{
                            'bg-gray-200 focus:bg-gray-100 text-gray-900 hover:text-gray-900':
                                active === item.to,
                            'hover:bg-gray-50 text-gray-600': active !== item.to
                        }"
                    >

<svg
v-if="item.value === 'logs'"
                            class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 transition ease-in-out duration-150"
                            :class="{
                                'text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500':
                                    active !== item.to,
                                'text-gray-600 group-hover:text-gray-700 group-focus:text-gray-700':
                                    active === item.to
                            }"
 viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M9 2C8.44772 2 8 2.44772 8 3C8 3.55228 8.44772 4 9 4H11C11.5523 4 12 3.55228 12 3C12 2.44772 11.5523 2 11 2H9Z" fill="#4A5568"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M4 5C4 3.89543 4.89543 3 6 3C6 4.65685 7.34315 6 9 6H11C12.6569 6 14 4.65685 14 3C15.1046 3 16 3.89543 16 5V16C16 17.1046 15.1046 18 14 18H6C4.89543 18 4 17.1046 4 16V5ZM7 9C6.44772 9 6 9.44772 6 10C6 10.5523 6.44772 11 7 11H7.01C7.56228 11 8.01 10.5523 8.01 10C8.01 9.44772 7.56228 9 7.01 9H7ZM10 9C9.44772 9 9 9.44772 9 10C9 10.5523 9.44772 11 10 11H13C13.5523 11 14 10.5523 14 10C14 9.44772 13.5523 9 13 9H10ZM7 13C6.44772 13 6 13.4477 6 14C6 14.5523 6.44772 15 7 15H7.01C7.56228 15 8.01 14.5523 8.01 14C8.01 13.4477 7.56228 13 7.01 13H7ZM10 13C9.44772 13 9 13.4477 9 14C9 14.5523 9.44772 15 10 15H13C13.5523 15 14 14.5523 14 14C14 13.4477 13.5523 13 13 13H10Z" fill="#4A5568"/>
</svg>
                        <svg
                            v-if="item.value === 'sites'"
                            class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 transition ease-in-out duration-150"
                            :class="{
                                'text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500':
                                    active !== item.to,
                                'text-gray-600 group-hover:text-gray-700 group-focus:text-gray-700':
                                    active === item.to
                            }"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M9 17V7M9 17C9 18.1046 8.10457 19 7 19H5C3.89543 19 3 18.1046 3 17V7C3 5.89543 3.89543 5 5 5H7C8.10457 5 9 5.89543 9 7M9 17C9 18.1046 9.89543 19 11 19H13C14.1046 19 15 18.1046 15 17M9 7C9 5.89543 9.89543 5 11 5H13C14.1046 5 15 5.89543 15 7M15 17V7M15 17C15 18.1046 15.8954 19 17 19H19C20.1046 19 21 18.1046 21 17V7C21 5.89543 20.1046 5 19 5H17C15.8954 5 15 5.89543 15 7"
                                stroke="#4A5568"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>

                        <svg
                            v-if="item.value === 'files'"
                            class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 transition ease-in-out duration-150"
                            :class="{
                                'text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500':
                                    active !== item.to,
                                'text-gray-600 group-hover:text-gray-700 group-focus:text-gray-700':
                                    active === item.to
                            }"
                            stroke="currentColor"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"
                            />
                        </svg>

                        <svg
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                            v-if="item.value === 'settings'"
                            class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 transition ease-in-out duration-150"
                            :class="{
                                'text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500':
                                    active !== item.to,
                                'text-gray-600 group-hover:text-gray-700 group-focus:text-gray-700':
                                    active === item.to
                            }"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"
                            />
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"
                            />
                        </svg>

                        <svg
                            v-if="item.value === 'source-control'"
                            class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 transition ease-in-out duration-150"
                            :class="{
                                'text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500':
                                    active !== item.to,
                                'text-gray-600 group-hover:text-gray-700 group-focus:text-gray-700':
                                    active === item.to
                            }"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"
                            />
                        </svg>

                        <svg
                            v-if="item.value === 'account'"
                            class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 transition ease-in-out duration-150"
                            :class="{
                                'text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500':
                                    active !== item.to,
                                'text-gray-600 group-hover:text-gray-700 group-focus:text-gray-700':
                                    active === item.to
                            }"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M16 7C16 9.20914 14.2091 11 12 11C9.79086 11 8 9.20914 8 7C8 4.79086 9.79086 3 12 3C14.2091 3 16 4.79086 16 7Z"
                                stroke="#4A5568"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                            <path
                                d="M12 14C8.13401 14 5 17.134 5 21H19C19 17.134 15.866 14 12 14Z"
                                stroke="#4A5568"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>

                        <svg
                            v-if="item.value === 'server-providers'"
                            class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 transition ease-in-out duration-150"
                            :class="{
                                'text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500':
                                    active !== item.to,
                                'text-gray-600 group-hover:text-gray-700 group-focus:text-gray-700':
                                    active === item.to
                            }"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M8 16C5.23858 16 3 13.7614 3 11C3 8.55154 4.75992 6.51413 7.08376 6.08376C7.51412 3.75992 9.55154 2 12 2C14.4485 2 16.4859 3.75992 16.9162 6.08376C19.2401 6.51413 21 8.55154 21 11C21 13.7614 18.7614 16 16 16M9 19L12 22M12 22L15 19M12 22V10"
                                stroke="#4A5568"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>

                        <svg
                            v-if="item.value === 'databases'"
                            class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 transition ease-in-out duration-150"
                            :class="{
                                'text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500':
                                    active !== item.to,
                                'text-gray-600 group-hover:text-gray-700 group-focus:text-gray-700':
                                    active === item.to
                            }"
                            stroke="currentColor"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"
                            />
                        </svg>

                        <svg
                            v-if="item.value === 'scheduler'"
                            class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 transition ease-in-out duration-150"
                            :class="{
                                'text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500':
                                    active !== item.to,
                                'text-gray-600 group-hover:text-gray-700 group-focus:text-gray-700':
                                    active === item.to
                            }"
                            stroke="currentColor"
                            fill="none"
                            viewBox="0 0 24 24"
                        >
                            <path
                                stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"
                            />
                        </svg>

                        <svg
                            v-if="item.value === 'network'"
                            class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 transition ease-in-out duration-150"
                            :class="{
                                'text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500':
                                    active !== item.to,
                                'text-gray-600 group-hover:text-gray-700 group-focus:text-gray-700':
                                    active === item.to
                            }"
                            viewBox="0 0 24 24"
                            fill="none"
                            xmlns="http://www.w3.org/2000/svg"
                        >
                            <path
                                d="M21 12C21 16.9706 16.9706 21 12 21M21 12C21 7.02944 16.9706 3 12 3M21 12H3M12 21C7.02944 21 3 16.9706 3 12M12 21C13.6569 21 15 16.9706 15 12C15 7.02944 13.6569 3 12 3M12 21C10.3431 21 9 16.9706 9 12C9 7.02944 10.3431 3 12 3M3 12C3 7.02944 7.02944 3 12 3"
                                stroke="#4A5568"
                                stroke-width="2"
                                stroke-linecap="round"
                                stroke-linejoin="round"
                            />
                        </svg>
                        <span class="truncate">
                            {{ item.label }}
                        </span>
                    </router-link>
                </nav>
            </div>
            <div class="w-full md:w-4/5 md:pl-6">
                <slot />
            </div>
        </div>
    </layout>
</template>

<script>
export default {
    props: {
        nav: {
            type: Array,
            required: false,
            default: () => []
        },
        showNav: {
            required: false,
            type: Boolean,
            default: false
        },
        active: {
            type: String,
            required: false
        }
    }
}
</script>
