<?php

namespace Knp\Rad\AutoRegistration\Finder;

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\Bundle\BundleInterface;

class BundleFinder
{
    /**
     * @param BundleInterface[]    $bundles
     * @param string[]|string|null $directory
     * @param string|null          $type
     *
     * @return string[]
     */
    public function findClasses($bundles, $directory = '', $type = '')
    {
        $finder = Finder::create()->files()->name('*.php')->sortByName()->ignoreUnreadableDirs();
        $found = false;

        foreach ($bundles as $bundle) {
            $path = rtrim($bundle->getPath(), DIRECTORY_SEPARATOR);

            if (true === empty($directory)) {
                $found = true;
                $finder->in($path);
                continue;
            }

            if (false === is_array($directory)) {
                $directory = [$directory];
            }

            foreach ($directory as $dir) {
                $pathname = sprintf('%s%s%s', $path, DIRECTORY_SEPARATOR, trim($dir, DIRECTORY_SEPARATOR));
                if (true === is_dir($pathname)) {
                    $found = true;
                    $finder->in($pathname);
                }
            }
        }

        if (false === $found) {
            return [];
        }

        $files = [];
        foreach ($finder as $file) {
            $files[] = $file->getPathname();
        }

        $classes = [];
        foreach ($files as $file) {
            foreach ($bundles as $bundle) {
                if (null !== $class = $this->getClassFromFileAndBundle($file, $bundle)) {
                    $classes[] = $class;
                    continue;
                }
            }
        }

        $classes = array_filter($classes, function ($e) { return class_exists($e); });

        if (false === empty($type)) {
            $classes = array_filter($classes, function ($e) use ($type) { return is_subclass_of($e, $type); });
        }

        return $classes;
    }

    /**
     * @param string          $file
     * @param BundleInterface $bundle
     *
     * @return string|null
     */
    private function getClassFromFileAndBundle($file, BundleInterface $bundle)
    {
        $root = $bundle->getPath();

        if (0 !== strpos($file, $root)) {
            return;
        }

        $file = substr($file, strlen($root), -4);

        return sprintf('%s%s', $bundle->getNamespace(), str_replace(DIRECTORY_SEPARATOR, '\\', $file));
    }
}
