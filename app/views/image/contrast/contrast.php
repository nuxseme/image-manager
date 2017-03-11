<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
</head>
<body>
<div>
    <form  onsubmit="return false;">
    <p>配置选项</p>
    <table>
        <tr>
            <td>原图</td>
            <td><input type="text" name="filename" value="<?=$filename?>" id="filename"></td>
        </tr>
        <tr>
            <td>等比缩略</td>
            <td>
                是<input type="radio" name="adapt" value="1" checked>
                否<input type="radio" name="adapt" value="0">
            </td>
        </tr>
        <tr>
            <td>宽</td>
            <td>
                <input type="text" name="width" value="" id="width">
                等比缩略：请指定宽或者高，同时指定宽高将会自适应缩略
            </td>

        </tr>
        <tr>
            <td>高</td>
            <td>
                <input type="text" name="height" value="" id="height">
                非等比缩略：请同时指定宽高。宽高都不指定按原图缩略。
            </td>
        </tr>


        <tr>
            <td>压缩质量比</td>
            <td>
                起始<input type="text" name="begin_quality" value="50" id="begin_quality">
                间隔<input type="text" name="step" value="5" id="step">
            </td>
        </tr>
    </table>
        <input type="submit" value="提交" id="submit">
    </form>
</div>
<div>
    <p> 原图属性：</p>
    <img src="<?=$url?>">
    <p>宽x高: <?=$width?>x<?=$height?> </p>
    <p>大小: <?=$size?> </p>
    <p>url: <?=$url?> </p>

</div>
<div id="contrast">

</div>

</body>
</html>
<script src="/static/js/jquery-1.11.3.min.js"></script>
<script type="text/javascript">
    $('#submit').click(function () {
        var filename = $("#filename").val();
        var width = $("#width").val();
        var height = $("#height").val();
        var adapt = $('input[name="adapt"]:checked ').val();
        var begin_quality = $("#begin_quality").val();
        var step = $("#step").val();

        var data = {
            'filename':filename,
            'width':width,
            'height':height,
            'adapt':adapt,
            'begin_quality':begin_quality,
            'step':step
        };
        $.ajax({
            url:'/image/contrast/handle',
            data:data,
            type:'post',
            success:function (response) {
                var response = JSON.parse(response);
                if(response.success) {
                    $('#contrast').empty().append(response.html);
                }else{
                    alert(response.message);
                }
            }

        });
    });


</script>

