module.exports = {
  apps: [
    {
      name: 'EmeyzWeb',
      exec_mode: 'cluster',
      instances: 'max', // Or a number of instances
      script: './node_modules/nuxt/bin/nuxt.js',
      args: 'start',
	error_file: '/var/www/Emeyz23/error.log'
    }
  ]
}
