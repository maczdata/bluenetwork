<?php

namespace App\Console\Commands;

use App\Models\Users\UserProfile;
use App\Repositories\Users\UserRepository;
use Illuminate\Console\Command;

class CreateUserProfile extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-profile';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';
    protected UserRepository $userRepository;
    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository)
    {
        parent::__construct();

        $this->userRepository = $userRepository;
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $users = $this->userRepository->all();
        foreach ($users as $user) {
            if (is_null($user->profile)) {
                UserProfile::create(['user_id' => $user->id]);
            }
        }
        return 0;
    }
}
