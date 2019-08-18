module.exports = {
    apps: [
        {
            name: 'blog.site.com',
            script: 'index.js',
            instances: 1,
            autorestart: true,
            exec_mode: 'cluster',
            log_date_format: 'YYYY-MM-DD HH:mm',
            cwd: '/home/nesa/blog.site.com',
            interpreter: '/usr/bin/node/10.13.0/node',
            env: {
                NODE_ENV: 'production'
            },
            error_file: '/home/nesa/.pm2/logs/blog.site.com',
            out_file: '/home/nesa/.pm2/logs/blog.site.com'
        }
    ]
}
