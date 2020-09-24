<?php

use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

if (!function_exists('sendError')) {
  function sendError($error, $errorMessages = [], $code = 403)
  {
      $response = [
          'success' => false,
          'message' => $error,
      ];
      if(!empty($errorMessages)){
          $response['data'] = $errorMessages;
      }
      return response()->json($response, $code);
  }
}