<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ResponseController extends Controller
{
    public function response() : Response{
        return Response(["data" => "data"],200)->header('X-HEADER-1', "VALUE-1")->header('X-HEADER-2', "VALUE-2")->withHeaders([]);
    }

    public function responseView() : Response{
        return Response()->view("");
    }

    public function responseJson() : JsonResponse{
        //* header content type auto sheet
        return Response()->json();
    }


    public function responseFile() : BinaryFileResponse{
        //* menampilkan file
        return Response()->file("");
    }

    
    public function responseDownload() : BinaryFileResponse{
        //* file dipaksa untuk didownload
        return Response()->download("");
    }

}
