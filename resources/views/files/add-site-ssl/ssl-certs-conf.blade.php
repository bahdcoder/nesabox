# --------------------------------------------------------------------------------------------
# | Nesa ssl certificates configuration file for {{ $site->name }} - (Do not remove or modify)      |
# -------------------------------------------------------------------------------------------

ssl_certificate /etc/nginx/ssl/{{ $site->name }}/server.cer;
ssl_certificate_key /etc/nginx/ssl/{{ $site->name }}/server.key;
