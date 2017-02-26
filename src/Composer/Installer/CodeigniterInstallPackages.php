<?php

namespace Composer\Installer;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class CodeigniterInstallPackages extends LibraryInstaller{

	public function supports($packageType){
		return in_array($packageType, array('codeigniter-view','codeigniter-model','codeigniter-library','codeigniter-helper'));
	}

    protected function installCode(PackageInterface $package)
	{
		parent::installCode($package);
		$this->postInstallActions($package);
	}

	protected function postInstallActions($package){
		$extra = $package->getExtra();

		if(!isset($extra['main'])){
			throw new \InvalidArgumentException("extra's main is require");
		}
		if(in_array($package->getType(),array('codeigniter-model','codeigniter-library')) && !isset($extra['class_name'])){
			throw new \InvalidArgumentException("extra's class_name is require");
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
		$config[$package->getPrettyName()]=array(
			'path'=>$this->getInstallPath($package).'/'.$extra['main'],
			'type'=>$package->getType(),
			'class_name'=>$extra['class_name']
		);
    	
    	$str_tmp="<?php\r\n"; //得到php的起始符。$str_tmp将累加
		$str_tmp.="defined('BASEPATH') OR exit('No direct script access allowed');\r\n";
		//http://stackoverflow.com/questions/18342477/save-array-to-php-file
		file_put_contents($packagePath,$str_tmp.'$config='. var_export($config, true) . ';');
	}

}
