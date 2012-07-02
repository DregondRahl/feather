<h3>G'day {{ $app->users->online->username }}!</h3>

<p>
	You have new messages!
</p>

<ul>
	<li>{{ HTML::link('', 'My Discussions') }}</li>
	<li>{{ HTML::link_to_route('logout', 'Sign Out') }}</li>
</ul>