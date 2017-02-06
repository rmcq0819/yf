<?php
class versionAction extends Action {
	
	//获取用户信息
	public function getVersion(){
		$version = M('app_version')->select();
		if(count($version)>0){
			$jsonArr['versions'] = $version;
			$jsonArr['status'] = 1;
		}else{
			$jsonArr['status'] = 0;
		}
	
		echo json_encode($jsonArr);
	}
}