<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\App;

class Handler extends ExceptionHandler
{
		private $sentryID;
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        AuthorizationException::class,
        HttpException::class,
        ModelNotFoundException::class,
        ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $e
     * @return void
     */
//    public function report(Exception $e)
//    {
//        parent::report($e);
//    }
			public function report(Exception $e)
			{
				if ($this->shouldReport($e)) {
					app('sentry')->captureException($e);
				}
				parent::report($e);
			}
	
	
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $e
     * @return \Illuminate\Http\Response
     */
//    public function render($request, Exception $e)
//    {
//        return parent::render($request, $e);
//    }
			public function render($request, Exception $e)
			{
				//Localisation
				if (Session::has('applocale') AND array_key_exists(Session::get('applocale'), Config::get('languages'))) {
					App::setLocale(Session::get('applocale'));
				}
				else {
					//Et by default
					App::setLocale('et');
				}
				
				if($this->isHttpException($e)){
					switch ($e->getStatusCode()) {
						case '404':
							\Log::error($e);
							return \Response::view('errors.404');
							break;
						
						case '500':
							return response()->view('errors.500', [
									'sentryID' => $this->sentryID,
							], 500);
							break;
						
						default:
							return $this->renderHttpException($e);
							break;
					}
				}
				else
				{
					return parent::render($request, $e);
				}
				
			}
}
