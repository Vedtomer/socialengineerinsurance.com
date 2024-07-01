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

        if ($response->headers->get('Content-Type') === 'application/json') {
            $content = json_decode($response->getContent(), true);
            $content = $this->convertNullToEmptyString($content);
            $response->setContent(json_encode($content));
        }

        return $response;
    }

    /**
     * Recursively convert null values to empty strings and numbers to integers in an array.
     *
     * @param array|null $array
     * @return array|string|int
     */
    protected function convertNullToEmptyString($array)
    {
        if (!is_array($array)) {
            if ($array === null) {
                return '';
            } elseif (is_numeric($array)) {
                return (int) $array;
            }
            return $array;
        }

        foreach ($array as $key => $value) {
            $array[$key] = $this->convertNullToEmptyString($value);
        }

        return $array;
    }
}
