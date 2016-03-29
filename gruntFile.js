module.exports = function (grunt) {

    // Project configuration.
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        connect: {
            server: {
                options: {
                    livereload: true
                }
            }
        },
        watch: {
            scripts: {
                files: ['**/*.ts'],
                tasks: ['exec:tsc']
            },
            models: {
                files: ['PHP/Application/Model/*.php'],
                tasks: ['exec:migrate', 'exec:generateService']
            },
            services: {
                files: ['PHP/Application/Service/*.php'],
                tasks: ['exec:generateService']
            },
            css: {
                files: ['**/*.css', '**/*.html', '**/*.js'],
                options: {
                    livereload: {
                        host: 'localhost',
                        port: 9000
                    }
                }
            }
        },
        exec: {
            migrate: {
                cmd: 'php PHP/Scripts/Migrate.php'
            },
            generateService: {
                cmd: 'php PHP/Scripts/GenerateService.php'
            },
            tsc: {
                cmd: 'tsc'
            },
            server: {
                cmd: 'php -S 0.0.0.0:8080 routing.php &'
            }
        }
    });

    grunt.loadNpmTasks('grunt-exec');
    grunt.loadNpmTasks('grunt-contrib-watch');

    // Default task(s).
    grunt.registerTask('default', ['watch']);
    grunt.registerTask('start', ['exec:server', 'watch'])
    grunt.registerTask('migrate', ['exec:migrate']);

};