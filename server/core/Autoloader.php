<?php

namespace App\Core;

/**
 * Interface definition for Autoloader functionality.
 * Following Dependency Inversion Principle.
 */
interface AutoloaderInterface
{
    public function register(): void;
    public function addNamespace(string $prefix, string $baseDir): self;
    public function loadClass(string $className): bool;
}

/**
 * Autoloader - A robust, SOLID-compliant PSR-4 autoloader.
 */
class Autoloader implements AutoloaderInterface
{
    /**
     * @var array Map of namespace prefixes to base directories.
     */
    private array $prefixes = [];

    /**
     * Registers the loadClass method in the SPL autoloader stack.
     */
    public function register(): void {
        spl_autoload_register([$this, 'loadClass']);
    }

    /**
     * Maps a namespace prefix to a base directory.
     * 
     * @param string $prefix The namespace prefix (e.g., "App\\").
     * @param string $baseDir The base directory for that prefix.
     * @throws \InvalidArgumentException If the base directory does not exist.
     * @return self
     */
    public function addNamespace(string $prefix, string $baseDir): self {
        $prefix = trim($prefix, '\\') . '\\';
        $realBaseDir = realpath($baseDir);

        if ($realBaseDir === false || !is_dir($realBaseDir)) {
            // Robust error handling: don't allow mapping to non-existent directories
            throw new \InvalidArgumentException("Base directory '{$baseDir}' does not exist or is not a directory.");
        }

        $baseDir = rtrim($realBaseDir, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;

        if (!isset($this->prefixes[$prefix])) {
            $this->prefixes[$prefix] = [];
        }

        // Avoid duplicate paths
        if (!in_array($baseDir, $this->prefixes[$prefix])) {
            $this->prefixes[$prefix][] = $baseDir;
        }

        return $this;
    }

    /**
     * Attempts to load the file for a given class name.
     * 
     * @param string $className The fully-qualified class name.
     * @return bool True if the file was successfully loaded, false otherwise.
     */
    public function loadClass(string $className): bool
    {
        $prefix = $className;

        // PSR-4 logic: iterate backwards through namespace components
        while (false !== $pos = strrpos($prefix, '\\')) {
            $prefix = substr($className, 0, $pos + 1);
            $relativeClass = substr($className, $pos + 1);

            if ($this->loadMappedFile($prefix, $relativeClass)) {
                return true;
            }

            $prefix = rtrim($prefix, '\\');
        }

        return false;
    }

    /**
     * Searches for a file in the mapped directories for a given prefix.
     * 
     * @param string $prefix The namespace prefix.
     * @param string $relativeClass The class name relative to the prefix.
     * @return bool
     */
    private function loadMappedFile(string $prefix, string $relativeClass): bool
    {
        if (!isset($this->prefixes[$prefix])) {
            return false;
        }

        foreach ($this->prefixes[$prefix] as $baseDir) {
            $file = $baseDir . str_replace('\\', DIRECTORY_SEPARATOR, $relativeClass) . '.php';

            if ($this->requireFile($file)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Safely includes a file if it exists.
     * 
     * @param string $file Path to the file.
     * @return bool
     */
    private function requireFile(string $file): bool
    {
        if (file_exists($file)) {
            require_once $file;
            return true;
        }
        return false;
    }
}
