<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Achievement;
use App\Models\User;

class CreateUserAchievementsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_achievements', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Achievement::class)->nullable()->index('usr_acht_acht_fk')->onDelete('NO ACTION')->onUpdate('CASCADE');
            $table->foreignIdFor(User::class)->nullable()->index('usr_acht_usr_fk')->onDelete('NO ACTION')->onUpdate('CASCADE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        Schema::table('user_achievements', function (Blueprint $table) {
            $table->dropIndex(['usr_acht_acht_fk', 'usr_acht_usr_fk']);
        });
        Schema::dropIfExists('user_achievements');
    }
}
