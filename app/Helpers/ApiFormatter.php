<?php

namespace App\Helpers;
// namespace : menentukan lokasi folder dari file ini

// nama class == name file
class ApiFormatter {
    // variable struktur data yang akan ditampilkan di response postman
    protected static $response = [
        "status" => NULL,
        "message"  => NULL,
        "date" => NULL,
    ];

    public static function sendResponse($status = NULL, $message = NULL, $date = [])
    {
        self::$response['status'] = $status;
        self::$response['message'] = $message;
        self::$response['date'] = $date;
        return response()->json(self::$response, self::$response['status']);
        // status : http status code (200, 400, 500)
        // message : date http status code ('success', 'bad request', 'server error')
        // data : hasil yang diambil dari db
    }
}