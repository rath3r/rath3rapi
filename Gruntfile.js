module.exports = function(grunt) {

    grunt.initConfig({
        settings: {
            assets: 'assets',
            dist: 'public',
            templates: 'templates',
            temp: '.tmp',
            bower: 'assets/bower_components'
        },
        banner: '/*!\n' +
        '* <%= pkg.name %> - v<%= pkg.version %> - <%= grunt.template.today("yyyy-mm-dd") %>\n' +
        '*/\n',
        clean: {
            build: [
                "<%= settings.dist %>/styles",
                "<%= settings.dist %>/scripts",
                "<%= settings.dist %>/fonts",
            ]
        },
        less: {
            build: {
                options: {
                    paths: [
                        "<%= settings.assets %>/less"
                    ]
                },
                files: {
                    "<%= settings.dist %>/styles/main.css": "<%= settings.assets %>/less/main.less"
                }
            },
        },
        copy: {
            ui: {
                expand: true,
                cwd: '<%= settings.bower %>/jquery-ui/themes/smoothness/images/',
                src: '**',
                dest: '<%= settings.dist %>/styles/images/',
                flatten: true,
                filter: 'isFile',
            },
            fonts: {
                expand: true,
                cwd: '<%= settings.bower %>/bootstrap/',
                src: 'fonts/**',
                dest: '<%= settings.dist %>',
                flatten: false,
                filter: 'isFile',
            },
        },
        concat: {
            options: {
                separator: ';',
                stripBanners: 'true'
            },
            libs: {
                src: [
                    '<%= settings.bower %>/jquery/dist/jquery.js',
                    '<%= settings.bower %>/jquery-ui/jquery-ui.js',
                    '<%= settings.bower %>/bootstrap/dist/js/bootstrap.js',
                    '<%= settings.assets %>/vendor/mixitup/src/jquery.mixitup.js',
                ],
                dest: '<%= settings.dist %>/scripts/libs.js',
            },
            main: {
                src: [
                    '<%= settings.assets %>/js/main.js',
                ],
                dest: '<%= settings.dist %>/scripts/main.js',
            },
        },
        imagemin: {
            svg: {
                options: {
                    optimizationLevel: 3,
                    svgoPlugins: [{ removeViewBox: false }],
                },
                files: {
                    '<%= settings.dist %>/images/icons/offline.svg': '<%= settings.assets %>/images/icons/offline.svg',
                    '<%= settings.dist %>/images/icons/no-image.svg': '<%= settings.assets %>/images/icons/no-image.svg',
                }
            },
        },
        watch: {
            less: {
                files: ['<%= settings.assets %>/less/**/*.less'],
                tasks: ['less'],
            },
            concat: {
                files: ['<%= settings.assets %>/js/main.js'],
                tasks: ['concat:main']
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-imagemin');

    grunt.registerTask('default', [
        'clean',
        'less',
        'copy',
        'concat',
        'imagemin'
    ]);


};