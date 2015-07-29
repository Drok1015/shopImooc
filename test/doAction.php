<?php 
require_once '../lib/string.func.php';
header("content-type:text/html;charset=utf-8");
//$_FILES
$filename=$_FILES['myFile']['name'];
$type=$_FILES['myFile']['type'];
$tmp_name=$_FILES['myFile']['tmp_name'];
$error=$_FILES['myFile']['error'];
$size=$_FILES['myFile']['size'];
$allowExt=array("gif","jpeg","jpg","png","wbmp");
$maxSize=1512000;
$imgFlag=true;
//判断下错误信息
if($error==UPLOAD_ERR_OK){
	$ext=getExt($filename);
	//限制上传文件类型
	if(!in_array($ext,$allowExt)){
		exit ("非法文件类型");
	}
	if($size>$maxSize){
		exit ("文件过大");
	}
	if($imgFlag){
		//如何验证图片是否是一个真正的图片类型
		//getimagesize($filename):验证文件是否是图片类型
		$info=getimagesize($tmp_name);
		//var_dump($info);exit;
		if(!$info){
			exit("不是真正的图片类型");
		}
	}
	//需要判断下文件是否是通过HTTP POST方式上传上来的
	//is_uploaded_file($tmp_name):
	
	$filename=getUniName().".".$ext;
	$path="uploads";
	if(!file_exists($path)){
		mkdir($path,0777,true);
	}
	$destination=$path."/".$filename;
	if(is_uploaded_file($tmp_name)){
		if(move_uploaded_file($tmp_name, $destination)){
			$mes="文件上传成功";
		}else{
			$mes="文件移动失败";
		}
	}else{
		$mes="文件不是通过HTTP POST方式上传上来的";
	}
}else{
	switch($error){
		case 1:
			$mes="超过了配置文件上传文件的大小";//UPLOAD_ERR_INI_SIZE
			break;
		case 2:
			$mes="超过了表单设置上传文件的大小";			//UPLOAD_ERR_FORM_SIZE
			break;
		case 3:
			$mes="文件部分被上传";//UPLOAD_ERR_PARTIAL
			break;
		case 4:
			$mes="没有文件被上传";//UPLOAD_ERR_NO_FILE
			break;
		case 6:
			$mes="没有找到临时目录";//UPLOAD_ERR_NO_TMP_DIR
			break;
		case 7:
			$mes="文件不可写";//UPLOAD_ERR_CANT_WRITE;
			break;
		case 8:
			$mes="由于PHP的扩展程序中断了文件上传";//UPLOAD_ERR_EXTENSION
			break;
			
			
			
	}
}
echo $mes;
//服务器端进行的配置
//1》file_uploads = On,支持通过HTTP POST方式上传文件
//2》;upload_tmp_dir =临时文件保存目录
//3》upload_max_filesize = 2M默认值是2M，上传的最大大小2M
//4》post_max_size = 8M，表单以POST方式发送数据的最大值，默认8M
//客户端进行配置
//<input type="hidden" name="MAX_FILE_SIZE" value="1024"  />
//<input type="file" name="myFile" accept="文件的MIME类型,..."/>







