<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

$this->title = $name;
$this->context->layout = false; //不使用布局
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>通淘国际有限公司</title>
    <style type="text/css">
        body {
            padding: 0;
            margin: 0 auto;
            background-color: #fbfbfb;
        }

        #footer {
            height: 40px;
            line-height: 40px;
            position: fixed;
            bottom: 0;
            width: 100%;
            text-align: center;
            background: #333;
            color: #fff;
            font-family: Arial;
            font-size: 12px;
            letter-spacing: 1px;
        }

        .content {
            height: 1800px;
            width: 100%;
            text-align: center;
        }
    </style>
</head>
<body>
<!--
    Auther: @tomtop.Inc
-->
<p style="text-align:center"><img src="<?=Yii::$app->request->getHostInfo()?>/404-error-page-design-6.jpg" /></p>
<div id="footer">CopyRight @copy2004 ~ 2016 通淘国际技有限公司 </div>
</body>
</html>

