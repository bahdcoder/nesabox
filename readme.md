# Nesabox api

# Ideas

- For now, we'll get rid of all `*.nesabox.com` support domains. Then, we'll figure out a way to setup a `*.nesaboxapp.com` something. Wow lol I feel free.
- This means, all certbot issues are completely gone !!!

# How to setup realtime logs

1 - Setup log watching app on user's server as usual
2 - Setup socket-io client (server side) on log-watcher.nesabox.com on your nesabox server
3 - When user visits page, user subscribes (emits subscribe) to log-watcher.nesabox.com site. 
4 - If confirmed, log-watcher.nesabox.com subscribes to log-watcher on user's server. 
5 - When file changes on user's server, log-watcher receives the file changes, and emits to the client browser.

