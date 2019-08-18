# Nesabox api

# Ideas

- For now, we'll get rid of all `*.nesabox.com` support domains. Then, we'll figure out a way to setup a `*.nesaboxapp.com` something. Wow lol I feel free.
- This means, all certbot issues are completely gone !!!

# How to handle nginx configuration
- Install nginx
- Clone the h5bp nginx configs
        - `git clone https://github.com/h5bp/server-configs-nginx.git h5bp-repository`
        - `mv /etc/nginx/h5bp-repository/h5bp /etc/nginx/h5bp`
        - `rm -r /etc/nginx/h5bp-repository`
- Replace the default nginx configuration with the one from h5bp 
        - `rm /etc/nginx/nginx.conf`
        - `cp /etc/nginx/h5bp/nginx.conf /etc/nginx/nginx.conf`
- Add a folder called `nesa-conf`
        - `mkdir /etc/nginx/nesa-conf`
- To add a site, add a folder in the `nesa-conf` folder for that site
        - `mkdir /etc/nginx/nesa-conf/ssl-http2.nesabox.com`
        - Add a default configuration for that site.
        - `cat > /etc/nginx/nesa-conf/ssl-http2.nesabox.com/base.conf << EOF EOF`

# How to setup realtime logs

1 - Setup log watching app on user's server as usual
2 - Setup socket-io client (server side) on log-watcher.nesabox.com on your nesabox server
3 - When user visits page, user subscribes (emits subscribe) to log-watcher.nesabox.com site. 
4 - If confirmed, log-watcher.nesabox.com subscribes to log-watcher on user's server. 
5 - When file changes on user's server, log-watcher receives the file changes, and emits to the client browser.

# How to securely get metrics from server

1 - We'll have an endpoint on nesabox, this endpoint connects to the server, a node js script on the server makes a local api request to fetch netdata data, then returns it to nesabox which inturn returns it to the browser.
