module.exports = {

	build: {
		expand: true,
		cwd: '<%= app.cssPath %>',
		src: [
			'*.css',
			'!*.min.css',
			'admin/*.css',
			'!admin/*.min.css',
			'!admin/lib/*.css',
		],
		dest: '<%= app.cssPath %>',
		ext: '.min.css'
	},

	icons: {
		files: {
			'<%= app.cssPath %>/lib/linea-icons/linea-icons.min.css': ['<%= app.cssPath %>/lib/linea-icons/linea-icons.css'],
			'<%= app.cssPath %>/lib/linearicons/linearicons.min.css': ['<%= app.cssPath %>/lib/linearicons/linearicons.css'],
			'<%= app.cssPath %>/lib/socicon/socicon.min.css': ['<%= app.cssPath %>/lib/socicon/socicon.css'],
			'<%= app.cssPath %>/lib/wolficons/wolficons.min.css': ['<%= app.cssPath %>/lib/wolficons/wolficons.css']
		}
	}
};