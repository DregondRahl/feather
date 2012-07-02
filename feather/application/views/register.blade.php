<h2>Join {{ $app->title }}</h2>

{{ Form::errors($errors) }}

<script type="text/javascript">
		var RecaptchaOptions = {
			theme : 'custom',
			custom_theme_widget : 'recaptcha_widget'
		}
	</script>

<div class="form-multi group">

	<div class="form-left">

		<fieldset>

			{{ Form::open(null, 'POST') }}

			<ol>

				<li>
					{{ Form::label('username', 'Username') }}
					{{ Form::text('username', Input::old('username'), array('autocomplete' => 'off', 'tabindex' => 1)) }}
				</li>

				<li>
					{{ Form::label('email', 'E-mail') }}
					{{ Form::text('email', Input::old('email'), array('autocomplete' => 'off', 'tabindex' => 1)) }}
				</li>

				<li>
					{{ Form::label('recaptcha_response_field', 'Security Confirmation') }}

					<div id="recaptcha_widget" style="display:none">

		   				<div id="recaptcha_image"></div>

		   				<div id="recaptcha_info">
			   				<span class="recaptcha_only_if_image">Enter the words above:</span>
			   				<span class="recaptcha_only_if_audio">Enter the numbers you hear:</span>
			   			</div>

		   				{{ Form::text('recaptcha_response_field', null, array('tabindex' => 2)) }}

						<ul class="join-recaptcha">
							<li class="refresh tooltip-ui" title="Get another image.">
								<a href="javascript:Recaptcha.reload()"></a>
							</li>
							<li class="audio tooltip-ui recaptcha_only_if_image" title="Listen to the words.">
								<a href="javascript:Recaptcha.switch_type('audio')"></a>
							</li>
							<li class="image tooltip-ui recaptcha_only_if_audio" title="See the words.">
								<a href="javascript:Recaptcha.switch_type('image')"></a>
							</li>
							<li class="help tooltip-ui" title="Need help?">
								<a href="javascript:Recaptcha.showhelp()"></a>
							</li>
						</ul>

		 			</div>

		 			{{ HTML::script('http://www.google.com/recaptcha/api/challenge?k=' . Config::get('feather.registration.recaptcha_public_key')) }}
				</li>

				@if(Config::get('feather.registration.rules', 0))

				<li>
					{{ Form::checkbox('rules', 1, Input::had('rules'), array('id' => 'rules', 'tabindex' => 2)) }}

					<label for="rules" class="inline normal">
						I have read and agree to the {{ HTML::link_to_route('rules', 'community rules', array(), array('class' => 'font-bold popup-ui')) }}.
					</label>
				</li>

				@endif

				<li>
					{{ Form::submit('Join Community', array('class' => 'btn blue large', 'tabindex' => 2)) }}
				</li>

			</ol>

			{{ Form::token() . Form::close() }}
		</fieldset>

	</div>

	<div class="form-right">

		<fieldset>

			<ol>
				<li>
					{{ Form::label('password', 'Password') }}
					{{ Form::password('password', array('autocomplete' => 'off', 'tabindex' => 1)) }}
				</li>

				<li>
					{{ Form::label('password_confirmation', 'Confirm Password') }}
					{{ Form::password('password_confirmation', array('autocomplete' => 'off', 'tabindex' => 1)) }}
				</li>
			</ol>

		</fieldset>

	</div>