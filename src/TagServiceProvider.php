<?php

namespace JobMetric\Tag;

use JobMetric\PackageCore\Exceptions\MigrationFolderNotFoundException;
use JobMetric\PackageCore\PackageCore;
use JobMetric\PackageCore\PackageCoreServiceProvider;
use JobMetric\Tag\Events\TagTypeEvent;

class TagServiceProvider extends PackageCoreServiceProvider
{
    /**
     * @param PackageCore $package
     *
     * @return void
     * @throws MigrationFolderNotFoundException
     */
    public function configuration(PackageCore $package): void
    {
        $package->name('laravel-tag')
            ->hasConfig()
            ->hasMigration()
            ->hasTranslation();
    }

    /**
     * After register package
     *
     * @return void
     */
    public function afterRegisterPackage(): void
    {
        $this->app->singleton('tagType', function () {
            $event = new TagTypeEvent;
            event($event);

            return $event->tagType;
        });
    }
}
