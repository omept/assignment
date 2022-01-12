<?php

use App\Models\Lesson;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserWatchLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_watch_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Lesson::class)->nullable()->index('usr_wls_les_fk')->onDelete('NO ACTION')->onUpdate('CASCADE');
            $table->foreignIdFor(User::class)->nullable()->index('usr_wls_usr_fk')->onDelete('NO ACTION')->onUpdate('CASCADE');
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

        Schema::table('user_watch_logs', function (Blueprint $table) {
            $table->dropIndex(['usr_wls_les_fk', 'usr_wls_usr_fk']);
        });
        Schema::dropIfExists('user_watch_logs');
    }
}
