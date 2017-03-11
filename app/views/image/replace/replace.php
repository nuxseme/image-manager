<?php
/**
 * *
 *  * @link http://www.tomtop.com/
 *  * @copyright Copyright (c) 2016 TOMTOP
 *  * @license http://www.tomtop.com/license/
 *
 */

use yii\helpers\Url;
?>
<head>
    <!-- Force latest IE rendering engine or ChromeFrame if installed -->
    <!--[if IE]>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <![endif]-->
    <meta charset="utf-8">
    <title>TTIM</title>
    <!-- Bootstrap styles -->
    <link rel="stylesheet" href="/static/css/bootstrap.min.css">
    <!-- Generic page styles -->
    <link rel="stylesheet" href="/static/css/style.css">
    <!-- blueimp Gallery styles -->
    <link rel="stylesheet" href="/static/css/blueimp-gallery.min.css">
    <!-- CSS to style the file input field as button and adjust the Bootstrap progress bars -->
    <link rel="stylesheet" href="/static/css/jquery.fileupload.css">
    <link rel="stylesheet" href="/static/css/jquery.fileupload-ui.css">
    <!-- CSS adjustments for browsers with JavaScript disabled -->
    <noscript><link rel="stylesheet" href="/static/css/jquery.fileupload-noscript.css"></noscript>
    <noscript><link rel="stylesheet" href="/static/css/jquery.fileupload-ui-noscript.css"></noscript>
</head>
<body>
<div class="container">
    <!-- The file upload form used as target for the file upload widget -->
    <form id="fileupload" data-url="<?=Url::toRoute('image/replace/handle')?>" action="<?=Url::toRoute('image/replace/handle')?>" method="POST" enctype="multipart/form-data">
        <!-- The fileupload-buttonbar contains buttons to add/delete files and start/cancel the upload -->
        <div class="row fileupload-buttonbar">
            <div class="col-lg-7">
                <!-- The fileinput-button span is used to style the file input field as button -->
                <span class="btn btn-success fileinput-button">
                    <i class="glyphicon glyphicon-plus"></i>
                    <span>替换文件...</span>
                    <input type="file" name="uploadfiles[]" multiple>
                </span>
                <button type="submit" class="btn btn-primary start">
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>开始上传</span>
                </button>
                <button type="reset" class="btn btn-warning cancel" style="display:none">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>Cancel upload</span>
                </button>
                <button type="button" class="btn btn-danger delete" style="display:none">
                    <i class="glyphicon glyphicon-trash"></i>
                    <span>Delete</span>
                </button>
                <input type="checkbox" class="toggle" style="display:none">
                <!-- The global file processing state -->
                <span class="fileupload-process"></span>
            </div>
            <!-- The global progress state -->
            <div class="col-lg-5 fileupload-progress fade">
                <!-- The global progress bar -->
                <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100">
                    <div class="progress-bar progress-bar-success" style="width:0%;"></div>
                </div>
                <!-- The extended global progress state -->
                <div class="progress-extended">&nbsp;</div>
            </div>
        </div>
        <!-- The table listing the files available for upload/download -->
        <table role="presentation" class="table table-striped"><tbody class="files"></tbody></table>
    </form>
