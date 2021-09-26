<?php

namespace Tests\Browser\Traits;

use Illuminate\Contracts\Console\Kernel;
use Illuminate\Foundation\Testing\RefreshDatabaseState;

trait DatabaseMigrationsWithSeeder
{
    /**
     * Define hooks to migrate the database before and after each test.
     *
     * @return void
     */
    public function runDatabaseMigrations()
    {
        $this->artisan('migrate:refresh');
        $this->app[Kernel::class]->setArtisan(null);
        $this->artisan('db:seed --class=UserSeeder');
        $this->artisan('db:seed --class=PhotoSeeder');

        $this->beforeApplicationDestroyed(function () {
            $this->artisan('migrate:rollback');

            RefreshDatabaseState::$migrated = false;
        });
    }
}
