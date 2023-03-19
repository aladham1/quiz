<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(FaceAuthSeeder::class);
        // $this->call(NotificationSeeder::class);
        // $this->call(CouponListSeeder::class);
        // $this->call(CouponSeeder::class);
        // $this->call(IntroSeeder::class);
        // $this->call(ExamSeeder::class);
        // $this->call(GroupSeeder::class);
        // $this->call(MCQSeeder::class);
        // $this->call(MultipleChoiceQuestionSeeder::class);
        // $this->call(ProjectSeeder::class);
        // $this->call(WordGameSeeder::class);
        $this->call(VoyagerDummyDatabaseSeeder::class);
         $this->call(UsersTableSeeder::class);


    }
}
