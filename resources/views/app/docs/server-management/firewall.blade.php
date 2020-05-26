<p class="mb-6">
    To add a <span class="font-bold">Firewall</span>, select a server you want to add some Firewall rules to. On the server details page, select <span class="font-bold">Network</span>.
</p>

@include('app.partials.image-link', [
    'name' => 'add-firewall'
])

<div>
    <p class="mb-3 mt-6">Scroll down to the Firewall section, here you provide these details;</p>
    <div class="flex flex-col mb-6">
        <div class="text-sm"><span class="font-bold">Name :</span> A suitable name for the Firewall rule.</div>
        <div class="text-sm"><span class="font-bold">Port :</span> This is the port of the service you want to restrict access to.</div>
        <div class="text-sm"><span class="font-bold">From IP Address :</span> You restrict access by adding IP address/adressess. If you do not specify any, then any IP address can have access to the port.</div>
    </div>
</div>

@include('app.partials.image-link', [
    'name' => 'add-firewall-2'
])
