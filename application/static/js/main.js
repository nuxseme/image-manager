
$(function () {
    //严格模式
    'use strict';

    //初始化上传插件
    $('#fileupload').fileupload();
    //前端校验上传文件格式
    $('#fileupload').fileupload('option',{
        acceptFileTypes: /(\.|\/)(gif|jpe?g|png)$/i
    });

    //加载初始显示的加载动画
    //$('#fileupload').addClass('fileupload-processing');
    //到指定的url服务器获取缩略图片并展示 未设定分页功能
    // $.ajax({
    //     url: $('#fileupload').fileupload('option', 'url'),
    //     dataType: 'json',
    //     context: $('#fileupload')[0]
    // }).always(function () {
    //     $(this).removeClass('fileupload-processing');
    // }).done(function (result) {
    //     console.log('开始的ajax');
    //     console.log(result);
    //     $(this).fileupload('option', 'done')
    //         .call(this, $.Event('done'), {result: result});
    // });
});
