<?php

use App\Models\Badge;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBadgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_badges', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Badge::class)->nullable()->index('usr_bgs_bg_fk')->onDelete('NO ACTION')->onUpdate('CASCADE');
            $table->foreignIdFor(User::class)->nullable()->index('usr_bgs_usr_fk')->onDelete('NO ACTION')->onUpdate('CASCADE');
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

        Schema::table('user_badges', function (Blueprint $table) {
            $table->dropIndex(['usr_bgs_bg_fk', 'usr_bgs_usr_fk']);
        });
        Schema::dropIfExists('user_badges');
    }
}
