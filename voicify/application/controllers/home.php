<?php

class Home_Controller extends Base_Controller {

	/**
	 * Show the homepage.
	 */
	public function get_index()
	{
		$this->layout->content = 'Hello World';
	}

	/**
	 * Show the sign in form.
	 */
	public function get_signin()
	{
		$this->layout->with('title', 'Sign In')
					 ->nest('content', 'login');
	}

	/**
	 * Handle a user sign in attempt.
	 * 
	 */
	public function post_signin()
	{
		if(Service\Validation\Login::passes(Input::all()))
		{
			if(Service\Security::authorize(array('username' => Input::get('username'), 'password' => Input::get('password'))))
			{
				return Redirect::home();
			}
			else
			{
				return Redirect::to_route('login')->with_input()->with_errors(new Messages(array(
					__('login.failed')->get()
				)));
			}
		}

		return Redirect::to_route('login')->with_input()->with_errors(Service\Validation::errors());
	}

	/**
	 * Handle the logout of a user.
	 */
	public function get_signout()
	{
		Service\Security::logout();

		return Redirect::home();
	}

	/**
	 * Show the join form.
	 */
	public function get_join()
	{
		$this->layout->with('title', 'Join')
					 ->nest('content', 'theme: register');
	}

	/**
	 * Handle a user join attempt.
	 */
	public function post_join()
	{
		if(Service\Validation\Register::passes(Input::all()))
		{
			if($user = Repository\User::register(Input::all()))
			{
				// Users must confirm their e-mail address once they have registered. We
				// will now send them an e-mail with their activation code.
				if(Config::get('feather.registration.confirm_email'))
				{
					
				}

				// Log the user in and redirect back to the home page.
				Service\Security::authorize($user);

				return Redirect::home();
			}
			else
			{
				return Redirect::to_route('register')->with_input()->with_errors(new Messages(array(
					__('register.failed')->get()
				)));
			}
		}

		return Redirect::to_route('register')->with_input()->with_errors(Service\Validation::errors());
	}

	/**
	 * Show the rules. If the request is Ajax, only show the rules without the layout.
	 */
	public function get_rules()
	{
		$this->layout->with('title', 'Rules')
					 ->nest('content', 'rules');
	}

}