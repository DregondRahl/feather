<!DOCTYPE html>

<html>

	<head>
		<title>{{ $title }} &ndash; {{ $app['title'] }}</title>

		{{ HTML::style('/assets/theme.css') }}
	</head>

	<body>

		<div class="container">

			<h1 class="header">{{ HTML::link('/', $app['title']) }}</h1>

			<div class="body">

				<div class="left">

					@if(Service\Security::activated())
						<div class="new-discussion">
							{{ HTML::link('', 'New Discussion', array('class' => 'btn orange large')) }}
						</div>
					@endif

					<div class="user">
						@if(Service\Security::online())
							@include('partial.menu.user')
						@else
							@include('partial.menu.guest')
						@endif
					</div>

				</div>

				<div class="right">

					<div class="content">
						
						<ul class="discussions">

							<li class="heading">
								<h2>Release Announcements</h2>

								<div class="child-category">

								</div>
							</li>

							<li class="discussion group">
								<div class="channel">
									<a href="">Release Announcements</a>
								</div>

								<div class="meta">
									<span class="label label-inverse">Sticky</span>
									<span class="label label-inverse">Closed</span>
									
									<a href="" class="title">No help zone</a>
									<p>
										Started by <a href="">Jason</a>, <span title="24th June, 2012 at 6:45am">24th June</span>
									</p>

									<p>
										Last reply by <a href="">Dayle</a>, on <span title="24th June, 2012 at 6:45am">24th June</span>
									</p>
								</div>

								<div class="stats">
									<div class="views">
										<span class="title">Views</span>
										<span class="number">2k</span>
									</div>
									<div class="replies">
										<span class="title">Replies</span>
										<span class="number">0</span>
									</div>
								</div>
							</li>

							<li class="discussion group">
								<div class="channel">
									<a href="">Release Announcements</a>
								</div>

								<div class="meta">
									<span class="label label-inverse">Sticky</span>
									<span class="label label-inverse">Closed</span>
									
									<a href="" class="title">No help zone</a>
									<p>
										Started by <a href="">Jason</a>, <span title="24th June, 2012 at 6:45am">24th June</span>
									</p>

									<p>
										Last reply by <a href="">Dayle</a>, on <span title="24th June, 2012 at 6:45am">24th June</span>
									</p>
								</div>

								<div class="stats">
									<div class="views">
										<span class="title">Views</span>
										<span class="number">2k</span>
									</div>
									<div class="replies">
										<span class="title">Replies</span>
										<span class="number">0</span>
									</div>
								</div>
							</li>

							<li class="discussion group">
								<div class="channel">
									<a href="">Release Announcements</a>
								</div>

								<div class="meta">
									<span class="label label-inverse">Sticky</span>
									<span class="label label-inverse">Closed</span>
									
									<a href="" class="title">No help zone</a>
									<p>
										Started by <a href="">Jason</a>, <span title="24th June, 2012 at 6:45am">24th June</span>
									</p>

									<p>
										Last reply by <a href="">Dayle</a>, on <span title="24th June, 2012 at 6:45am">24th June</span>
									</p>
								</div>

								<div class="stats">
									<div class="views">
										<span class="title">Views</span>
										<span class="number">2k</span>
									</div>
									<div class="replies">
										<span class="title">Replies</span>
										<span class="number">0</span>
									</div>
								</div>
							</li>

							<li class="more">
								See all Release Announcements
							</li>

							<li class="heading">
								<h2>General Discussions</h2>
							</li>

							<li class="discussion group">
								<div class="channel">
									<a href="">General Discussions</a>
								</div>

								<div class="meta">
									<a href="" class="title">How have you found Laravel?</a>
									
									<p>
										Started by <a href="">Taylor</a>, <span title="24th June, 2012 at 6:45am">24th June</span>
									</p>

									<p>
										Last reply by <a href="">Phill</a>, on <span title="24th June, 2012 at 6:45am">24th June</span>
									</p>
								</div>

								<div class="stats">
									<div class="views">
										<span class="title">Views</span>
										<span class="number">1.6k</span>
									</div>
									<div class="replies">
										<span class="title">Replies</span>
										<span class="number">460</span>
									</div>
								</div>
							</li>

						</ul>

					</div>

				</div>
			</div>

			<div class="footer">

				<p class="links">
					Powered by {{ HTML::link('http://voicify.org', 'Voicify', array('class' => 'tooltip-ui-footer', 'title' => 'Version [development]')) }}
				</p>

				<ul class="info">
					<li>49,421 discussions</li>
					<li>120,184 replies</li>
					<li>1,521 users online</li>
				</ul>

			</div>

		</div>

		@if(Service\Security::online() and !Service\Security::activated())
		<div class="fixed-message">
			Don't forget to confirm your e-mail address. We can {{ HTML::link_to_route('resend', 'resend') }} the confirmation e-mail if you never got it.
		</div>
		@endif

		{{ HTML::script('assets/theme.js') }}
	</body>

</html>