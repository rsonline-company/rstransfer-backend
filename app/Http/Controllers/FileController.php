<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\FileRequest;
use App\Interfaces\FileInterface;

class FileController extends Controller
{
    protected $fileInterface;

    public function __construct(FileInterface $fileInterface) {
        $this->fileInterface = $fileInterface;
    }

    public function store(FileRequest $request) {
        $response = $this->fileInterface->store($request);

        return response()->json($response);
    }
}
