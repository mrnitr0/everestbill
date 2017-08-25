<?php
namespace EverestBill\Http\Controllers\Frontend\Auth;

use Exception;
use Illuminate\View\Factory as View;
use Cartalyst\Sentinel\Sentinel as Auth;
use EverestBill\Http\Requests\LoginData;
use EverestBill\Http\Controllers\Controller;
use Illuminate\Routing\Redirector as Redirect;

class Login extends Controller
{
    /**
     * Get the login form
     *
     * @param View $view
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function getForm(View $view)
    {
        return $view->make('frontend.login');
    }

    /**
     * Perform the login request
     *
     * @param LoginData $request
     * @param Auth      $auth
     * @param Redirect  $redirect
     *
     * @return mixed
     */
    public function perform(
        LoginData $request,
        Auth $auth,
        Redirect $redirect
    )
    {
        try {
            if($user = $auth->authenticate($request->all())) {
                return $redirect
                    ->intended()
                    ->withSuccess('Login successful.');
            } else {
                throw new Exception(
                    'An error occured while registering. Please try again.'
                );
            }
        } catch(Exception $e) {
            return $redirect
                ->back()
                ->withInput()
                ->withError($e->getMessage());
        } 
    }
}
