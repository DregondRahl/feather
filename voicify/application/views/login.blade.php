<h2>Sign In</h2>

<div class="form-multi group">

	{{ Form::errors($errors) }}

	<div class="form-left">

		<fieldset>

			{{ Form::open(URL::to_route('login'), 'POST') }}

			<ol>

				<li>
					{{ Form::label('username', 'Username') }}
					{{ Form::text('username', Input::old('username'), array('autocomplete' => 'off')) }}
				</li>

				<li>
					{{ Form::label('password', 'Password') }}
					{{ Form::password('password', array('autocomplete' => 'off')) }}
				</li>

				<li>
					{{ Form::checkbox('remember', 1, Input::old('remember'), array('id' => 'remember')) }}
					{{ Form::label('remember', 'Remember me on this computer', array('class' => 'inline normal')) }}
				</li>
				
				<li>
					{{ Form::submit('Sign In', array('class' => 'btn blue')) }}&nbsp;&nbsp;
					{{ HTML::link_to_route('register', 'No account? Join our community!', array(), array('class' => 'btn light font-bold')) }}
				</li>

			</ol>

			{{ Form::token() . Form::close() }}
		</fieldset>

	</div>

	<div class="form-right authorization-links">
		<h4 class="font-bold">Or connect with an existing service.</h4>

		@include('theme: partial.authorization')
	</div>

</div>