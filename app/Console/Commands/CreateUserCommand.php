<?php

namespace App\Console\Commands;

use App\Models\Role;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class CreateUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:create';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a new user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user['name'] = $this->ask('What is your name?');
        $user['email'] = $this->ask('What is your email address?');
        $user['password'] = $this->secret('What is your password?');

        $roleName = $this->choice('Role of the new user', ['Admin', 'Editor'], 1);

        $role = Role::where('name', $roleName)->first();

        if (! $role) {
            $this->error('Could not find Role');

            return -1;
        }

        $validator = Validator::make($user, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'min:8'],
        ]);

        if ($validator->fails()) {
            foreach ($validator->errors()->all() as $error) {
                $this->error($error);
            }

            return;
        }

        DB::transaction(function () use ($role, $user) {

            $user['password'] = Hash::make($user['password']);

            $user = User::create($user);

            $user->roles()->attach($role->id);
        });

        $this->info('User '.$user['name'].' created successfully!');
        // $this->info("User ID: {$user->id}");
        // $this->info("User name: {$user->name}");
        // $this->info("User email: {$user->email}");

    }
}
