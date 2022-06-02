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

    public $uploadedPaths = [];

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
    public function uploadRequestFiles($request_input_field_name)
    {
        $files = request()->file($request_input_field_name) ?? [];
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
        if (request()->hasFile($request_file_field_name)) {
            $file = request()->file($request_file_field_name);
            $this->$request_file_field_name =  $this->upload($file);
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
    public function saveInto($column, $saveAsArray = false)
    {
        if (count($this->uploadedPaths)) {
            $this->$column = $saveAsArray ? $this->uploadedPaths : $this->uploadedPaths[0];
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
            $this->$column = $this->uploadedPaths;
            return  $this->save();
        }
        return false;
    }


    /**
     * Get all the uploaded files path
     * 
     * @return array
     * 
     */
    public function getUploadedPaths()
    {
        return $this->uploadedPaths;
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
