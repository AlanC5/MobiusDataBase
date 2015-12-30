// Concat and minifies JS/CSS, occurs automatically whenever developer saves and the first time grunt is run
// Be sure to run "grunt" in terminal first

module.exports = function(grunt) {
	grunt.initConfig({
		
		concat: {
			js: {
				src: ['js/1.js', 'js/2.js'],
				dest: 'build/js/scripts.js',
			},
			css: {
				src: ['css/main.css', 'css/theme.css'],
				dest: 'build/css/styles.css',
			},
		},

		uglify: {
			js: {
				files: {
					'build/js/scripts.min.js' : ['build/js/scripts.js']
				}
			}
		},

		cssmin: {
			options: {
				shorthandCompacting: false,
				roundingPrecision: -1
			},
			css: {
				files: {
					'build/css/styles.min.css': ['build/css/styles.css']
				}
			}
		},

		watch: {
			js: {
				files: ['js/**/*.js'],
				tasks: ['concat:js', 'uglify:js'],
			},
			css: {
				files: ['css/**/*.css'],
				tasks: ['concat:css', 'cssmin:css'],
			}
		},
	});
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-cssmin');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.registerTask('default', ['concat', 'uglify', 'cssmin', 'watch']);
};