<?php
/**
 * Created by PhpStorm.
 * User: alifa
 * Date: 5/17/2022
 * Time: 8:39 PM
 */

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class BaseController extends Controller
{
    /**
     * Send a successful JSON response.
     *
     * @param mixed  $result  The data to return.
     * @param string $message The success message.
     * @param int    $code    The HTTP status code (default: 200).
     *
     * @return JsonResponse
     */
    public function successResponse(
        $result,
        string $message = "Operation was successfully",
        int $code = 200
    ): JsonResponse
    {
        $response = [
            'success' => true,
            'message' => $message,
            'data'    => $result
        ];
        return response()->json($response, $code);
    }

    /**
     * Send an error JSON response.
     *
     * @param array|string $errors  An array of error details or a single error message.
     * @param string       $message The error message.
     * @param int          $code    The HTTP status code (default: 422).
     *
     * @return JsonResponse
     */
    public function errorResponse($errors, string $message, int $code = 422): JsonResponse
    {
        // Ensure errors are always an array
        if (!is_array($errors)) {
            $errors = [$errors];
        }

        $response = [
            'success' => false,
            'message' => $message,
            'errors'  => $errors
        ];
        return response()->json($response, $code);
    }

    protected function checkDependencies($model, $relations)
    {
        $dependencies = [];

        foreach ($relations as $relation => $label) {
            if ($model->$relation()->exists()) {
                $dependencies[] = $label;
            }
        }

        return !empty($dependencies) ? $dependencies : false;
    }
}
