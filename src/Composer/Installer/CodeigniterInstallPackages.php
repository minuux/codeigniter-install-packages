<?php

namespace Composer\Installer;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;

class CodeigniterInstallerPackages extends LibraryInstaller{

    public function getInstallPath(PackageInterface $package){
        throw new \InvalidArgumentException("Package install path ${parent::getInstallPath($package)}");
        return parent::getInstallPath($package);
    }

}
