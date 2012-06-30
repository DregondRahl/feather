<!DOCTYPE html>

<html>

	<head>
		<title>{{ $title }} &ndash; {{ $app['title'] }}</title>

		{{ HTML::style('/assets/theme.css') }}
	</head>

	<body>

		@if(Service\Security::online() and !Service\Security::activated())
		<div class="fixed-message">
			<strong>Wait up!</strong> Don't forget to confirm your e-mail address. We can {{ HTML::link_to_route('resend', 'resend the confirmation e-mail') }} if you never got it.
		</div>
		@endif

		<div class="container">

			<h1 class="header">{{ HTML::link('/', $app['title']) }}</h1>

			<ul class="menu group">

				<li><a href="" class="selected">Home</a></li>
				<li><a href="">Members</a></li>
				<li><a href="">Administration</a></li>

			</ul>

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
						
						{{ $content }}

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

		{{ HTML::script('assets/theme.js') }}
	</body>

</html>