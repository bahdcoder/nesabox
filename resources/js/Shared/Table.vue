<template>
    <div class="flex flex-col">
        <div
            class="-my-2 py-2 overflow-x-auto sm:-mx-6 sm:px-6 lg:-mx-8 lg:px-8"
        >
            <div
                class="align-middle inline-block min-w-full shadow overflow-hidden sm:rounded-lg border-b border-gray-200"
            >
                <table class="min-w-full">
                    <thead>
                        <tr>
                            <th
                                :key="header.value"
                                v-for="header in headers"
                                class="px-6 py-3 border-b border-gray-200 bg-gray-50 text-left text-xs leading-4 font-medium text-gray-500 uppercase tracking-wider"
                            >
                                {{ header.label }}
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white">
                        <tr
                            class="cursor-pointer hover:bg-gray-100"
                            @click="$emit('row-clicked', row)"
                            v-for="row in rows"
                            :key="row.id"
                        >
                            <td
                                :key="header.value"
                                v-for="header in headers"
                                class="px-6 py-4 whitespace-no-wrap border-b border-gray-200"
                            >
                                <slot name="row" :row="row" :header="header">
                                    {{ row[header.value] }}
                                </slot>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: {
        headers: {
            type: Array,
            required: true,
            default: () => []
        },
        rows: {
            type: Array,
            required: true,
            default: () => []
        }
    }
}
</script>
