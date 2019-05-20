module.exports = function (grunt) {
	'use strict';

	/**
	* LOAD TASKS
	*/
	require('matchdep').filterDev('grunt-*').forEach(grunt.loadNpmTasks);
	
	/**
	* CONFIGURATION
	*/
	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		clean: {
			options: {
				force: true
			},
			deploy: [
				'deploy'
			]
		},
		copy: {
			deploy: {
				files: [{
					expand: true,
					src: [
						'**',
						'!**/assets/**',
						'!**/bin/**',
						'!**/deploy/**',
						'!**/sass/**',
						'!**/node_modules/**',
						'!**/tests/**',
						'!config.rb',
						'!Gruntfile.js',
						'!package.json',
						'!phpunit.xml',
						'!README.md'
					],
					dest: 'deploy/'
				}],
			}
		},
		wp_readme_to_markdown: {
			your_target: {
				files: {
					'README.md': 'readme.txt'
				},
				options: {
					screenshot_url: 'https://ps.w.org/taxonomy-filter/trunk/{screenshot}.jpg',
				}
			}
		},
		wp_deploy: {
			deploy: {
				options: {
					plugin_slug: 'taxonomy-filter',
                    svn_user: 'lando1982',
                    build_dir: 'deploy',
					assets_dir: 'assets'
				},
			}
		}
	});
	
	/**
	* BUILD GIT README FILE AND MAYBE SOME SASS LATER?
	*/
	grunt.registerTask('default', [
		'wp_readme_to_markdown' 
	]);
	
	/**
	* DEPLOY TO WORDPRESS
	*/
	grunt.registerTask('deploy', [
		'copy:deploy',
		'wp_deploy:deploy',
		'clean:deploy'
	]);

};
