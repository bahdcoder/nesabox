<template>
    <button
        type="button"
        @click="$emit('click')"
        class="border-2 border-red-500 p-2 rounded hover:bg-red-100 shadow"
    >
        <svg
            width="20"
            height="20"
            fill="none"
            viewBox="0 0 24 24"
            class="cursor-pointer"
            xmlns="http://www.w3.org/2000/svg"
        >
            <path
                d="M10 14L12 12M12 12L14 10M12 12L10 10M12 12L14 14M21 12C21 16.9706 16.9706 21 12 21C7.02944 21 3 16.9706 3 12C3 7.02944 7.02944 3 12 3C16.9706 3 21 7.02944 21 12Z"
                stroke="#f05252"
                stroke-width="2"
                stroke-linecap="round"
                stroke-linejoin="round"
            />
        </svg>
    </button>
</template>

<script>
export default {}
</script>
