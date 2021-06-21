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
            $table->string('campus_id', 50);
            $table->string('emp_type', 45);
            $table->unsignedTinyInteger('emp_sort');
            $table->string('lastname');
            $table->string('firstname');
            $table->unsignedBigInteger('committee');
            $table->unsignedBigInteger('charge');
            $table->unsignedBigInteger('rank');
            $table->string('department')->nullable();
            $table->string('college')->nullable();
            $table->string('ext')->nullable();
            $table->string('email')->nullable();
            $table->string('term');
            $table->text('notes')->nullable();
            $table->boolean('private')->default(0);
            $table->boolean('alternate')->default(0);
            $table->timestamps();
            $table->softDeletes();
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
