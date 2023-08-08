<?php

namespace App\Traits;

use App\Request;
use App\Helpers\Session;

trait HasProtectedRoutes {
    public function redirectAnonymousUsers(
        string $path = '/login',
        ?string $message = 'You must be signed in to visit this page.'
    )
    {
        if (!$this->user->isLoggedIn()) {
            if ($message) Session::flash('error', $message);
            $this->response->redirectTo($path);
        }
    }

    public function redirectAuthenticatedUsers(
        string $path = '/dashboard',
        ?string $message = 'You are already signed in.'
    ) {
        if ($this->user->isLoggedIn()) {
            if ($message) Session::flash('error', $message);
            $this->response->redirectTo($path);
        }
    }

    // Protects against CSRF
    public function redirectUnintendedAccess(
        Request $request,
        string $path = '/',
        ?string $message = 'This request did not seem intentional.')
    {
        if (
            !isset($request->getInput('get')['csrfToken']) ||
            $request->getInput('get')['csrfToken'] !== Session::get('csrfToken')
        ) {
            Session::flash('error', $message);
            $this->response->redirectTo($path);
        }
    }
}
