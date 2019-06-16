<?php

namespace PackageGeneratorDrupal;

use Gitonomy\Git\Reference\Branch;
use Gitonomy\Git\Reference\Tag;

class DrupalCoreRequireDevBuilder extends DrupalPackageBuilder {

  public function getPackage() {
    $composer =  $this->config['composer']['metadata'];
    $constraint = NULL;
    if ($this->gitObject instanceof Branch) {
      $constraint = str_replace('origin/', '', $this->gitObject->getName()) . '-dev';
    }
    elseif ($this->gitObject instanceof Tag) {
      $constraint = $this->gitObject->getName();
    }
    if ($constraint) {
      $composer['require']['drupal/core'] = $constraint;
    }

    // The relevant require-dev constraints are stored in core/composer.json.
    $path = $this->gitObject->getRepository()
        ->getPath() . '/core/composer.json';
    if (file_exists($path)) {
      $composerJsonData = json_decode(file_get_contents($path), TRUE);
      $composer['require'] += isset($composerJsonData['require-dev']) ? $composerJsonData['require-dev'] : [];
    }

    return $composer;
  }

}
