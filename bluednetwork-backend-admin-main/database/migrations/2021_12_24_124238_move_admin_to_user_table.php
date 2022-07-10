<?php

use App\Models\Control\Admin;
use App\Models\Users\User;
use App\Repositories\Users\UserRepository;
use Carbon\Carbon;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MoveAdminToUserTable extends Migration
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = resolve(UserRepository::class);
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $admins = Admin::get();
        $users = $this->userRepository->get();
        foreach ($users as $user) {
            if (!$user->hasAnyRole(['super_admin', 'user'])) {
                $user->assignRole('user');
            }
        }
        foreach ($admins as $admin) {
            $user = $this->userRepository->updateOrCreate(
                [
                    "email" => $admin->email
                ],
                [
                    "first_name" => $admin->first_name,
                    "last_name" => $admin->last_name,
                    "username" => $admin->first_name,
                    "password" => $admin->password,
                    "phone_number" => $admin->phone_number,
                    "intl_phone_number" => $admin->intl_phone_number,
                    "email_verified_at" => Carbon::now(),
                    "phone_verified_at" => Carbon::now(),
                ]
            );

            if (!$user->hasAnyRole(['super_admin', 'user'])) {
                $user->assignRole('super_admin');
            }
        }


        Schema::dropIfExists('admin');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    }
}
