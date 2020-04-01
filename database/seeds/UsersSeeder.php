<?php


use Phinx\Seed\AbstractSeed;

class UsersSeeder extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $data = [
            'name' => 'user01',
            'email' => 'email@email.com',
            'avatar' => '',
            'confirmation_key' => '',
            'confirmation_expires' => '',
            'is_confirmation' => '',
            'password' => password_hash('123456', PASSWORD_BCRYPT),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $user = $this->table('users');
        $user->insert($data)->save();
    }
}
