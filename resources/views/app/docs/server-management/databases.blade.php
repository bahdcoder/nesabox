<p class="mb-6">
    In order to create a database on for your server, ensure you selected a database of your choice when you added a server, this would enable Nesabox install the specific database on your server.
    If you did not do that, you cannot use a database with the server you just created.
</p>

<p class="mb-6">
    Click on the server you just created, you would be directed to a page where you can view the details for your new server.
    On the side menu, a list of database/databases installed on the server.
</p>

@include('app.partials.image-link', [
    'name' => 'add-database'
])

<p class="mt-6">
    You can select the database you want to create, here you can provide a <span class="font-semibold">name</span> for the database. You can also add <span class="font-semibold">users</span> for the database, these are the users that can have either Readonly or Read and Write access to your database.
</p>