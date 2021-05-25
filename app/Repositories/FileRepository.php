<?php

namespace App\Repositories;

use App\Http\Requests\FileRequest;
use App\Interfaces\FileInterface;
use File;
use DB;

class FileRepository implements FileInterface
{
    public function store(FileRequest $request) {
        $data = $request->all();
        $files = $data['files'];

        $filesNum = 0;
        foreach($files as $file) {
            $filesNum++;
        }

        $fileNames = array();
        $zipName = null;
        if($filesNum === 1) {
            $fileNames = $this->saveFilesToStorage($files);
        }

        if($filesNum > 1) {
            $zipData = $this->zipFiles($files);

            $fileNames = $zipData->fileNames;
            $zipName = $zipData->name;
        }

        $insertData = array();
        foreach($fileNames as $fileName) {
            array_push($insertData, array(
                'name' => $fileName,
                'parent_zip_name' => $zipName,
                'uploader_key' => $data['uploaderKey'],
                'user_id' => auth()->user() ? auth()->user()->id : null,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'expires_at' => date('Y-m-d H:i:s', strtotime('+3 days'))
            ));
        }

        DB::table('files')->insert($insertData);

        $response = new \stdClass();

        // Check if send email or get download link
        /* In this case we return link to download the file */
        if($data['sendEmail'] === 'false') {
            if($zipName !== null) {
                $response->downloadLink = $_SERVER['SERVER_NAME'].':8000/api/download/'.$zipName;
            } else {
                $response->downloadLink = $_SERVER['SERVER_NAME'].':8000/api/download/'.$fileNames[0];
            }
        } else {
            $response = 'Email has been sent.';
        }

        return $response;
    }

    private function saveFilesToStorage($files) {
        $fileNames = array();
        foreach($files as $file) {
            $fileNameToStore = $this->createFileName($file);
            array_push($fileNames, $fileNameToStore);

            $path = $file->storeAs('public/files', $fileNameToStore);
        }

        return $fileNames;
    }

    private function zipFiles($files) {
        $zip = new \ZipArchive();
        $zipName = 'zip_'.time().mt_rand( 0, 0xffff ).'.zip';

        $fileNames = array();

        if ($zip->open(public_path('storage/files/'.$zipName), \ZipArchive::CREATE)== TRUE) {
            foreach ($files as $file) {
                $fileNameToStore = $this->createFileName($file);
                array_push($fileNames, $fileNameToStore);

                $zip->addFile($file, $fileNameToStore); // add to zip
            }
            $zip->close();
        }

        $zipData = new \stdClass();
        $zipData->name = $zipName;
        $zipData->fileNames = $fileNames;

        return $zipData;
    }

    private function createFileName($file) {
        $fileNameWithExt = $file->getClientOriginalName();
        $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
        $extension = $file->guessExtension();
        $fileNameToStore = $filename.'_'.time().mt_rand( 0, 0xffff ).'.'.$extension;

        return $fileNameToStore;
    }

    private function createDownloadLink() {
        $headers = ["Content-Type: application/$fileExtension"];

        return response()->download($filePath, $fileName, $headers);
    }

    public function load() {
        $files = DB::table('files')
            ->where('user_id', auth()->user()->id)
            ->select('name', 'parent_zip_name as parentZipName', 'expires_at as expiresAt')
            ->orderBy('created_at', 'desc')
            ->get();

        return $files;
    }
}