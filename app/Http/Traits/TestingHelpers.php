<?php

namespace App\Http\Traits;

use App\Models\Category;
use App\Models\Profile;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use stdClass;

trait TestingHelpers
{
    public function getFakeImage($name = 'test.jpg')
    {
        return UploadedFile::fake()->image($name);
    }

    public function getFakeImages($count = 1, $name = 'test.jpg'): Collection
    {
        $images = collect();

        for ($i = 0; $i < $count; $i++) {
            $image = UploadedFile::fake()->image($name);
            $images->push($image);
        }

        return $images;
    }

    public function randomemail()
    {
        return fake()->randomElement([
            fake()->numerify('77#######'),
            fake()->numerify('71#######'),
            fake()->numerify('73#######'),
            fake()->numerify('01######')
        ]);
    }

    public function randomMobileemail()
    {
        return fake()->randomElement([
            fake()->numerify('77#######'),
            fake()->numerify('71#######'),
            fake()->numerify('73#######')
        ]);
    }

    public function randomUserName()
    {
        return fake()->randomElement([
            fake()->company(),
            fake()->name(),
            fake()->lastName()
        ]);
    }

    public function getUserByEmail($email = 'test@example.com') : User
    {
        $user = User::firstWhere('email', $email) ?: User::factory()->create();

        return $user;
    }
}
