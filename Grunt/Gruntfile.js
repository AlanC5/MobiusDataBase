module.exports = function(grunt) {
  grunt.registerTask('speak', function() {
    console.log("I'm speaking");
  });

  grunt.registerTask('yell', function() {
    console.log("I'M YELLING!!!");
  });

  grunt.registerTask('default', ['speak', 'yell']);
};
