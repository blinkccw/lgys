<?php

namespace backend\models;
use yii\base\Model;
use yii\web\UploadedFile;
use Yii;
/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of UploadForm
 *
 * @author xiaojx
 */
class UploadForm extends Model{
    /**
     * @var UploadedFile
     */
    public $upFile;
    public $upImage;
    public $upCompressed;
    public $upFlash;
    public $upVideo;
    public $upWxfile;

    public function rules()
    {
        return [
            [['upImage'], 'file', 'skipOnEmpty' => true,'maxSize' => 2*1024*1024],
            [['upFile'], 'file', 'skipOnEmpty' => true,'maxSize' => 20*1024*1024],
            [['upWxfile'], 'file', 'skipOnEmpty' => true,'maxSize' => 20*1024*1024],
            [['upCompressed'], 'file', 'skipOnEmpty' => true,'maxSize' => 5*1024*1024],
            [['upFlash'], 'file', 'skipOnEmpty' => true,'maxSize' => 5*1024*1024],
            [['upVideo'], 'file', 'skipOnEmpty' => true,'maxSize' => 100*1024*1024],
        ];
    }
    
}
