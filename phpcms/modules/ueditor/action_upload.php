<?php
/**
 * 上传附件和上传视频
 * User: Jinqn
 * Date: 14-04-09
 * Time: 上午10:17
 */
include "Uploader.class.php";

/* 上传配置 */
$base64 = "upload";
switch (htmlspecialchars($_GET['action'])) {
    case 'uploadimage':
        $config = array(
            "pathFormat" => $this->CONFIG['imagePathFormat'],
            "maxSize" => $this->CONFIG['imageMaxSize'],
            "allowFiles" => $this->CONFIG['imageAllowFiles']
        );
        $fieldName = $this->CONFIG['imageFieldName'];
        break;
    case 'uploadscrawl':
        $config = array(
            "pathFormat" => $this->CONFIG['scrawlPathFormat'],
            "maxSize" => $this->CONFIG['scrawlMaxSize'],
            "allowFiles" => $this->CONFIG['scrawlAllowFiles'],
            "oriName" => "scrawl.png"
        );
        $fieldName = $this->CONFIG['scrawlFieldName'];
        $base64 = "base64";
        break;
    case 'uploadvideo':
        $config = array(
            "pathFormat" => $this->CONFIG['videoPathFormat'],
            "maxSize" => $this->CONFIG['videoMaxSize'],
            "allowFiles" => $this->CONFIG['videoAllowFiles']
        );
        $fieldName = $this->CONFIG['videoFieldName'];
        break;
    case 'uploadfile':
    default:
        $config = array(
            "pathFormat" => $this->CONFIG['filePathFormat'],
            "maxSize" => $this->CONFIG['fileMaxSize'],
            "allowFiles" => $this->CONFIG['fileAllowFiles']
        );
        $fieldName = $this->CONFIG['fileFieldName'];
        break;
}

/* 生成上传实例对象并完成上传 */
$up = new Uploader($fieldName, $config, $base64);

/**
 * 得到上传文件所对应的各个参数,数组结构
 * array(
 *     "state" => "",          //上传状态，上传成功时必须返回"SUCCESS"
 *     "url" => "",            //返回的地址
 *     "title" => "",          //新文件名
 *     "original" => "",       //原始文件名
 *     "type" => ""            //文件类型
 *     "size" => "",           //文件大小
 * )
 */

/* 返回数据 */
return json_encode($up->getFileInfo());
