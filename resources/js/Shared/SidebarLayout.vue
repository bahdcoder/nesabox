<template>
    <layout>
        <slot name="header" />
        <div class="w-full flex flex-wrap">
            <div class="w-full md:w-1/5">
                <nav>
                    <a
                        href="#"
                        :key="item.value"
                        v-for="item in nav"
                        class="mt-1 group flex items-center px-3 py-2 text-sm leading-5 font-medium text-gray-600 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150"
                    >
                        <svg
                            class="flex-shrink-0 -ml-1 mr-3 h-6 w-6 text-gray-400 group-hover:text-gray-500 group-focus:text-gray-500 transition ease-in-out duration-150"
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
                        <span class="truncate">
                            {{ item.label }}
                        </span>
                    </a>
                </nav>
            </div>
            <div class="w-full md:w-4/5">
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
        }
    }
}
</script>
