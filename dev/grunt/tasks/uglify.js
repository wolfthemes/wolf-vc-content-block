module.exports = {

	lib: {

		options : {
			//banner : '/*! <%= app.name %> Wordpress Plugin v<%= app.version %> */ \n'
			preserveComments : 'some'
		},

		files: {
			'<%= app.jsPath %>/lib/parallax.min.js': [ '<%= app.jsPath %>/lib/parallax.js'],
			'<%= app.jsPath %>/lib/jquery.fittext.min.js': [ '<%= app.jsPath %>/lib/jquery.fittext.js'],
			'<%= app.jsPath %>/lib/jquery.bigtext.min.js': [ '<%= app.jsPath %>/lib/bigtext.js'],
			'<%= app.jsPath %>/lib/cocoen.min.js': [ '<%= app.jsPath %>/lib/cocoen.js'],
		}
	},

	files: {

		options : {
			//banner : '/*! <%= app.name %> Wordpress Plugin v<%= app.version %> */ \n'
			preserveComments : 'some'
		},

		files: {
			'<%= app.jsPath %>/min/bigtext.min.js': [ '<%= app.jsPath %>/bigtext.js'],
			'<%= app.jsPath %>/min/fittext.min.js': [ '<%= app.jsPath %>/fittext.js'],
			'<%= app.jsPath %>/min/accordion.min.js': [ '<%= app.jsPath %>/accordion.js'],
			'<%= app.jsPath %>/min/autotyping.min.js': [ '<%= app.jsPath %>/autotyping.js'],
			'<%= app.jsPath %>/min/sliders.min.js': [ '<%= app.jsPath %>/sliders.js'],
			'<%= app.jsPath %>/min/advanced-slider.min.js': [ '<%= app.jsPath %>/advanced-slider.js'],
			'<%= app.jsPath %>/min/carousels.min.js': [ '<%= app.jsPath %>/carousels.js'],
			'<%= app.jsPath %>/min/buttons.min.js': [ '<%= app.jsPath %>/buttons.js'],
			'<%= app.jsPath %>/min/icons.min.js': [ '<%= app.jsPath %>/icons.js'],
			'<%= app.jsPath %>/min/mailchimp.min.js': [ '<%= app.jsPath %>/mailchimp.js'],
			'<%= app.jsPath %>/min/cocoen.min.js': [ '<%= app.jsPath %>/cocoen.js'],
			'<%= app.jsPath %>/min/countdown.min.js': [ '<%= app.jsPath %>/countdown.js'],
			'<%= app.jsPath %>/min/counter.min.js': [ '<%= app.jsPath %>/counter.js'],
			'<%= app.jsPath %>/min/tabs.min.js': [ '<%= app.jsPath %>/tabs.js'],
			'<%= app.jsPath %>/min/YT-video-bg.min.js': [ '<%= app.jsPath %>/YT-video-bg.js'],
			//'<%= app.jsPath %>/min/vimeo-video-bg.min.js': [ '<%= app.jsPath %>/vimeo-video-bg.js'],
			'<%= app.jsPath %>/min/youtube.min.js': [ '<%= app.jsPath %>/youtube.js'],
			'<%= app.jsPath %>/min/message.min.js': [ '<%= app.jsPath %>/message.js'],
			'<%= app.jsPath %>/min/functions.min.js': [ '<%= app.jsPath %>/functions.js'],
		}
	},

	concatLib: {
		options : {
			banner : '/*! <%= app.name %> libraries Wordpress Plugin v<%= app.version %> */ \n',
			preserveComments : 'some'
		},

		files: {

			'<%= app.jsPath %>/min/lib.min.js': [
				'<%= app.jsPath %>/lib/bigtext.js',
				'<%= app.jsPath %>/lib/cocoen.js',
				'<%= app.jsPath %>/lib/jquery.countdown.min.js',
				'<%= app.jsPath %>/lib/countUp.min.js',
				'<%= app.jsPath %>/lib/jquery.fittext.js',
				'<%= app.jsPath %>/lib/flickity.pkgd.min.js',
				'<%= app.jsPath %>/lib/typed.min.js',
				'<%= app.jsPath %>/lib/wow.min.js',
				'<%= app.jsPath %>/lib/lity.min.js',
			],
		}
	},

	concat: {

		options : {
			banner : '/*! <%= app.name %> Wordpress Plugin v<%= app.version %> */ \n'
			// preserveComments : 'some'
		},

		files: {

			'<%= app.jsPath %>/min/scripts.min.js': [
				'<%= app.jsPath %>/bigtext.js',
				'<%= app.jsPath %>/fittext.js',
				'<%= app.jsPath %>/accordion.js',
				'<%= app.jsPath %>/autotyping.js',
				'<%= app.jsPath %>/advanced-slider.js',
				'<%= app.jsPath %>/buttons.js',
				'<%= app.jsPath %>/carousels.js',
				'<%= app.jsPath %>/cocoen.js',
				'<%= app.jsPath %>/countdown.js',
				'<%= app.jsPath %>/counter.js', // new
				'<%= app.jsPath %>/icons.js',
				//'<%= app.jsPath %>/mailchimp.js', // need JS global var so need to be enqueued separately
				'<%= app.jsPath %>/sliders.js',
				'<%= app.jsPath %>/tabs.js',
				'<%= app.jsPath %>/youtube.js',
				'<%= app.jsPath %>/YT-video-bg.js',
				'<%= app.jsPath %>/message.js',
				'<%= app.jsPath %>/functions.js'
			],
		}
	},

	build: {

		options : {
			banner : '/*! <%= app.name %> Wordpress Plugin v<%= app.version %> */ \n'
			// preserveComments : 'some'
		},

		files: {

			'<%= app.jsPath %>/min/app.min.js': [
				'<%= app.jsPath %>/lib/jquery.swipebox.min.js',
				//'<%= app.jsPath %>/lib/jquery.haParallax.min.js',
				'<%= app.jsPath %>/lib/wow.min.js',
				'<%= app.jsPath %>/lib/waypoints.min.js',
				'<%= app.jsPath %>/lib/YT-video-bg.js',
				'<%= app.jsPath %>/lib/functions.js'
			],
		}
	}
};