</div>
<!-- The blueimp Gallery widget -->
<div id="blueimp-gallery" class="blueimp-gallery blueimp-gallery-controls" data-filter=":even">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>
<!-- The template to display files available for upload -->
<script id="template-upload" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-upload fade">
        <td>
            <span class="preview"></span>
        </td>
        <td>
            <p class="name">{%=file.name%}</p>
            <strong class="error text-danger"></strong>
        </td>
        <td>
            <p class="size">正在上传...</p>
            <div class="progress progress-striped active" role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0"><div class="progress-bar progress-bar-success" style="width:0%;"></div></div>
        </td>
        <td>
            {% if (!i && !o.options.autoUpload) { %}
                <button class="btn btn-primary start" disabled>
                    <i class="glyphicon glyphicon-upload"></i>
                    <span>开始</span>
                </button>
            {% } %}
            {% if (!i) { %}
                <button class="btn btn-warning cancel">
                    <i class="glyphicon glyphicon-ban-circle"></i>
                    <span>取消</span>
                </button>
            {% } %}
        </td>
    </tr>
{% } %}
</script>
<!-- The template to display files available for download -->
<script id="template-download" type="text/x-tmpl">
{% for (var i=0, file; file=o.files[i]; i++) { %}
    <tr class="template-download fade">
        <td>
            <span class="preview">
                {% if (file.thumbnailUrl) { %}
                    <a href="{%=file.url%}?v={%=Math.random()%}" title="{%=file.name%}" download="{%=file.name%}" data-gallery><img src="{%=file.thumbnailUrl%}?v={%=Math.random()%}"  height="80" width="80"></a>
                {% } %}
            </span>
        </td>
        <td>
            <p class="name">
                {% if (file.url) { %}
                    <a href="{%=file.url%}?v={%=Math.random()%}" title="{%=file.name%}" download="{%=file.name%}?v={%=Math.random()%}" {%=file.thumbnailUrl?'data-gallery':''%}>{%=file.name%}</a>
                {% } else { %}
                    <span>{%=file.name%}</span>
                {% } %}
            </p>
            {% if (file.error) { %}
                <div><span class="label label-danger">错误</span> {%=file.error%}</div>
            {% } %}
        </td>
        <td>
            <span class="size">{%=o.formatFileSize(file.size)%}</span>
        </td>
         {% if (!file.error) { %}
           <td>
            <p class="name">
                <input type='text' value={%=window.location.origin+file.url%}  size=70 />
            </p>
        </td>
            {% } %}

<!--        <td>-->
<!--            {% if (file.deleteUrl) { %}-->
<!--                <button class="btn btn-danger delete" data-type="{%=file.deleteType%}" data-url="{%=file.deleteUrl%}"{% if (file.deleteWithCredentials) { %} data-xhr-fields='{"withCredentials":true}'{% } %}>-->
<!--                    <i class="glyphicon glyphicon-trash"></i>-->
<!--                    <span>Delete</span>-->
<!--                </button>-->
<!--                <input type="checkbox" name="delete" value="1" class="toggle">-->
<!--            {% } else { %}-->
<!--                <button class="btn btn-warning cancel">-->
<!--                    <i class="glyphicon glyphicon-ban-circle"></i>-->
<!--                    <span>Cancel</span>-->
<!--                </button>-->
<!--            {% } %}-->
<!--        </td>-->
    </tr>
{% } %}
</script>
<script src="/static/js/jquery-1.11.3.min.js"></script>
<!-- The jQuery UI widget factory, can be omitted if jQuery UI is already included -->
<script src="/static/js/vendor/jquery.ui.widget.js"></script>
<!-- The Templates plugin is included to render the upload/download listings -->
<script src="/static/js/vendor/tmpl.min.js"></script>
<!-- The Load Image plugin is included for the preview images and image resizing functionality -->
<script src="/static/js/vendor/load-image.all.min.js"></script>
<!-- The Canvas to Blob plugin is included for image resizing functionality -->
<script src="/static/js/vendor/canvas-to-blob.min.js"></script>
<!-- Bootstrap JS is not required, but included for the responsive demo navigation -->
<script src="/static/js/vendor/bootstrap.min.js"></script>
<!-- blueimp Gallery script -->
<script src="/static/js/vendor/jquery.blueimp-gallery.min.js"></script>
<!-- The Iframe Transport is required for browsers without support for XHR file uploads -->
<script src="/static/js/jquery.iframe-transport.js"></script>
<!-- The basic File Upload plugin -->
<script src="/static/js/jquery.fileupload.js"></script>
<!-- The File Upload processing plugin -->
<script src="/static/js/jquery.fileupload-process.js"></script>
<!-- The File Upload image preview & resize plugin -->
<script src="/static/js/jquery.fileupload-image.js"></script>
<!-- The File Upload validation plugin -->
<script src="/static/js/jquery.fileupload-validate.js"></script>
<!-- The File Upload user interface plugin -->
<script src="/static/js/jquery.fileupload-ui.js"></script>
<!-- The main application script -->
<script src="/static/js/main.js"></script>
</body>