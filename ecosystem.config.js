// PM2 Ecosystem Configuration for E-Sign Application
// Dokumentasi: https://pm2.keymetrics.io/docs/usage/application-declaration/

module.exports = {
  apps: [
    {
      // Nama aplikasi
      name: 'esign-app',

      // Script yang akan dijalankan
      script: 'node_modules/next/dist/bin/next',
      args: 'start -p 3999',

      // Working directory
      cwd: './',

      // Instances (jumlah process)
      // 'max' = sesuai jumlah CPU cores
      // atau set angka manual: 1, 2, 4, dst
      instances: 2,

      // Execution mode: 'cluster' atau 'fork'
      exec_mode: 'cluster',

      // Environment variables
      env: {
        NODE_ENV: 'production',
        PORT: 3999
      },

      // Development environment
      env_development: {
        NODE_ENV: 'development',
        PORT: 3999
      },

      // Production environment
      env_production: {
        NODE_ENV: 'production',
        PORT: 3999
      },

      // Logging
      error_file: './logs/esign-error.log',
      out_file: './logs/esign-out.log',
      log_date_format: 'YYYY-MM-DD HH:mm:ss Z',
      merge_logs: true,

      // Auto restart
      autorestart: true,

      // Max memory restart (restart jika memory mencapai limit)
      max_memory_restart: '1G',

      // Watch files for changes (set false untuk production)
      watch: false,

      // Ignore watch (jika watch = true)
      ignore_watch: [
        'node_modules',
        'logs',
        '.git',
        '.next'
      ],

      // Max restarts dalam 1 menit
      max_restarts: 10,

      // Minimum uptime sebelum dianggap sebagai restart yang valid
      min_uptime: '10s',

      // Wait before restart
      kill_timeout: 5000,

      // Listen timeout
      listen_timeout: 10000,

      // Exponential backoff restart delay
      exp_backoff_restart_delay: 100,

      // Instance var untuk cluster mode
      instance_var: 'INSTANCE_ID',

      // Source map support
      source_map_support: true,

      // Automation restart at specific time (cron)
      // cron_restart: '0 0 * * *',  // Restart setiap midnight

      // Disable auto restart dalam interval waktu tertentu
      // restart_delay: 4000,
    }
  ],

  // Deploy configuration (opsional)
  deploy: {
    production: {
      // User untuk SSH
      user: 'esign',

      // Host server
      host: 'your-server.com',

      // SSH key
      key: '~/.ssh/id_rsa',

      // Ref (branch)
      ref: 'origin/main',

      // Git repository
      repo: 'https://github.com/sand07/esign-fullstack.git',

      // Path di server
      path: '/home/esign/esign-fullstack',

      // Pre-deploy commands
      'pre-deploy-local': '',

      // Post-deploy commands
      'post-deploy': 'npm install && npx prisma generate && npx prisma migrate deploy && npm run build && pm2 reload ecosystem.config.js --env production',

      // Pre-setup commands
      'pre-setup': ''
    }
  }
};
