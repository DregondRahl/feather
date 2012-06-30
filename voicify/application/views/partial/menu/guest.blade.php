<h3>Hello!</h3>

<p>
	Welcome to our community.
</p>

<p>
	We encourage all new visitors to join and take part in the discussions.
</p>

{{ HTML::link_to_route('login', 'Sign In', null, array('class' => 'signin btn light')) }}

{{ HTML::link_to_route('register', 'Join Community', null, array('class' => 'join btn light')) }}

<p>
	Connect with an existing service.
</p>

<div class="authorization-links">
	@include('partial.authorization')
</div>