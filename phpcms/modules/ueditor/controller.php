<?php
defined('IN_PHPCMS') or exit('No permission resources.');
$session_storage = 'session_'.pc_base::load_config('system','session_storage');
pc_base::load_sys_class($session_storage);
if(param::get_cookie('sys_lang')) {
    define('SYS_STYLE',param::get_cookie('sys_lang'));
} else {
    define('SYS_STYLE','zh-cn');
}
//header('Access-Control-Allow-Origin: http://www.baidu.com'); //设置http://www.baidu.com允许跨域访问
//header('Access-Control-Allow-Headers: X-Requested-With,X_Requested_With'); //设置允许的跨域header
date_default_timezone_set("Asia/chongqing");
error_reporting(E_ERROR);
header("Content-Type: text/html; charset=utf-8");
class controller {
    var $CONFIG;
    var $action;
    function __construct($CONFIG,$action){
        $this->CONFIG = json_decode(preg_replace("/\/\*[\s\S]+?\*\//", "", file_get_contents("".CACHE_PATH."configs".DIRECTORY_SEPARATOR."config.json")), true);
        $this->action = $_GET['action'];
        //phpcms 用户会话
        $this->userid = $_SESSION['userid'] ? $_SESSION['userid'] : (param::get_cookie('_userid') ? param::get_cookie('_userid') : sys_auth($_POST['userid_flash'],'DECODE'));
        $this->isadmin = $this->admin_username = $_SESSION['roleid'] ? 1 : 0;
        //判断是否登录
        if(empty($this->userid)){
            showmessage(L('please_login','','member'));
        }
    }
    public function upload(){
        //判断用户权限
        $grouplist = getcache('grouplist','member');
        if($this->isadmin==0 && !$grouplist[$this->groupid]['allowattachment']) return false;

        switch ($this->action) {
            case 'config':
                $result =  json_encode($this->CONFIG);
                break;

            /* 上传图片 */
            case 'uploadimage':
            /* 上传涂鸦 */
            case 'uploadscrawl':
            /* 上传视频 */
            case 'uploadvideo':
            /* 上传文件 */
            case 'uploadfile':
                $result = include("action_upload.php");
                break;

            /* 列出图片 */
            case 'listimage':
                $result = include("action_list.php");
                break;
            /* 列出文件 */
            case 'listfile':
                $result = include("action_list.php");
                break;

            /* 抓取远程文件 */
            case 'catchimage':
                $result = include("action_crawler.php");
                break;

            default:
                $result = json_encode(array(
                    'state'=> '请求地址出错'
                ));
                break;
        }

        /* 输出结果 */
        if (isset($_GET["callback"])) {
            if (preg_match("/^[\w_]+$/", $_GET["callback"])) {
                echo htmlspecialchars($_GET["callback"]) . '(' . $result . ')';
            } else {
                echo json_encode(array(
                    'state'=> 'callback参数不合法'
                ));
            }
        } else {
            echo $result;
        }
    }
}