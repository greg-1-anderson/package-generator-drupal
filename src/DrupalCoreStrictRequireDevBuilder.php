<?php

namespace PackageGeneratorDrupal;

class DrupalCoreStrictRequireDevBuilder extends DrupalPackageBuilder {

  public function getPackage() {
    $composer =  $this->config['composer']['metadata'];

    // Always require "drupal/core": "self.version"
    $composer['require']['drupal/core'] = 'self.version';

    // Use only one of drupal/core-recommended-dev-dependencies or
    // drupal/core-dev-dependencies.
    $composer['conflict']['drupal/core-dev-dependencies'] = '*';

    // Pull the exact versions of the dependencies from the composer.lock
    // file and use it to build our 'require' section.
    if (isset($this->composerLock['packages-dev'])) {
      foreach ($this->composerLock['packages-dev'] as $package) {
        $composer['require'][$package['name']] = $this->packageToVersion($package);
      }
    }

    return $composer;
  }

}
