<?php
class MY_form {
	public static function ueditor($textareaid = 'content') {
	  if(!defined('EDITOR_INIT')) {
	    $str .= '<script type="text/javascript" src="'.JS_PATH.'ueditor/ueditor.config.js"></script>';
	    $str .= '<script type="text/javascript" src="'.JS_PATH.'ueditor/ueditor.all.js"></script>';
	    define('EDITOR_INIT', 1);
	  }
	  $str .= "<script type='text/javascript'>"; 
	  $str .= 'var ue = UE.getEditor("'.$textareaid.'",{
	  	serverUrl:"'.APP_PATH.'index.php?m=ueditor&c=controller&a=upload",
			initialFrameWidth:650,
			initialFrameHeight:500,
			pasteplain:true,
			autoFloatEnabled:false
		});';
		$str .="UE.Editor.prototype._bkGetActionUrl = UE.Editor.prototype.getActionUrl;";
		$str .="UE.Editor.prototype.getActionUrl = function(action) {";
		$str .="	return '".APP_PATH."index.php?m=ueditor&c=controller&a=upload&module=".$module."&catid=".$catid."&dosubmit=1&action='+action;";
		$str .="}";
	  $str .= '</script>';
		return $str;
	}
}
?>