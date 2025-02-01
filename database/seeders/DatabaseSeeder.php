<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\ActionsSeeder;
use Database\Seeders\ProjectsSeeder;
use Database\Seeders\RequestersSeeder;
use Database\Seeders\SubcontractorsSeeder;
use Database\Seeders\ActionModificationSeeder;
use Database\Seeders\ModificationWorkOrderSeeder;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            // PermissionSeeder::class,
            //  ModificationWorkOrderSeeder::class

        //      ActionsSeeder::class,
        //      SubcontractorsSeeder::class,
        //      RequestersSeeder::class,
        //      ProjectsSeeder::class,
        //    ActionModificationSeeder::class
        ]);
    }
}
