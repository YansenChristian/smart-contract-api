<?php

namespace App\Http\Controllers;

use App\Exceptions\ApiValidationException;
use App\Exceptions\AuthenticationException;
use App\Exceptions\RouteException;
use Codeception\Codecept;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Lumen\Routing\Controller as BaseController;
use Symfony\Component\Process\Process;

/**
 * @OA\Info(
 *     title="API SELLER",
 *     version="1.0",
 *     description="Welcome to Ralali API for Seller. Ralali API is a RESTful Web Service served as a communication bridge between client to our system.",
 *     @OA\Contact(
 *        email= "willy.brordus@ralali.com"
 *     )
 *  )
 *
 * @OA\Tag(
 *     name="(v1) Account Resource",
 *     description="Manage Seller Account"
 * )
 *
 * @OA\Tag(
 *     name="(v1) Brand Resource",
 *     description="Manage Seller Brands"
 * )
 *
 * @OA\Tag(
 *     name="(v1) Category Resource",
 *     description="Manage Ralali Category"
 * )
 *
 * @OA\Tag(
 *     name="(v1) Courier Resource",
 *     description="Manage Seller Couriers"
 * )
 *
 * @OA\Tag(
 *     name="(v1) Item Resource",
 *     description="Manage Seller Products"
 * )
 *
 * @OA\Tag(
 *     name="(v1) Location Resource",
 *     description="Get Administrative Location Data"
 * )
 *
 * @OA\Tag(
 *     name="(v1) Notification Resource",
 *     description="Push Order Status Notification"
 * )
 *
 * @OA\Tag(
 *     name="(v1) Onboarding Resource",
 *     description="Create and Manage Onboarding Process"
 * )
 *
 * @OA\Tag(
 *     name="(v1) Order Resource",
 *     description="Create and Manage Seller Orders"
 * )
 *
 * @OA\Tag(
 *     name="(v1) Register Resource",
 *     description="Manage Register"
 * )
 *
 * @OA\Tag(
 *     name="(v1) Report Resource",
 *     description="Manage Seller Report"
 * )
 *
 * @OA\Tag(
 *     name="(v2) Item Resource",
 *     description="Manage Seller Products"
 * )
 *
 * @OA\Tag(
 *     name="(v2) Order Resource",
 *     description="Create and Manage Seller Orders"
 * )
 *
 * @OA\Tag(
 *     name="(v2) User Fcm Resource",
 *     description="Manage Seller Account"
 * )
 */
class Controller extends BaseController
{
    
    public function accessDenied()
    {
        return Response::create(null, 403);
    }

    public function getStaticDocs() {
        $filePath = storage_path('static-docs/static-docs.yaml');

        if (! File::exists($filePath)) {
            abort(404, 'Cannot find '.$filePath);
        }

        $content = File::get($filePath);

        return new Response($content, 200, ['Content-Type' => 'application/x-yaml']);
    }

    public function staticDocs()
    {
        $url = route('static-docs-file');
        $url = Request::secure()
        ? preg_replace("/^http:/i", "https:", $url)
        : preg_replace("/^https:/i", "http:", $url);

        return view('redoc.index', [
            'documentationFile' => $url
        ]);
    }

    public function healthcheck()
    {
        if(env('APP_ENV') == 'production') {
            echo "true";exit;
        }

        if(env('APP_ENV') == 'development') {
            $report = Storage::disk('s3-api-seller')->get('codeception/report.html');
        }
        else {
            $report = file_get_contents(base_path().'/tests/_output/report.html');
        }

        return response($report);
    }

    function getUserId() {
        $vendor_id = (isset(Auth::user()->vendor_id) && Auth::user()->vendor_id) ? Auth::user()->vendor_id : Auth::user()->id;

        return $vendor_id;
    }

    function throwApiException(Validator $validator) {
        throw new ApiValidationException($validator);
    }
}
