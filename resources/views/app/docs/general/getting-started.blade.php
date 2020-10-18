<p class="mb-6">
    Nesabox is a modern server management and site deployment service designed specifically for node.js applications.
</p>


<p class="mb-6">
Nesabox is not a server provider. We do not provide you with the servers needed to manage your applications. You bring your server, and we manage your server for you.
We support a number of server providers such as Digital Ocean, Linode, Vultr and AWS. This is just to make the server provisioning process easier.
</p>
<p class="mb-6">
If your provider is not supported, you can handle the server provisioning process on your provider website, and manually connect this fresh server to nesabox. 
</p>

<p class="mb-6">
    When you provision a new server with nesabox, Nesabox'll install and configure the following packages:
</p>

<ul class='mb-6'>
    @foreach(collect(['Nginx', 'Node.js Lts', 'n (node.js version manager)', 'Pm2 process manager', 'MongoDB / MySQL (5.7 & 8) / MariaDB / PostgresQL (if selected)', 'UFW Firewall', 'Redis', 'Automatic Security Updates']) as $item)
        <li class="mb-2 flex items-center">
            <svg class='w-5 h-5 mr-1' fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M5 13l4 4L19 7"></path></svg>
            {{ $item }}
        </li>
    @endforeach
</ul>

<p class="mb-6">
    In addition, nesabox can help you manage scheduled jobs, database backups, daemon processes, SSL certificates, application logs and more. After a server is provisioned, you can then deploy unlimited static, SPAs or node.js web applications using the Nesabox dashboard.
</p>

<p class="mb-6">
</p>