<?php

namespace App\Interfaces;

use App\Http\Requests\FileRequest;

interface FileInterface
{
    /**
     * Store files
     * 
     * @method  POST    api/files/store
     * @access  public
     */
    public function store(FileRequest $request);

    /**
     * Load files
     * 
     * @method  POST    api/files/load
     * @access  public
     */
    public function load();
}