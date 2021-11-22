<?php

namespace App\Libraries;

class AutoVersion {

  public static function asset($asset) {
    $pathToAsset = public_path() . DIRECTORY_SEPARATOR . $asset;
    $versionSuffix = self::versionByModifiedTime($pathToAsset);

    return url($asset . ($versionSuffix ? '?' . $versionSuffix : ''));
  }

  public static function route($route) {
    $route = route($route);

    return $route . '?' . time();
  }

  private static function versionByModifiedTime($pathToAsset) {
    return file_exists($pathToAsset) ? filemtime($pathToAsset) : false;
  }

}
