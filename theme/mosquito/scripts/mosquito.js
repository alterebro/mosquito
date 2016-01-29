function init_share() {

	var metadata = {
		title : document.querySelector('meta[property="og:title"]').getAttribute('content'),
		description : document.querySelector('meta[property="og:description"]').getAttribute('content'),
		url : document.querySelector('meta[property="og:url"]').getAttribute('content'),
		image : document.querySelector('meta[property="og:image"]').getAttribute('content')
	};

	var socialnetwork_baseurl = {
		facebook: "http://www.facebook.com/sharer.php?u={url}&t={title}",
		twitter: "http://twitter.com/share?text={title}&url={url}",
		googleplus: "https://plusone.google.com/_/+1/confirm?hl=en&url={url}",
		linkedin: "http://www.linkedin.com/shareArticle?mini=true&url={url}&title={title}&summary={content}",
		tumblr: "http://www.tumblr.com/share?v=3&u={url}",
		pinterest: "http://pinterest.com/pin/create/button/?url={url}&media={image}&description={content}",
		email: "mailto:?subject={title}&body={content}{url}"
	};

	for ( var i in socialnetwork_baseurl ) {
		socialnetwork_baseurl[i] = socialnetwork_baseurl[i].replace(/\{url\}/, encodeURIComponent(metadata.url))
			.replace(/\{title\}/, encodeURIComponent(metadata.title))
			.replace(/\{content\}/, encodeURIComponent(metadata.description))
			.replace(/\{image\}/, encodeURIComponent(metadata.image));
 	}

	var social_links = document.querySelectorAll('.social-share li[class]');
	for (var i = 0; i < social_links.length; i++) {
		var _network = social_links[i].className;
		var _link = social_links[i].querySelector('a');
			_link.setAttribute('href', socialnetwork_baseurl[_network]);
			_link.setAttribute('data-share', _network+'-share');
			_link.onclick = function(e) {
				e.preventDefault();
				var network_window_name = this.getAttribute('data-share');
				if ( network_window_name.indexOf('email') > -1 ) {
					window.location.href = this.href;
				} else {
					var network_window = window.open( this.href, network_window_name, 'height=350,width=600');
						network_window.focus();
				}
			}
	}
}

init_share();
hljs.initHighlightingOnLoad();
