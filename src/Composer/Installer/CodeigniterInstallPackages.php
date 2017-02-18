<?php

namespace Composer\Installer;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class CodeigniterInstallPackages extends LibraryInstaller{

    protected function installCode(PackageInterface $package)
	{
		parent::installCode($package);
		$this->postInstallActions($package);
	}

	protected function postInstallActions($package){

		if(!in_array($package->getType(),array('codeigniter-view','codeigniter-model','codeigniter-library','codeigniter-help'))){
			return;
		}
		$prefix=rtrim($package->getType(),'codeigniter-');
		$configPath=dirname(rtrim($this->composer->getConfig()->get('vendor-dir'), '/')).'/application/config/';
    	$packagePath=$configPath.'packages.php';
    	// @HACK to work around the security check in CI config files
    	if ( ! defined('BASEPATH')){
			define('BASEPATH', 1);
		}
    	
    	if (file_exists($packagePath)){
			@include($packagePath);
		}else{
			$config=array();
		}
		$config[$package->getPrettyName()]=parent::getInstallPath($package);
    	
    	$str_tmp="<?php\r\n"; //得到php的起始符。$str_tmp将累加
		$str_tmp.="defined('BASEPATH') OR exit('No direct script access allowed');\r\n";
		foreach ($config as $key => $value) {
			$str_tmp.="\$config['$prefix']['$key']='$value';\r\n";
		}
		file_put_contents($packagePath,$str_tmp);
	}

}
