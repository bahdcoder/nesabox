<p class="mb-6">
    Nesabox provides an easy way to add and manage SSH keys for a server.
</p>
<p class="mb-6">
    To add SSH keys for a server, select the particular server on your <a href="/dashboard" target='_blank' class='underline-sha-green-500'>Dashboard</a>. Next, on the side menu select <span>SSH Keys</span>.
</p>

<div>
    <p class="mb-3 mt-6">Nesabox needs just two details to add the SSH keys;</p>
    <div class="flex flex-col mb-6">
        <div class="text-sm"><span class="font-bold">Name :</span> A suitable name for the SSH key.</div>
        <div class="text-sm"><span class="font-bold">Public key :</span> A public key for the remote server</div>
    </div>
</div>

@include('app.partials.image-link', [
    'name' => 'add-ssh-key-to-account'
])
