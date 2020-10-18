<p class="mb-6">
    Before provisioning any servers, you should <a href="/account/ssh-keys" target='_blank' class='underline-sha-green-500'>add an ssh key</a> to your account. All ssh keys on your account are added to your server during the provisioning process.
</p>

<p class="mb-6">
    The ssh key is added to your server as the <span class="code">nesa</span> user.
</p>


@include('app.partials.image-link', [
    'name' => 'add-ssh-key-to-account'
])