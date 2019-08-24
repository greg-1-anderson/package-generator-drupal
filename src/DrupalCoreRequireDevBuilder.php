<?php

namespace PackageGeneratorDrupal;

use Gitonomy\Git\Reference\Branch;
use Gitonomy\Git\Reference\Tag;

class DrupalCoreRequireDevBuilder extends DrupalPackageBuilder {

  public function getPackage() {
    $composer =  $this->config['composer']['metadata'] + ['require' => []];

    // The relevant require-dev constraints are stored in core/composer.json until Drupal 8.8.x,
    // where they moved to the root composer.json.
    foreach (['/core/composer.json', '/composer.json'] as $composerJsonPath) {
      $path = $this->gitObject->getRepository()
          ->getPath() . $composerJsonPath;
      if (file_exists($path)) {
        $composerJsonData = json_decode(file_get_contents($path), TRUE);
        $composer['require'] += isset($composerJsonData['require-dev']) ? $composerJsonData['require-dev'] : [];
      }
    }

    return $composer;
  }

}
