<?php namespace Gbc\MediaFileRenamer\Services;

use Winter\Storm\Support\Facades\Config;

/**
 * FileRenamer - Service responsible for handling all aspects about file names.
 * It checks if the file is unique and generate a new name if it's not.
 * It Slugs the file name to make it better for web browsers.
 */
class FileRenamer
{
    /**
     * @var string
     */
    private $dirName;

    /**
     * @var string
     */
    private $extension;

    /**
     * @var string
     */
    private $originalNameNoExt;

    /**
     * @var string
     */
    private $fixedFile;

    /**
     * @var string
     */
    private $storagePath;

    /**
     * FileRenamer constructor.
     *
     * @param string $relativePath
     * @param string $storagePath
     */
    public function __construct(string $relativePath = "", $storagePath = null)
    {
        $this->dirName = pathinfo($relativePath, PATHINFO_DIRNAME);
        $this->extension = pathinfo($relativePath, PATHINFO_EXTENSION);
        $this->originalNameNoExt = pathinfo($relativePath, PATHINFO_FILENAME);
        if ($storagePath !== null) {
          $this->storagePath = $storagePath;
        } else {
          $this->storagePath = $this->getStoragePath();
        }
    }

    /**
     * Calls the FileRenamer service to rename a file.
     *
     * This function generates a fixed filename by slugging the
     * original filename and adding an index if the file already
     * exists.
     *
     * @return string The fixed URI of the renamed file
     */
    public function call(): string
    {
        $this->fixedFile = $this->slugFileName($this->originalNameNoExt) . '.' . $this->extension;
        $this->addIndexIfFileExists();

        return $this->getFixedUri();
    }

    private function getStoragePath(): string
    {
      $storageRelativePath = rtrim(Config::get('cms.storage.media.path', '/storage/app/media'), '/');
      return base_path($storageRelativePath);
    }
    /**
     * @return string
     */
    private function getFixedUri(): string
    {
      if ($this->dirName !== '.') {
        return $this->dirName . '/' . $this->fixedFile;
      }
      return $this->fixedFile;
    }

    /**
     * Adds an index to the filename if the file already exists.
     *
     * This function checks if the file specified by
     * `$this->getFixedUri()` exists. If it does, it removes
     * the existing index from `$this->fixedFile` and adds a
     * new index to the filename. It continues this process
     * until a unique filename is found.
     *
     * @return void
     */
    private function addIndexIfFileExists(): void
    {
        $index = 1;
        $fullPath = $this->storagePath .'/'. $this->getFixedUri();

        while (file_exists($fullPath)) {
          $fileName = preg_replace('/--(\d+)/', '', $this->fixedFile); // remove index from $this->fixedFile
          $this->fixedFile = str_replace(
              '.' . $this->extension,
              '--' . $index++ . '.' . $this->extension,
              $fileName);
          $fullPath = $this->storagePath .'/'. $this->getFixedUri();
        }
    }

    /**
     * @param string $filename
     *
     * @return string
     */
    private function slugFileName(string $filename): string
    {
        return strtolower(preg_replace('/[^A-Za-z0-9\-]/', '-', $filename));
    }
}

