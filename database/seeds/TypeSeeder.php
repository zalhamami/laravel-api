<?php

use Illuminate\Database\Seeder;
use App\Type;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Type::truncate();
        $values = ['Mountain', 'Homestay', 'Equiqment', 'Basecamp'];
        for ($i = 0; $i < count($values); $i++) {
            Type::create([
                'name' => $values[$i]
            ]);
        }
    }
}
