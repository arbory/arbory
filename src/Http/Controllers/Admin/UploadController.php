<?php

namespace Arbory\Base\Http\Controllers\Admin;

use UniSharp\LaravelFilemanager\Controllers\UploadController as LfmUploadController;

/**
 * Class UploadController.
 */
class UploadController extends LfmUploadController
{
    /**
     * Upload files.
     *
     * @param void
     * @return string
     */
    public function upload()
    {
        $response = parent::upload();

        return count($this->errors) > 0 ? $this->errorResponse() : $response;
    }

    /**
     * Laravel File Manager and CKEditor integration is broken. In cases of unsuccessful uploads,
     * LFM returns error responses of unexpected format. This fixes that broken behaviour.
     *
     * @return array
     */
    protected function errorResponse()
    {
        if (request('responseType') === 'json') {
            return [
                'uploaded' => 0,
                'error' => [
                    'message' => implode(",\n", $this->errors),
                ],
            ];
        } else {
            return $this->errors;
        }
    }
}
