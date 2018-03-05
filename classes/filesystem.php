<?php namespace Zollerboy\Navigation\Classes;

/**
 * Filesystem helper class
 */
class Filesystem {

	/**
	 * Create a new File at the given path with the given content.
	 * @param  string $filePath    The path, where the file will be saved.
	 * @param  array  $fileContent The content of the file, split in lines. Default = []
	 * @return void
	 */
	public function createFile(string $filePath, array $fileContent = []) {
		$file = fopen($filePath, "w");
		fwrite($file, implode($fileContent));
		fclose($file);
	}

	/**
	 * Rename a file at the given path with the given new path and edit the content with the replaceRegex.
	 * @param  string $filePath     The path, where the file was stored.
	 * @param  string $newFilePath  The path, where the file will be stored.
	 * @param  array  $replaceRegex An array, where key and value are both regex strings. All matches of the key will be replaced with the value.
	 * @return void
	 */
	public function updateFile(string $filePath, string $newFilePath, array $replaceRegex = []) {
		rename($filePath, $newFilePath);
		$fileContent = preg_replace(array_keys($replaceRegex), $replaceRegex, file($newFilePath));
		$file = fopen($newFilePath, "w");
		fwrite($file, implode($fileContent));
		fclose($file);
	}

	/**
	 * Deletes a file at the given path.
	 * @param  string $filePath The path where the file was stored.
	 * @return void
	 */
	public function deleteFile(string $filePath) {
		unlink($filePath);
	}

}
