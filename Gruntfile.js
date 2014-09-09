module.exports = function(grunt) {
    'use strict';
    [
        'grunt-contrib-yuidoc',
    ].forEach(function (name) {
        grunt.loadNpmTasks(name);
    });

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        yuidoc: {
            compile: {
                name: '<%= pkg.name %>',
                description: '<%= pkg.description %>',
                version: '<%= pkg.version %>',
                url: '<%= pkg.homepage %>',
                options: {
                    extension: ".php",
                    paths: ['modules/'],
                    outdir: './docs'
                }
            }
        }
    });
}
