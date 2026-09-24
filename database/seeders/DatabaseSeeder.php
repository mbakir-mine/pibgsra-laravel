<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        if (! env('PIBGSRA_OWNER_EMAIL') || ! env('PIBGSRA_OWNER_PASSWORD')) {
            return;
        }

        $owner = User::firstOrCreate([
            'email' => env('PIBGSRA_OWNER_EMAIL'),
        ], [
            'name' => env('PIBGSRA_OWNER_NAME', 'Owner PIBGSRA'),
            'full_name' => env('PIBGSRA_OWNER_NAME', 'Owner PIBGSRA'),
            'password' => env('PIBGSRA_OWNER_PASSWORD'),
        ]);

        UserRole::firstOrCreate([
            'user_id' => $owner->id,
            'role' => UserRole::OWNER,
        ], [
            'state' => 'Selangor',
        ]);
    }
}
