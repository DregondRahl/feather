<div class="alert alert-error">

	<strong>Oops!</strong> We found the following problems.

	<ol>
	@foreach($errors->all() as $error)

		<li>{{ $error }}</li>

	@endforeach
	</ol>

</div>