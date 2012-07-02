<!DOCTYPE html>

<html>

	<head>
		<title>{{ $title }} &ndash; {{ $app->title }}</title>

		{{ HTML::style('/assets/theme.css') }}
	</head>

	<body>

		@if(Bouncer::online() and !Bouncer::activated())
		<div class="fixed-message">
			<strong>Wait up!</strong> Don't forget to confirm your e-mail address. We can {{ HTML::link_to_route('resend', 'resend the confirmation e-mail') }} if you never got it.
		</div>
		@endif

		<div class="container">

			<div class="header group">
				<h1>{{ HTML::link('/', $app->title) }}</h1>

				<div class="search">
					<input type="text" placeholder="Search...">
					<input type="submit" value="">
				</div>
			</div>

			<ul class="menu group">

				<li><a href="{{ URL::home() }}" class="selected">Home</a></li>
				<li><a href="{{ URL::to('activity') }}">Recent Activity</a></li>
				<li><a href="{{ URL::to('members') }}">Members</a></li>
				
				@if(Bouncer::is('moderator'))
				<li class="right"><a href="{{ URL::to('moderator/dashboard') }}">Moderator Dashboard</a></li>
				@endif

				@if(Bouncer::is('admin'))
				<li class="right"><a href="{{ URL::to('admin/dashboard') }}">Admin Dashboard</a></li>
				@endif

			</ul>

			<div class="body">

				<div class="left">

					@if(Bouncer::activated())
						<div class="new-discussion">
							{{ HTML::link('', 'New Discussion', array('class' => 'btn orange large')) }}
						</div>
					@endif

					<div class="user">
						@if(Bouncer::online())
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
					Powered by {{ HTML::link('http://featherforums.com', 'Feather', array('class' => 'tooltip-ui-footer', 'title' => 'Version [development]')) }}
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