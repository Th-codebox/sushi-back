module.exports = {
    apps: [
        {
            name: 'laravel-echo-server',
            //interpreter: 'php',
            script: 'laravel-echo-server',
            args: 'start',
            //instances: 1,
            autorestart: true,
            watch: false,
            max_memory_restart: '1G',
            //uid: '1000'
        },
        {
            name: 'laravel-horizon-queue',
            interpreter: 'php',
            script: 'artisan',
            args: 'horizon',
            instances: 1,
            autorestart: true,
            watch: false,
            max_memory_restart: '1G'
        }
    ]
};
