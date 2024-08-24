<?php

namespace Gbc\MediaFileRenamer;

use Gbc\MediaFileRenamer\Services\FileRenamer;
use System\Classes\MediaLibrary;
use System\Classes\PluginBase;
use Winter\Storm\Support\Facades\Config;
use Winter\Storm\Support\Facades\Event;

/**
 * MediaManagerFix Plugin Information File
 */
class Plugin extends PluginBase
{
  /**
   * Register method, called when the plugin is first registered.
   *
   * @return void
   */
  public function register() {}

  /**
   * Boot method, called right before the request route.
   *
   * @return array
   */
  public function boot()
  {
    Event::listen('media.file.upload', function ($widget, $filePath, $uploadedFile) {
      $this->fixFileName($widget, $filePath, $uploadedFile);
    });
  }

  /**
   * The logic required for fixing filenames and calling the service
   */
  private function fixFileName($widget, $filePath, $uploadedFile): void
  {
    $storageFolder = rtrim(Config::get('cms.storage.media.path', '/storage/app/media'), '/');
    $storageFolder = '/media/';
    $relativeFile = str_replace($storageFolder, '', $filePath);
    $fileRenamer = new FileRenamer($relativeFile);
    $newPath = $fileRenamer->call();
    MediaLibrary::instance()->moveFile($relativeFile, $newPath);
  }
}
