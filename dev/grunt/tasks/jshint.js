module.exports = {

	options : {
		jshintrc : '.jshintrc',
		reporterOutput: "",
	},

	all: [
		'<%= app.jsPath %>/*.js',
		'<%= app.jsPath %>/admin/*.js',
		'!<%= app.jsPath %>/countdown.js',
		'!<%= app.jsPath %>/lib/**',
		'!<%= app.jsPath %>/admin/min/**'
	]
};