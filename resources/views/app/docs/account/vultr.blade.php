<p class="mb-6">
    To connect this provider, visit the <a class="underline-sha-green-500" target='_blank' href="https://my.vultr.com/settings/#settingsapi">api page</a> on your vultr dashboard and copy the <span class="code">Personal Access Token</span>. Make sure the API is enabled.
</p>

@include('app.partials.image-link', [
    'name' => 'add-vultr-api-token'
])

<p class="my-6">
Now visit the <a href="/account/server-providers">server providers page</a> on your nesabox dashboard and add a new vultr provider. 
</p>

@include('app.partials.image-link', [
    'name' => 'add-vultr-api-token-to-nesabox'
])

