<?php

namespace Composer\Installer;

use Composer\Package\PackageInterface;
use Composer\Installer\LibraryInstaller;
use Composer\Repository\InstalledRepositoryInterface;

class CodeigniterInstallPackages extends LibraryInstaller{

    public function getInstallPath(PackageInterface $package){
        throw new \InvalidArgumentException("Package install path ${parent::getInstallPath($package)}");
        return parent::getInstallPath($package);
    }

}
