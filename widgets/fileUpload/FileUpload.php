<?php

namespace app\widgets\fileUpload;
use dosamigos\fileupload\FileUploadUI;
use dosamigos\fileupload\FileUploadUIAsset;
use dosamigos\gallery\GalleryAsset;
use yii\helpers\Json;


class FileUpload extends FileUploadUI
{

    /**
     * Registers required script for the plugin to work as jQuery File Uploader UI
     */
    public function registerClientScript()
    {

        $this->load = true;

        $view = $this->getView();

        if ($this->gallery) {
            GalleryAsset::register($view);
        }

        FileUploadUIAsset::register($view);

        $options = Json::encode($this->clientOptions);
        $id = $this->options['id'];

        $js[] = ";jQuery('#$id').fileupload($options);";
        if (!empty($this->clientEvents)) {
            foreach ($this->clientEvents as $event => $handler) {
                $js[] = "jQuery('#$id').on('$event', $handler);";
            }
        }
        $view->registerJs(implode("\n", $js));

        if ($this->load) {
            $view->registerJs("
                $('#$id').addClass('fileupload-processing');
                $.ajax({
                    url: $('#$id').fileupload('option', 'url'),
                    dataType: 'json',
                    context: $('#$id')[0]
                }).always(function () {
                    $(this).removeClass('fileupload-processing');
                }).done(function (result) {
                    $(this).fileupload('option', 'done').call(this, $.Event('done'), {result: result});
                });
            ");
        }
    }

}