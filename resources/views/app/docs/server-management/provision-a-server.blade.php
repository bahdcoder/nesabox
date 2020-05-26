Time to provision a server. On your dashboard, click the <span class="italic bold">Add new server</span> button. 

<p class="mt-6 mb-6">
    Here you select the server provider you want to go with, next select an <a href="/account/server-providers" target='_blank' class='underline-sha-green-500'>API key</a> for the server provider (this should have been created on the <a href="/account/server-providers" target="_blank" class='underline-sha-green-500'>Server Providers page</a>)
</p>

@include('app.partials.image-link', [
    'name' => 'add-server-1'
])

<p class="mt-6">
    The next steps involves you providing customized details for the new server. These include;
</p>
<ul class="mb-6">
    <li class="text-sm flex">
        <span class="font-semibold text-sm mr-3" style="width: 20%">Server name :</span><span style="width: 80%"> Here you provide a name for the new server i.e Ecommerce-store.</span>
    </li>
    <li class="text-sm flex w-full">
        <span class="font-semibold mr-3" style="width: 20%">Server type :</span>
        <div class="flex flex-col" style="width: 80%">
            <span>This is where you tell NesaBox how you want your server bootstrapped.</span>
            <span>- Default : this provides everything you need to run a site i.e Nginx, Monitoring, Private networking </span>
            <span>- Load Balancer : this option invloces setting up Nginx on the server and optimizing for load balancing only.</span>
            <span>- Database : this option invloces setting up Nginx on the server and optimizing for load balancing only.</span>
        </div>
    </li>
    <li class="text-sm flex">
        <span class="font-semibold text-sm mr-3" style="width: 20%">Region :</span><span style="width: 80%"> Where do you want your server located,  ideally the data center you choose depends on the location of the users of the application.</span>
    </li>
    <li class="text-sm flex">
        <span class="font-semibold text-sm mr-3" style="width: 20%">Size :</span><span style="width: 80%"> Select the size of RAM, GB and virtual CPUs</span>
    </li>
</ul>

@include('app.partials.image-link', [
    'name' => 'add-server-2'
])