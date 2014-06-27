$(function () {
	var pathnames = window.location.pathname.split('/');
	var filename = pathnames[pathnames.length -1];

	$('.nav-sidebar')
			.find('li').removeClass('active')
			.end()
			.find('a[href="' +filename + '"]').parent().addClass('active')

});