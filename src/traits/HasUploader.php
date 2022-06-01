<?php

namespace Plusemon\Uploader\traits;

/**
 * Name     :   Easy File Upload and View Helpers
 * Author   :   Emon Khan
 * Date     :   12/05/2022
 */
trait HasUploader
{
    /**
     * Generate the url for specific file related to the model
     * 
     * @param string $property_name
     * @param string $type
     * @return string
     * 
     */
    public function urlOf($property_name)
    {
        if ($this->$property_name and file_exists(public_path($this->$property_name))) {
            return asset($this->$property_name);
        }
        return '';
    }

    /**
     * Upload under a specific model
     * 
     * @param string $request_input_field_name
     * @param string $type
     * @return \App\Models\User
     * 
     */
    public function uploadFromRequest($request_input_field_name, $file_type = 'images')
    {
        if (request()->hasFile($request_input_field_name)) {
            $file = request()->file($request_input_field_name);
            $module_name =  $module ?? $this->getTable();
            $unique_id = $this->id ?? uniqid();

            $file_name = "{$module_name}-{$unique_id}-{$request_input_field_name}.{$file->extension()}";
            $dir = "uploads/{$module_name}/{$file_type}/";

            $saved = $file->move(public_path($dir), $file_name);
            $file_path = $dir . $file_name;

            if ($saved) {
                $this->$request_input_field_name = $file_path;
            }
        }
        return $this;
    }
}
