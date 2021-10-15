<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 填充 50 个虚拟用户
        $users = factory(User::class)->times(50)->make();
        User::insert($users->makeVisible(['password', 'remember_token'])->toArray());
        $user = User::find(1);
        $user->name = '西野七瀬';
        $user->is_admin = true;
        $user->email = 'espblood@qq.com';
        $user->password = bcrypt('abc123');
        $user->save();
    }
}
