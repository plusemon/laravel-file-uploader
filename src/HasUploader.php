<?php

namespace Plusemon\Uploader;

use Illuminate\Http\UploadedFile;
use Intervention\Image\Facades\Image;

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
    public $uploaded_files = [];


    /**
     * name of the model.
     * 
     * @var array
     * 
     */
    public $model_name;


    /**
     * Request file input file field name
     * 
     * @var string
     * 
     */
    public $request_input_field;

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
    public function isFile($column)
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
    public function uploadRequestFiles($request_input_field)
    {
        $files = request()->file($request_input_field) ?? [];
        if ($files) {
            $this->request_input_field = $request_input_field;
            $this->uploadFiles($files);
        }
        return $this;
    }

    /**
     * Upload under a specific model from the request files
     * 
     * @param string $request_file_field_name
     * @param string $type
     * @return \Plusemon\Uploader\HasUploader
     * 
     */
    public function uploadRequestFile($request_input_field)
    {
        if (request()->hasFile($request_input_field)) {
            $this->request_input_field = $request_input_field;
            $this->upload(request()->file($request_input_field));
        }
        return $this;
    }


    /**
     * Upload a file on a a path
     * 
     * @param string $request_file_field_name
     * @param string $type
     * @return \Plusemon\Uploader\HasUploader
     * 
     */
    public function upload(UploadedFile $file, $module_name = null, $file_type = 'images', $unique_id = null)
    {
        $model_name = $model_name ?? $this->getTable();

        $this->model_name = $model_name;

        $unique_id = $unique_id ?? uniqid();

        $file_name = "{$model_name}-{$unique_id}.{$file->extension()}";
        $dir = "uploads/{$model_name}/{$file_type}/";
        $file_path = $dir . $file_name;
        $file->move(public_path($dir), $file_name);
        array_push($this->uploaded_files, $file_path);
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
        $column_name = $column ?? $this->request_input_field;
        if (count($this->uploaded_files)) {
            $this->$column_name = $saveAsArray ? $this->uploaded_files : $this->uploaded_files[0];
            return  $this->save();
        }
        return false;
    }

    /**
     * Update the particular file path into the model property
     * 
     * @param string $column
     * @return bool
     * 
     */
    public function updateInto($column = null)
    {
        $column_name = $column ?? $this->request_input_field;

        // remove old res

        $file = public_path($this->$column_name);
        if (is_file($file)) {
            unlink($file);
        }

        if (count($this->uploaded_files)) {
            $this->$column_name = $this->uploaded_files[0];
            return  $this->save();
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
        return $this->uploaded_files;
    }

    /**
     * Remove the specified resource from storage according of the model.
     * 
     * @param string $column
     * @return \Plusemon\Uploader\HasUploader
     * 
     */
    public function deleteFile($column)
    {
        $file = public_path($this->$column);
        if (is_file($file)) return unlink($file);
        return false;
    }

    /**
     * Remove the specified resource from storage according to the model.
     * 
     * @param string $column
     * @return bool
     * 
     */
    public function deleteWithFile($column): bool
    {
        $this->deleteFile($column);
        return $this->delete();
    }


    // alieses

    /**
     * Remove the specified resource from storage according to the model.
     * 
     * @param string $column
     * @return bool
     * 
     */
    public function deleteWith($column): bool
    {
        return $this->deleteWithFile($column);
    }

    /**
     * Resizes current image based on given width and/or height. To contraint the resize command, pass an optional Closure callback as third parameter.
     * 
     *  @param int $width
     * @param int $height
     * @param \Closure $callback
     * @return \Plusemon\Uploader\HasUploader
     */

    public function resize($width = null, $height = null, $callback = null)
    {
        Image::make($this->uploaded_files[0])->resize($width, $height, $callback)->save();
        return $this;
    }
}
