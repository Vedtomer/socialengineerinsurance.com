<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ConvertNullToEmptyString
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $content = $response->getContent();

        // Try to decode JSON content
        $decoded = json_decode($content, true);

        if (json_last_error() === JSON_ERROR_NONE) {
            // If it's valid JSON, process it
            $processed = $this->convertNullToEmptyString($decoded);
            $response->setContent(json_encode($processed));
        } else {
            // If it's not JSON, process it as a string
            $processed = $this->convertNullToEmptyString($content);
            $response->setContent($processed);
        }

        return $response;
    }

    /**
     * Recursively convert null values to empty strings and whole numbers to integers.
     *
     * @param mixed $data
     * @return mixed
     */
    protected function convertNullToEmptyString($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                $data[$key] = $this->convertNullToEmptyString($value);
            }
        } elseif (is_null($data)) {
            return '';
        } elseif (is_numeric($data)) {
            $float_value = (float) $data;
            if ($float_value == (int) $float_value) {
                return (int) $float_value;
            }
            return $float_value;
        } elseif (is_string($data)) {
            // Process string content (e.g., HTML)
            return preg_replace_callback('/\bnull\b|\b\d+(\.\d+)?\b/', function($matches) {
                if ($matches[0] === 'null') return '';
                $float_value = (float) $matches[0];
                return ($float_value == (int) $float_value) ? (int) $float_value : $float_value;
            }, $data);
        }

        return $data;
    }
}
