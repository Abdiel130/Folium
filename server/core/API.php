<?php

namespace App\Core;

/**
 * API Handler - Centralizes and standardizes all server responses.
 * Follows SOLID principles and provides a robust, developer-friendly interface.
 */
final class API implements HttpCodes
{
    /**
     * Success JSON Response.
     * 
     * @param mixed $data Payload payload to be returned.
     * @param string $message User friendly message for success.
     * @param int $code HTTP Status code (defaults to 200 OK).
     * @return string JSON encoded string.
     */
    public static function success(mixed $data = null, string $message = 'Request completed successfully', int $code = self::HTTP_OK): string
    {
        return self::createResponse(true, $message, $code, $data);
    }

    /**
     * Generic Error JSON Response.
     * 
     * @param string $message Error message explaining what went wrong.
     * @param int $code HTTP Status code (defaults to 500 Internal Server Error).
     * @param mixed $errors Optional list of errors (e.g., validation errors).
     * @return string JSON encoded string.
     */
    public static function error(string $message = 'Something went wrong', int $code = self::HTTP_INTERNAL_SERVER_ERROR, mixed $errors = null): string
    {
        return self::createResponse(false, $message, $code, null, $errors);
    }

    /**
     * 404 Not Found Shorthand.
     * 
     * @param string $message
     * @return string
     */
    public static function notFound(string $message = 'Resource not found'): string
    {
        return self::error($message, self::HTTP_NOT_FOUND);
    }

    /**
     * 401 Unauthorized Shorthand.
     * 
     * @param string $message
     * @return string
     */
    public static function unauthorized(string $message = 'Unauthorized'): string
    {
        return self::error($message, self::HTTP_UNAUTHORIZED);
    }

    /**
     * 403 Forbidden Shorthand.
     * 
     * @param string $message
     * @return string
     */
    public static function forbidden(string $message = 'Forbidden'): string
    {
        return self::error($message, self::HTTP_FORBIDDEN);
    }

    /**
     * 422 Validation Error Shorthand.
     * 
     * @param mixed $errors Validation errors.
     * @param string $message
     * @return string
     */
    public static function validation(mixed $errors, string $message = 'Validation failed'): string
    {
        return self::error($message, self::HTTP_UNPROCESSABLE_ENTITY, $errors);
    }

    /**
     * Core method to build the standardized JSON response.
     * 
     * @param bool $success
     * @param string $message
     * @param int $code
     * @param mixed|null $data
     * @param mixed|null $errors
     * @return string
     */
    private static function createResponse(bool $success, string $message, int $code, mixed $data = null, mixed $errors = null): string
    {
        // Set proper headers
        if (!headers_sent()) {
            header('Content-Type: application/json; charset=utf-8');
            http_response_code($code);
        }

        $payload = [
            'success' => $success,
            'message' => $message,
            'code'    => $code,
            'timestamp' => date('Y-m-d H:i:s'),
        ];

        // Only include data/errors if they are not null to keep response clean
        if ($data !== null) {
            $payload['data'] = $data;
        }

        if ($errors !== null) {
            $payload['errors'] = $errors;
        }

        return json_encode($payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }
}
