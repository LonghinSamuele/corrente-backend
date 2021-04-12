<?php

class Response
{
    public static function json($data)
    {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin:*');
        header('Cross-Origin-Embedder-Policy: require-corp');
        header('Cross-Origin-Opener-Policy: same-origin');

        echo json_encode($data);
        die();
    }
}