<?php

namespace PackageGeneratorDrupal;

class DrupalCoreStrictBuilder extends DrupalPackageBuilder {

  public function getPackage() {
    $composer =  $this->config['composer']['metadata'];

    // Always require "drupal/core": "self.version"
    $composer['require']['drupal/core'] = 'self.version';

    // Copy the 'packages' section from the Composer lock into our 'require'
    // section. There is also a 'packages-dev' section, but we do not need
    // to pin 'require-dev' versions, as 'require-dev' dependencies are never
    // included from subprojects. Use 'drupal/core-dev-dependencies' to get
    // Drupal's dev dependencies.
    if (isset($this->composerLock['packages'])) {
      foreach ($this->composerLock['packages'] as $package) {
        $composer['require'][$package['name']] = $this->packageToVersion($package);
      }
    }

    return $composer;
  }

}
