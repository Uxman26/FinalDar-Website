<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Finder\Finder;

class UpdateTranslations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-translations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scans the application for translatable strings in PHP, Blade, and JSON files and updates the JSON language files.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting to scan project files for translatable strings...');

        // Scan PHP and Blade files
        $phpStrings = $this->scanForTranslatableStrings();
        
        // Scan JSON files
        $jsonStrings = $this->scanJsonFiles();

        // Merge and filter out duplicates
        $allStrings = array_unique(array_merge($phpStrings, $jsonStrings));

        // Update language files
        $this->updateLanguageFiles($allStrings);

        $this->info('Translations have been updated successfully!');
    }

    /**
     * Scan the PHP and Blade project files for translatable strings.
     *
     * @return array
     */
    protected function scanForTranslatableStrings()
    {
        $finder = new Finder();
        $finder->in([
            base_path('resources/views'),
            base_path('app')
        ])->files()->name('*.php')->name('*.blade.php');
        
        $strings = [];
        foreach ($finder as $file) {
            $content = $file->getContents();
            // Regex to find __('...') and @lang('...')
            preg_match_all("/__\(['\"]([^'\"]+)['\"]\)/", $content, $matches);
            preg_match_all("/@lang\(['\"]([^'\"]+)['\"]\)/", $content, $langMatches);

            $strings = array_merge($strings, $matches[1], $langMatches[1]);
        }

        return $strings;
    }

    /**
     * Update the JSON language files with the scanned strings.
     *
     * @param array $strings
     */
    protected function updateLanguageFiles(array $strings)
    {
        $locales = ['en', 'ar', 'es']; // Example locales
        foreach ($locales as $locale) {
            $path = resource_path("lang/{$locale}.json");
            $current = [];
            if (file_exists($path)) {
                $current = json_decode(file_get_contents($path), true) ?: [];
            }

            foreach ($strings as $string) {
                if (!array_key_exists($string, $current)) {
                    $current[$string] = $string;  // Default to the key itself
                }
            }

            ksort($current);
            file_put_contents($path, json_encode($current, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        }
    }

    /**
     * Scan JSON files for translatable strings based on specified keys.
     *
     * @return array
     */
    protected function scanJsonFiles()
    {
        $finder = new Finder();
        $finder->in(base_path('resources/menu'))->files()->name('*.json');
        $strings = [];

        foreach ($finder as $file) {
            $content = json_decode($file->getContents(), true);
            $this->extractStringsFromJson($content, $strings);
        }

        return $strings;
    }

    /**
     * Recursively extract strings from JSON content.
     *
     * @param mixed $content
     * @param array &$strings
     */
    protected function extractStringsFromJson($content, &$strings)
    {
        if (is_array($content)) {
            foreach ($content as $key => $value) {
                if (is_string($value) && $key === 'name') {
                    $strings[] = $value;
                } elseif (is_array($value)) {
                    $this->extractStringsFromJson($value, $strings);
                }
            }
        }
    }
}
