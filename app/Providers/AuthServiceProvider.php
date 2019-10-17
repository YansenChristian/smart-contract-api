<?php

namespace App\Providers;

use App\Exceptions\ScopeException;
use App\Scope\ScopeService;
use App\User;
use Firebase\JWT\JWT;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Cache;
use GuzzleHttp\Psr7\Request as GuzzleRequest;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('scope.service', function() {
            return new ScopeService();
        });
    }

    /**
     * Boot the authentication services for the application.
     *
     * @return void
     */
    public function boot()
    {
        $algorithms = ['RS256'];
        $this->app['auth']->viaRequest('api', function (Request $request) use($algorithms) {
            if ($header = $request->header('Authorization')) {
                list($type, $token) = explode(' ', $header);

                # [POSTMAN] Get Client Data
                $cache_key = sprintf('client_pub:%s', $token);
                if(strtolower($type) == 'bearer') {
                    if(Cache::has($cache_key) == false or Cache::get($cache_key) === null) {
                        $client = new Client();

                        $url = sprintf('%s%s', env('API_OAUTH_HOST'), '/v2/client');
                        $request = new GuzzleRequest(Request::METHOD_GET, $url, [
                            'Content-Type' => 'application/json',
                            'Authorization' => 'Bearer ' . $token
                        ]);

                        $res = $client->send($request);
                        $data_pub = json_decode($res->getBody(), true);

                        Cache::put($cache_key, $data_pub, 720);
                    }

                    JWT::$leeway = 60;
                    $pub_key = Cache::get($cache_key)['key_pairs']['public'];
                    $data = JWT::decode($token, $pub_key, $algorithms);

                    $user = DB::table('users')
                        ->where('id', $data->user_id)
                        ->select([
                            'id',
                            'vendor_id',
                            'name',
                            'email',
                            'type',
                            'vendor_status'
                        ])
                        ->first();


                    # [SCOPE SERVICE] Get available token scopes
                    $service = $this->app->get('scope.service');
                    $service->read(function() use($token, $data) {
                        # [POSTMAN] Get User Scopes
                        $cache_scope_key = sprintf('scopes:%s', $token);
                        if(Cache::has($cache_scope_key) == false or Cache::get($cache_scope_key) === null) {
                            $client = new Client();

                            $url = sprintf('%s%s', env('API_OAUTH_HOST'), '/v2/scopes');
                            $request = new GuzzleRequest(Request::METHOD_GET, $url, [
                                'Content-Type' => 'application/json',
                                'Authorization' => 'Bearer ' . $token
                            ]);

                            $res = $client->send($request);
                            $token_scopes = json_decode($res->getBody(), true)['scopes'];

                            Cache::put($cache_scope_key, $token_scopes, 720);
                        }

                        return [ 'token' => $token, 'role' => $data->role, 'token_scopes' => Cache::get($cache_scope_key) ];
                    });


                    return $user;
                }
            }
        });
    }
}
