<nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
	<div class="container">
		<button class="navbar-toggler" type="button" data-toggle="collapse"
			data-target="#navbarSupportedContent"
			aria-controls="navbarSupportedContent" aria-expanded="false"
			aria-label="{{ __('Toggle navigation') }}">
			<span class="navbar-toggler-icon"></span>
		</button>

		<div class="collapse navbar-collapse" id="navbarSupportedContent">
		@auth
			<ul class="navbar-nav main">
        <li class="nav-item">
            <a href="{{ route('home') }}" class="nav-link {{ request()->is('/') ? 'active' : '' }}">Home</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('committee') }}" class="nav-link {{ request()->is('committee*') ? 'active' : '' }}">Committee Management</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('charge') }}" class="nav-link {{ request()->is('charge*') ? 'active' : '' }}">Charge Membership</a>
        </li>
        <li class="nav-item">
            <a href="{{ route('list') }}" class="nav-link {{ request()->is('list*') ? 'active' : '' }}">List Management</a>
        </li>
			</ul>
			@endauth

			<!-- Right Side Of Navbar -->
			<ul class="navbar-nav ml-auto">
				<!-- Authentication Links -->
				@guest
					<li class="nav-item"><a class="nav-link" href="{{ route('login') }}">{{
							__('Login') }}</a></li> @if (Route::has('register'))
					<li class="nav-item"><a class="nav-link"
						href="{{ route('register') }}">{{ __('Register') }}</a></li> @endif
				@else
					<li class="nav-item dropdown">
						<a id="navbarDropdown"
							class="nav-link dropdown-toggle" href="#" role="button"
							data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"
							v-pre> {{ Auth::user()->name }} <span class="caret"></span>
						</a>
	
						<div class="dropdown-menu dropdown-menu-right"
							aria-labelledby="navbarDropdown">
							<a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">{{ __('Logout') }} </a>
							<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">@csrf</form>
						</div>
					</li> 
				@endguest
			</ul>
		</div>
	</div>
</nav>
