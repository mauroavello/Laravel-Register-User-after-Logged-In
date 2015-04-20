<?php namespace Re\Http\Controllers\Auth;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Re\Http\Controllers\Controller;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\Registrar;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Registration & Login Controller
	|--------------------------------------------------------------------------
	|
	| This controller handles the registration of new users, as well as the
	| authentication of existing users. By default, this controller uses
	| a simple trait to add these behaviors. Why don't you explore it?
	|
	*/

	use AuthenticatesAndRegistersUsers;

	protected $redirectTo = '/';


	/**
	 * Create a new authentication controller instance.
	 *
	 * @param  \Illuminate\Contracts\Auth\Guard     $auth
	 * @param  \Illuminate\Contracts\Auth\Registrar $registrar
	 *
	 * @return void
	 */
	public function __construct ( Guard $auth, Registrar $registrar )
	{
		$this->auth      = $auth;
		$this->registrar = $registrar;

		$this->middleware( 'guest', [ 'except' => [ 'getLogout', 'getRegister', 'postRegister' ] ] );
//		$this->middleware('auth', ['only' => ['getRegister', 'postRegister' ]]);
	}


	public function getRegister ()
	{
		if ( $this->auth->check() ) {
			return view( 'auth.register' );
//			return "AuthController";
//			dd( $this->auth->user());
//			return new RedirectResponse(url('/auth/register'));
		}

		return redirect( '/auth/login' );
	}

	/**
	 * Handle a registration request for the application.
	 *
	 * @param  \Illuminate\Http\Request $request
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function postRegister ( Request $request )
	{
		$validator = $this->registrar->validator( $request->all() );

		if ( $validator->fails() ) {
			$this->throwValidationException( $request, $validator );
		}

		$this->registrar->create( $request->all() );

		return redirect( $this->redirectPath() );
	}
}