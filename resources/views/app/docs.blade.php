<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <meta http-equiv="X-UA-Compatible" content="ie=edge" />
        <title>Nesabox docs - {{ $current['name'] }}</title>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}?v=2" />

        <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&display=swap" rel="stylesheet" />

        <meta name="twitter:card" content="photo" />
        <meta name="twitter:site" content="@bahdcoder" />
        <meta name="twitter:creator" content="@bahdcoder" />
        <meta name="twitter:title" content="Nesabox" />
        <meta name="twitter:description" content="Nesabox is a server management tool for provisioning, deploying and scaling node.js applications on DigitalOcean, Linode, Vultr, Amazon and more." />

        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <meta name="description" content="Nesabox is a server management tool for provisioning, deploying and scaling node.js applications on DigitalOcean, Linode, Vultr, Amazon and more.">

        <meta property="og:site_name" content="Nesabox"/>
        <meta property="og:title" content="Nesabox"/>
        <meta property="og:description" content="Nesabox is a server management tool for provisioning, deploying and scaling node.js applications on DigitalOcean, Linode, Vultr, Amazon and more."/>
        <meta property="og:url" content="https://nesabox.com/"/>
        <meta property="og:type" content="website"/>
        <meta name="twitter:url" content="https://nesabox.com" />

        <meta name="twitter:image:alt" content="Nesabox">
        <meta name="twitter:card" content="summary_large_image">

        <style>
            .hero-bg {
                background-image: url("@include('app.partials.hero-bg')");
            }

            .bg-sky-blue {
                background: #f3f8ff;
            }
        </style>

        <script>
            function toggleMenu() {
                var toggleButton = document.getElementById('toggle-menu-button')
                var menu = document.getElementById('menu')

                if (menu.classList.contains('hidden')) {
                    toggleButton.innerText = 'Hide menu'
                } else {
                    toggleButton.innerText = 'Show menu'
                }

                menu.classList.toggle('hidden')
            }
        </script>
    </head>

    <body class="font-sans bg-white">
        @include('app.partials.banner')

        <div class="relative antialiased overflow-hidden bg-gray-800 hero-bg">
            @include('app.partials.nav', [
                'docs' => true
            ])
        </div>

        <div class="container mx-auto my-8 md:my-12 px-6 md:px-0">
            <div class="w-full flex flex-wrap">
                <button
                    onclick='toggleMenu()'
                    id='toggle-menu-button'
                    class="md:hidden mb-3 md:mb-0 w-full md:w-auto capitalize inline-flex justify-center py-3 px-5 border text-gray-700 border-transparent text-sm leading-5 font-medium rounded-sm text-white bg-sky-blue focus:outline-none transition duration-150 ease-in-out"
                >
                    Show menu
                </button>
                <div id='menu' class="w-full md:w-1/4 hidden md:block bg-sky-blue py-4 px-5">
                    @include('app.partials.docs-nav', [
                        'path' => $current['path']
                    ])
                </div>

                <div class="w-full md:w-3/4 md:pl-20">
                    <div class="max-w-3xl text-lg text-gray-700 leading-8">
                        <h3 class="mt-6 md:mt-0 mb-3 text-left font-serif text-2xl md:text-4xl font-semibold mb-6 sm:mb-8">
                            {{ $current['name'] }}
                        </h3>
                        @include('app.docs.' . $page)

                        @if($current['next'])
                            <div class="mt-6 flex justify-end">
                                <a href="/docs/{{ $current['next'] }}" class='underline-sha-green-500'>Next →</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        @include('app.partials.footer')
    </body>
</html>
