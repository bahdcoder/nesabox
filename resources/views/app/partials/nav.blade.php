<div class="{{ $docs ? 'container' : 'max-w-screen-lg' }} mx-auto flex justify-between items-center pt-4 md:pt-6 {{ $docs ? 'pb-4 md:pb-6' : '' }} px-3">
    @include('app.partials.logo')
    <div class="text-white font-medium">
        <a href="/docs" class="text-white hover:text-sha-green-500 mr-5">
            Docs
        </a>

        <a href="{{ auth()->user() ? '/dashboard' : '/auth/login' }}" class="text-white hover:text-sha-green-500">
            {{ auth()->user() ? 'Dashboard ' : 'Log in here ' }}→
        </a>
    </div>
</div>
