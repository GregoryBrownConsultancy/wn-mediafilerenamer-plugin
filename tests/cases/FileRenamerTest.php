<?php namespace Gbc\MediaFileRenamer\Tests\Cases;

use System\Tests\Bootstrap\TestCase;
use Gbc\MediaFileRenamer\Services\FileRenamer;
class FileRenamerTest extends TestCase
{
  /**
   * Tests whether the FileRenamer class can be instantiated.
   *
   * @return void
   */
  public function testFileRenamerIsAccessible(): void
  {
    $fileRenamer = new FileRenamer();

    $this->assertTrue($fileRenamer instanceof FileRenamer);
  }
  /**
   * Tests whether the FileRenamer class can receive a filename
   * and slug it correctly.
   *
   * @return void
   */
  public function testShouldReceiveFilenameAndSlugIt(): void
  {
    $fullPath = ("tESt case.tmp");
    $slug = ("test-case.tmp");
    $fileRenamer = new FileRenamer($fullPath, $this->getFixturePath());
    $newFile = $fileRenamer->call();
    $this->assertEquals($newFile, $slug);
  }
  /**
   * Tests whether the FileRenamer class generates a unique filename.
   * If the pattern "--N" is found, iterate over indexes util a unique
   * filename is found for save.
   *
   * @return void
   */
  public function testUseAUniqueFileName(): void
  {
    $fullPath = ("test-case--1.tmp");
    $slug = ("test-case--3.tmp");
    $fileRenamer = new FileRenamer($fullPath, $this->getFixturePath());
    $newFile = $fileRenamer->call();
    $this->assertEquals($newFile, $slug);
  }

  private function getFixturePath(): string
  {
    $basePath = base_path('plugins/gbc/mediafilerenamer');
    $fullPath = $basePath."/tests/fixtures";
    return $fullPath;
  }

}
