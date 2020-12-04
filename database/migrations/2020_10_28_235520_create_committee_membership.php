<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommitteeMembership extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('committee_membership', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('committee');
            $table->string('campus_id', 10);
            $table->string('lastname');
            $table->string('firstname');
            $table->string('rank');
            $table->string('department')->nullable();
            $table->string('college')->nullable();
            $table->string('ext')->nullable();
            $table->string('email')->nullable();
            $table->string('term');
            $table->string('charge_memberhip');
            $table->text('notes')->nullable();
            $table->boolean('private')->default(0);
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
        Schema::dropIfExists('committee_membership');
    }
}
