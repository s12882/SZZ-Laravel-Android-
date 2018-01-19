<?php

namespace App\Logic;

use File;
use Illuminate\Support\Facades\Log;

class FileHandler {

    private $filesFolder;
    private $fullFilesFolder;
    private $file;
    private $fullPath;
    private $fileName;

    public function __construct($files_folder) {
        $this->fullFilesFolder = public_path() . $files_folder;
        $this->filesFolder = $files_folder;
        $this->catalogExists();
    }

    public function setAndGetFilename($file) {
        $this->file = $file;
        $this->fileName = uniqid().'_'.$file->getClientOriginalName();
        $this->fullPath = $this->fullFilesFolder . '/' . $this->fileName;
        $this->deleteIfExists();
        return $this->fileName;
    }
    
    public function getFileInfoForTask($task_id)
    {
        return [
          'file_name' => $this->fileName,
          'original_file_name' => $this->file->getClientOriginalName(),
          'type' => $this->file->getMimeType(),
          'size' => $this->file->getSize()/1000,
          'task_id' => $task_id,
          'uploaded_by' => \Auth::id()
        ];
    }

    public function save() {
        $result = false;
        
        if(isset($this->file) == false) 
            return true;
        
        try {
            $this->file->move($this->fullFilesFolder, $this->fileName);
            $result = true;
        } catch (Exception $ex) {
            Log::warning($ex->getMessage);
        }

        return $result;
    }

    private function catalogExists() {
        if (File::exists($this->fullFilesFolder) == false) {
            File::makeDirectory($this->fullFilesFolder,true);
        }
    }

    private function deleteIfExists() {
        if (File::exists($this->fullPath)) {
            try {
                File::delete($this->fullPath);
            } catch (Exception $ex) {
                Log::warning($ex->getMessage);
            }
        }
    }

    public function deleteFile($filePath) {
        $path = public_path() . $filePath;

        if (File::exists($path)) {
            File::delete($path);
        }
    }
    
    public function deleteFileByName($fileName) {
        $path = $this->fullFilesFolder . '/' . $fileName;

        if (File::exists($path)) {
            File::delete($path);
        }
    }    
}
