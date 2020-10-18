<p class="mb-6">
    To connect this provider, visit the <a class="underline-sha-green-500" target='_blank' href="https://cloud.digitalocean.com/account/api/tokens">api tokens page</a> on your digital ocean dashboard and click the <span class="code">Generate new token</span> button.
</p>

@include('app.partials.image-link', [
    'name' => 'add-digital-ocean-api-token'
])

<p class="my-6">
Copy the generated api token. Now visit the <a href="/account/server-providers">server providers page</a> on your nesabox dashboard and add a new digital ocean provider. 
</p>

@include('app.partials.image-link', [
    'name' => 'add-digital-ocean-api-token-to-nesabox'
])

