<?php

namespace common\extensions;

use yii\web\AssetBundle;

class ExportMenuAsset extends AssetBundle
{
    public $js = [
        'js/export-menu.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset'
    ];
    public function init()
    {
        $this->sourcePath = __DIR__ . '/assets';
        parent::init();
    }
}