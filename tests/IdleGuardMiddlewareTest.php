<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Mretaa\AutoLogout\Middleware\IdleGuardMiddleware;
use Tests\TestCase;

class IdleGuardMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_is_logged_out_after_inactivity()
    {
        // Simule un utilisateur connecté
        $user = \App\Models\User::factory()->create();
        $this->be($user);

        // Simule la dernière activité dépassée
        Session::put('last_activity', time() - (Config::get('idle-guard.idle_time') + Config::get('idle-guard.logout_time') + 1));

        $middleware = new IdleGuardMiddleware();
        $request = Request::create('/dashboard', 'GET');
        $response = $middleware->handle($request, function () {
            return response('OK');
        });

        $this->assertFalse(Auth::check());
        $this->assertEquals(302, $response->getStatusCode());
        $this->assertEquals(Config::get('idle-guard.redirect_url'), $response->headers->get('Location'));
    }

    public function test_user_activity_resets_last_activity()
    {
        $user = \App\Models\User::factory()->create();
        $this->be($user);
        Session::put('last_activity', time() - 10);

        $middleware = new IdleGuardMiddleware();
        $request = Request::create('/dashboard', 'GET');
        $middleware->handle($request, function () {
            return response('OK');
        });

        $this->assertEquals(time(), Session::get('last_activity'));
    }

        public function test_user_is_not_logged_out_if_active()
        {
            $user = \App\Models\User::factory()->create();
            $this->be($user);
            Session::put('last_activity', time());

            $middleware = new IdleGuardMiddleware();
            $request = Request::create('/dashboard', 'GET');
            $response = $middleware->handle($request, function () {
                return response('OK');
            });

            $this->assertTrue(Auth::check());
            $this->assertEquals('OK', $response->getContent());
        }

        public function test_middleware_does_nothing_if_not_authenticated()
        {
            Auth::logout();
            Session::put('last_activity', time() - 1000);

            $middleware = new IdleGuardMiddleware();
            $request = Request::create('/dashboard', 'GET');
            $response = $middleware->handle($request, function () {
                return response('OK');
            });

            $this->assertEquals('OK', $response->getContent());
        }

        public function test_session_is_forgotten_after_logout()
        {
            $user = \App\Models\User::factory()->create();
            $this->be($user);
            Session::put('last_activity', time() - (Config::get('idle-guard.idle_time') + Config::get('idle-guard.logout_time') + 1));

            $middleware = new IdleGuardMiddleware();
            $request = Request::create('/dashboard', 'GET');
            $middleware->handle($request, function () {
                return response('OK');
            });

            $this->assertNull(Session::get('last_activity'));
        }

        public function test_custom_config_is_respected()
        {
            Config::set('idle-guard.idle_time', 10);
            Config::set('idle-guard.logout_time', 5);
            Config::set('idle-guard.redirect_url', '/custom-logout');

            $user = \App\Models\User::factory()->create();
            $this->be($user);
            Session::put('last_activity', time() - 16); // 10 + 5 + 1

            $middleware = new IdleGuardMiddleware();
            $request = Request::create('/dashboard', 'GET');
            $response = $middleware->handle($request, function () {
                return response('OK');
            });

            $this->assertFalse(Auth::check());
            $this->assertEquals(302, $response->getStatusCode());
            $this->assertEquals('/custom-logout', $response->headers->get('Location'));
        }
}
