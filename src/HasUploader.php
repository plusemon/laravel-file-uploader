<?php

namespace Plusemon\Uploader\traits;

use Illuminate\Http\UploadedFile;

/**
 * Name     :   Easy File Upload and View Helpers
 * Author   :   Emon Khan
 * Date     :   12/05/2022
 */
trait HasUploader
{

    /**
     * Uploaded files paths as an array.
     * 
     * @var array
     * 
     */
    public $uploadedPaths = [];


    /**
     * Request file input file field name
     * 
     * @var string
     * 
     */
    public $requestFileField;

    /**
     * Generate the url for specific file related to the model
     * 
     * @param string $column
     * @param string $type
     * @return string
     * 
     */
    public function urlOf($column)
    {
        if ($this->$column and is_file(public_path($this->$column))) {
            return asset($this->$column);
        }
        return null;
    }

    /**
     * Check if file exist
     * 
     * @param string $path
     * @return bool
     * 
     */
    public function checkFileExists($column)
    {
        return is_file(public_path($this->$column));
    }


    /**
     * Upload multiple files
     * 
     * @param array $files
     * @param string $file_type
     * @return array
     * 
     */
    public function uploadFiles(array $files)
    {
        foreach ($files as $file) {
            $this->upload($file);
        }
        return  $this;
    }

    /**
     * Upload multiple Request files
     * 
     * @param array $files
     * @param string $file_type
     * @return $this
     * 
     */
    public function uploadRequestFiles($input_file_array)
    {
        $files = request()->file($input_file_array) ?? [];
        $this->uploadFiles($files);
        return $this;
    }

    /**
     * Upload under a specific model from the request files
     * 
     * @param string $request_file_field_name
     * @param string $type
     * @return \App\Models\User
     * 
     */
    public function uploadRequestFile($request_file_field_name)
    {
        $this->requestFileField = $request_file_field_name;

        if (request()->hasFile($request_file_field_name)) {
            $file = request()->file($request_file_field_name);
            $this->requestFileField =  $this->upload($file);
        }
        return $this;
    }

    public function upload(UploadedFile $file)
    {
        $module_name =  $this->getTable();
        $unique_id = uniqid();
        $file_name = "{$module_name}-{$unique_id}.{$file->extension()}";
        $dir = "uploads/{$module_name}/";
        $file_path = $dir . $file_name;
        $file->move(public_path($dir), $file_name);
        array_push($this->uploadedPaths, $file_path);
        return $this;
    }

    /**
     * Save the particular file path into the model property
     * 
     * @param string $column
     * @return bool
     * 
     */
    public function saveInto($column = null, $saveAsArray = false)
    {
        if (count($this->uploadedPaths)) {

            if (is_array($this->uploadedPaths)) {
                
            }

            $this->$column = is_array($this->uploadedPaths) ? $this->uploadedPaths : $this->uploadedPaths[0];
            return  $this->save();
        }
        return false;
    }

    /**
     * Save the particular files path into the model property
     * 
     * @param string $column
     * @return bool
     * 
     */
    public function saveAsArray($column)
    {
        if (count($this->uploadedPaths)) {
            $this->$column = $recFileis->uploadedPaths;
            return  $this->recFileave();
        }
        return false;
    }


    /**
     * Get all the uploaded files path as array
     * 
     * @return array
     * 
     */
    public function getUploadedFiles()
    {
        return $this->requestFileField;
    }

    /**
     * Remove the specified resource from storage according to the model.
     * 
     * @param string $column
     * @return bool
     * 
     */
    public function deleteWith($column): bool
    {
        $file = public_path($this->$column);
        if (is_file($file)) {
            unlink($file);
        }
        return $this->delete();
    }
}
