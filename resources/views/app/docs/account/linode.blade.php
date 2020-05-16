<p class="mb-6">
    To connect this provider, visit the <a class="underline-sha-green-500" target='_blank' href="https://cloud.linode.com/profile/tokens">api tokens page</a> on your linode dashboard and click the <span class="code">Add Personal Access Token</span> button.
</p>

@include('app.partials.image-link', [
    'name' => 'add-linode-api-token'
])

<p class="my-6">
Copy the generated api token. Now visit the <a href="/account/server-providers">server providers page</a> on your nesabox dashboard and add a new linode provider. 
</p>

@include('app.partials.image-link', [
    'name' => 'add-linode-api-token-to-nesabox'
])

