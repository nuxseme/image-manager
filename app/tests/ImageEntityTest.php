<?php
/**
 * *
 *  * @link http://www.tomtop.com/
 *  * @copyright Copyright (c) 2016 TOMTOP
 *  * @license http://www.tomtop.com/license/
 *
 */

namespace app\tests;

use app\models\image\ImageEntity;

/**
 * Class ImageEntityTest
 * @package app\tests
 */
class ImageEntityTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @return ImageEntity
     */
    public function testNewImageEntity()
    {
        $file = new ImageEntity(__DIR__.'/source/cat.png');
        $this->assertAttributeEquals(220109,'size',$file);
        $this->assertAttributeEquals(__DIR__.'/source/cat.png','file',$file);
        $this->assertAttributeEquals(__DIR__.'/source','dir',$file);
        $this->assertAttributeEquals('image/png','mime',$file);
        $this->assertAttributeEquals(500,'width',$file);
        $this->assertAttributeEquals(412,'height',$file);
        return $file;
    }

    /**
     * @param ImageEntity $file
     * @depends testNewImageEntity
     */
    public function testFormatSize(ImageEntity $file)
    {
        $this->assertEquals('214.95 KB',$file->formatSize());
    }


    /**
     * @param ImageEntity $file
     * @depends testNewImageEntity
     */
    public function testGetContent(ImageEntity $file)
    {
        $this->assertNotEmpty($file->getContent());
    }


    /**
     * @param ImageEntity $file
     * @depends testNewImageEntity
     */
    public function testGetFile(ImageEntity $file)
    {
        $this->assertFileEquals(__DIR__.'/source/cat.png', $file->getFile());
    }

    /**
     * @param ImageEntity $file
     * @depends testNewImageEntity
     */
    public function testGetWidth(ImageEntity $file)
    {
        $this->assertEquals(500,$file->getWidth());
    }

    /**
     * @param ImageEntity $file
     * @depends testNewImageEntity
     */
    public function testGetHeight(ImageEntity $file)
    {
        $this->assertEquals(412,$file->getHeight());
    }

    /**
     * @param ImageEntity $file
     * @depends testNewImageEntity
     */
    public function testGetMime(ImageEntity $file)
    {
        $this->assertEquals('image/png',$file->getMime());
    }

    /**
     * @param ImageEntity $file
     * @depends testNewImageEntity
     */
    public function testGetDir(ImageEntity $file)
    {
        $this->assertEquals(__DIR__.'/source',$file->getDir());
    }

    /**
     * @param ImageEntity $file
     * @depends testNewImageEntity
     */
    public function testGetSize(ImageEntity $file)
    {
       $this->assertEquals(220109,$file->getSize());
    }

    /**
     * @param ImageEntity $file
     * @depends testNewImageEntity
     */
    public function testAdapt(ImageEntity $file)
    {
        $this->assertEquals(['width' => 100, 'height' => 82],$file->adapt(100,100));
    }

}