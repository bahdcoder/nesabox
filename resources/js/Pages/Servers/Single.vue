<template>
    <sidebar-layout :nav="nav">
        <template slot="header">
            <!-- <div class="h-12 w-full mb-5"></div> -->
        </template>
        <div class="shadow mt-4 md:mt-0">
            <div
                class="bg-white px-4 py-5 border-b border-gray-200 sm:px-6 rounded-t-lg"
            >
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    New site
                </h3>
            </div>

            <div class="w-full bg-gray-50 p-4 md:p-6 pb-8">
                <div class="w-full mt-5">
                    <text-input
                        name="domain"
                        label="Root domain"
                        placeholder="www.example.com"
                        help="You can host multiple sites on a server. To add a new site, provide the domain name the site would be hosted on."
                    />
                </div>

                <div class="w-full mt-5">
                    <text-input
                        name="type"
                        component="select"
                        label="Project type"
                        :options="projectTypes"
                        help="If your project is a Single Page application, static html site or related, select Static HTML. Select Nodejs for a standard Nodejs application."
                        placeholder="www.example.com"
                    />
                </div>

                <div class="flex justify-end w-full w-full mt-5">
                    <v-button type="submit" label="Add site" class="w-full md:w-1/5" />
                </div>
            </div>
        </div>
    </sidebar-layout>
</template>

<script>
export default {
    data() {
        return {
            projectTypes: [
                {
                    label: 'Static HTML',
                    value: 'html'
                },
                {
                    label: 'Nodejs',
                    value: 'nodejs'
                }
            ],
            nav: [
                {
                    label: 'Sites',
                    value: 'sites'
                },
                {
                    label: 'Databases',
                    value: 'databases'
                },
                {
                    label: 'Scheduler',
                    value: 'scheduler'
                },
                {
                    label: 'Network',
                    value: 'network'
                }
            ]
        }
    }
}
</script>
