# --------------------------------------------------------------------------------
# | Nesa base configuration file for {{ $site->name }} - (Do not remove or modify)      |
# -------------------------------------------------------------------------------

server {
    listen 80;
    listen [::]:80;

    server_name .{{ $site->name }};
    return 301 https://\$host\$request_uri;
}

server {
  listen [::]:443 ssl http2;
  listen 443 ssl http2;

  server_name www.{{ $site->name }};

  include h5bp/ssl/ssl_engine.conf;
  include h5bp/ssl/policy_modern.conf;
  include nesa-conf/{{ $site->name }}/ssl_certificates.conf;

  return 301 https://{{ $site->name }}\$request_uri;
}
