'use strict';
module.exports = function (grunt) {

  // load all grunt tasks matching the `grunt-*` pattern
  require('load-grunt-tasks')(grunt);

  grunt.initConfig({

    // Package info
    pkg: grunt.file.readJSON('package.json'),

    // Clean dist folder
    clean: {
      dist: {
        src: [ 'dist/**' ]
      }
    },

    // Copy files to dist folder
    copy: {
      dist: {
        files: [
          {
            expand: true,
            src: [
              '**',
              '!node_modules/**',
              '!dist/**',
              '!.git/**',
              '!.github/**',
              '!tests/**',
              '!bin/**',
              '!docs/**',
              '!.gitignore',
              '!.gitattributes',
              '!.editorconfig',
              '!.distignore',
              '!gruntfile.js',
              '!Gruntfile.js',
              '!package.json',
              '!package-lock.json',
              '!composer.json',
              '!composer.lock',
              '!phpcs.xml',
              '!phpunit.xml',
              '!README.md',
              '!claude.md',
              '!CLAUDE.md',
              '!DEEP-AUDIT.md',
              '!BUGFIX-LOG.md',
              '!CHANGELOG.md',
              '!market-audit.md',
              '!competitive-analysis.md',
              '!.DS_Store'
            ],
            dest: 'dist/<%= pkg.name %>/'
          }
        ]
      }
    },

    // Compress to zip
    compress: {
      dist: {
        options: {
          archive: 'dist/<%= pkg.name %>-<%= pkg.version %>.zip',
          mode: 'zip'
        },
        files: [
          {
            expand: true,
            cwd: 'dist/',
            src: [ '<%= pkg.name %>/**' ],
            dest: '/'
          }
        ]
      }
    },

    // Check text domain
    checktextdomain: {
      options: {
        text_domain: ['email-customizer-for-woocommerce'],
        keywords: [
          '__:1,2d',
          '_e:1,2d',
          '_x:1,2c,3d',
          'esc_html__:1,2d',
          'esc_html_e:1,2d',
          'esc_html_x:1,2c,3d',
          'esc_attr__:1,2d',
          'esc_attr_e:1,2d',
          'esc_attr_x:1,2c,3d',
          '_ex:1,2c,3d',
          '_n:1,2,4d',
          '_nx:1,2,4c,5d',
          '_n_noop:1,2,3d',
          '_nx_noop:1,2,3c,4d'
        ]
      },
      target: {
        files: [{
          src: [
            '*.php',
            '**/*.php',
            '!node_modules/**',
            '!vendor/**',
            '!tests/**'
          ],
          expand: true
        }]
      }
    },

    // Task for CSS minification
    cssmin: {
      public: {
        files: [{
          expand: true,
          cwd: 'public/css/',
          src: ['*.css', '!*.min.css', '!vendor/*.css'],
          dest: 'public/css/min',
          ext: '.min.css',
        }],
      },
      admin: {
        files: [{
          expand: true,
          cwd: 'admin/css/',
          src: ['*.css', '!*.min.css', '!vendor/*.css'],
          dest: 'admin/css/',
          ext: '.min.css',
        }],
      },
      wbcom: {
        files: [{
          expand: true,
          cwd: 'admin/wbcom/assets/css/',
          src: ['*.css', '!*.min.css', '!vendor/*.css'],
          dest: 'admin/wbcom/assets/css/min/',
          ext: '.min.css',
        }],
      },
    },

    // Task for JavaScript minification
    uglify: {
      public: {
        options: {
          mangle: false,
        },
        files: [{
          expand: true,
          cwd: 'public/js/',
          src: ['*.js', '!*.min.js', '!vendor/*.js'],
          dest: 'public/js/min/',
          ext: '.min.js',
        }],
      },
      admin: {
        options: {
          mangle: false,
        },
        files: [{
          expand: true,
          cwd: 'admin/js/',
          src: ['*.js', '!*.min.js', '!vendor/*.js'],
          dest: 'admin/js/min/',
          ext: '.min.js',
        }],
      },
      wbcom: {
        options: {
          mangle: false,
        },
        files: [{
          expand: true,
          cwd: 'admin/wbcom/assets/js',
          src: ['*.js', '!*.min.js', '!vendor/*.js'],
          dest: 'admin/wbcom/assets/js/min/',
          ext: '.min.js',
        }],
      },
    },

    // Task for watching file changes
    watch: {
      css: {
        files: ['public/css/*.css'],
        tasks: ['cssmin:public'],
      },
      adminCss: {
        files: ['admin/css/*.css'],
        tasks: ['cssmin:admin'],
      },
      js: {
        files: ['public/js/*.js'],
        tasks: ['uglify:public'],
      },
      adminJs: {
        files: ['admin/js/*.js'],
        tasks: ['uglify:admin'],
      },
      php: {
        files: ['**/*.php'],
        tasks: ['checktextdomain'],
      },
    },

    // Task for generating RTL CSS
    rtlcss: {
      myTask: {
        options: {
          map: { inline: false },
          opts: {
            clean: false
          },
          plugins: [],
          saveUnmodified: true,
        },
        files: [
          {
            expand: true,
            cwd: 'public/css/',
            src: ['**/*.min.css', '!vendor/**/*.css'],
            dest: 'public/css/rtl/',
            ext: '.rtl.css',
            flatten: true
          },
          {
            expand: true,
            cwd: 'admin/css/',
            src: ['**/*.min.css', '!vendor/**/*.css'],
            dest: 'admin/css/',
            ext: '.rtl.css',
            flatten: true
          },
          {
            expand: true,
            cwd: 'admin/wbcom/assets/css/',
            src: ['**/*.min.css', '!vendor/**/*.css'],
            dest: 'admin/wbcom/assets/css/rtl/',
            ext: '.rtl.css',
            flatten: true
          }
        ]
      }
    },

    shell: {
      wpcli: {
        command: 'wp i18n make-pot . languages/email-customizer-for-woocommerce.pot',
      }
    }
  });

  // Register tasks
  grunt.registerTask('default', ['cssmin', 'uglify', 'checktextdomain', 'rtlcss', 'shell']);
  grunt.registerTask('minify', ['cssmin', 'uglify', 'rtlcss']);
  grunt.registerTask('i18n', ['checktextdomain', 'shell']);
  grunt.registerTask('build', ['clean:dist', 'copy:dist', 'compress:dist']);
  grunt.registerTask('zip', ['clean:dist', 'copy:dist', 'compress:dist']);
};
