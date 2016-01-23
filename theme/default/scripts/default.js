function init_share() {
	var metadata = {
		title : document.querySelector('meta[property="og:title"]').getAttribute('content'),
		description : document.querySelector('meta[property="og:description"]').getAttribute('content'),
		url : document.querySelector('meta[property="og:url"]').getAttribute('content'),
		image : document.querySelector('meta[property="og:image"]').getAttribute('content')
	};

	var social_links = document.querySelectorAll('#social-networks li a');
	for (var i = 0; i < social_links.length; i++) {
		var _href = social_links[i].href;
			_href = _href.replace(/\{url\}/, encodeURIComponent(metadata.url))
		       .replace(/\{title\}/, encodeURIComponent(metadata.title))
		       .replace(/\{content\}/, encodeURIComponent(metadata.description))
		       .replace(/\{image\}/, encodeURIComponent(metadata.image));

		social_links[i].setAttribute('href', _href);
		social_links[i].onclick = function(e) {
			e.preventDefault();
			var network_window_name = this.className.replace(/\s+/g, '') + '-share';
			if ( network_window_name.indexOf('email') > -1 ) {
				window.location.href = this.href;
			} else {
				var network_window = window.open( this.href, network_window_name, 'height=350,width=600');
					network_window.focus();
			}
		}
	}
}

/* http://stackoverflow.com/questions/14389566/stop-css3-transition-from-firing-on-page-load */

/*
(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
})(window,document,'script','//www.google-analytics.com/analytics.js','ga');

ga('create', 'UA-63335-22', 'auto');
ga('send', 'pageview');
*/
