<?php
/**
 * *
 *  * @link http://www.tomtop.com/
 *  * @copyright Copyright (c) 2016 TOMTOP
 *  * @license http://www.tomtop.com/license/
 *
 */

namespace app\models\image;

use app\exception\ImageNotFound;
use app\models\foundation\Entity;

/**
 * Class ImageEntity
 * @package app\models\image
 */
class ImageEntity extends Entity
{
    /**
     * 文件句柄
     * @var
     */
    private $file;
    /**
     * 文件目录
     * @var
     */
    private $dir;
    /**
     * 图片宽
     * @var
     */
    private $width;
    /**
     * 图片高
     * @var
     */
    private $height;

    /**
     * 图片类型
     * @var
     */
    private $mime;
    /**
     * 图片大小
     * @var
     */
    private $size;

    /**
     * 图片最后修改时间
     * @var
     */
    private $last_modify_time;

    /**
     * 构造函数 初始化文件
     * ImageEntity constructor.
     * @param $file
     */
    public function __construct($file)
    {
        $this->file = $file;
        //初始化参数
        $this->init();
    }

    /**
     * 初始化图片参数
     * @throws ImageNotFound
     */
    private function init()
    {
        if (!$this->isValidate()) {
            throw new ImageNotFound("[$this->file] not found");
        }
        $this->dir    = dirname($this->file);
        $info   = getimagesize($this->file);
        $this->width  = $info[0];
        $this->height = $info[1];
        $this->mime   = $info['mime'];
        $this->size   = filesize($this->file);
        $this->last_modify_time = filemtime($this->file);
    }

    /**
     * 字节数转换成带单位的 digits，要保留几位小数
     * @param int $digits
     * @return string
     */
    public function formatSize($digits = 2)
    {
        $unit = ['', 'K', 'M', 'G', 'T', 'P'];
        $base = 1024;
        $size = filesize($this->file);
        $i    = floor(log($size, $base));
        return round($size / pow($base, $i), $digits) . ' ' . $unit[$i] . 'B';
    }

    /**
     * 验证图片是否存在
     * @return bool
     */
    private function isValidate()
    {
        return is_file($this->file);
    }


    /**
     *下载
     */
    public function download()
    {
        header('Content-Description: File Transfer');
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($this->file) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . $this->size);
        readfile($this->file);
    }

    /**
     * 获取图片数据
     * @return string
     */
    public function getContent()
    {
        return file_get_contents($this->file);
    }

    /**
     * 显示
     */
    public function show()
    {
        switch ($this->mime) {
            case 'image/jpeg':
                header('Content-type: image/jpeg');
                readfile($this->file);
                break;
            case 'image/png':
                header('Content-type: image/png');
                readfile($this->file);
                break;
            case 'image/gif':
                header('Content-type: image/gif');
                readfile($this->file);
                break;
        }
        exit();
    }

    /**
     * 删除图片
     * @return bool
     */
    public function delete()
    {
        return unlink($this->file);
    }

    /**
     * @return mixed
     */
    public function getInfo()
    {
        $info = [
            'width' => $this->getWidth(),
            'height' => $this->getHeight(),
            'mime' => $this->getMime(),
            'size' => $this->getSize(),
            'format_size' => $this->formatSize(),
            'last_modify_time' => $this->getLastModifyTime(),
            'path' => $this->file
        ];

        return $info;
    }

    /**
     * @return mixed
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * @return mixed
     */
    public function getWidth()
    {
        return $this->width;
    }

    /**
     * @return mixed
     */
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * @return mixed
     */
    public function getMime()
    {
        return $this->mime;
    }

    /**
     * @return mixed
     */
    public function getDir()
    {
        return $this->dir;
    }

    /**
     * @return mixed
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * @return mixed
     */
    public function getLastModifyTime()
    {
        return $this->last_modify_time;
    }

    public function adapt($w,$h)
    {
        //获取最大值
        $maxW = intval($w) > $this->width ? $this->width : $w;
        $maxH = intval($h) > $this->height ? $this->height : $h;
        //计算根据宽计算高度
        $newH = floor(($maxW/$this->getWidth())*$this->getHeight());
        $newW = $newH > $maxH ? floor(($newH/$this->getHeight())*$this->getWidth()) : $maxW;
        return [
            'width' => $newW,
            'height' => $newH,
        ];
    }
}