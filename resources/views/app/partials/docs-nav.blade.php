<nav>
    @foreach ($menu as $section)
        <div class="mt-8">
            <h3 class="px-3 text-xs leading-4 font-semibold text-gray-800 uppercase tracking-wider" id="projects-headline">
                {{ $section['name'] }}
            </h3>

            <div class="mt-1" role="group" aria-labelledby="projects-headline">
                @foreach($section['items'] as $item)
                    <a href="/docs/{{ $item['path'] }}" class="group flex items-center px-3 py-2 text-sm leading-5 text-gray-700 rounded-md hover:text-gray-900 hover:bg-gray-50 focus:outline-none focus:bg-gray-100 transition ease-in-out duration-150">
                        <span class="truncate {{ $path === $item['path'] ? 'underline-sha-green-500' : '' }}">
                            {{ $item['name'] }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    @endforeach
</nav>