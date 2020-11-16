<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateForeignKeyConstraints extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('translations', function (Blueprint $table) {
            $table->foreign('localeId')->references('id')->on('locales');
            $table->foreign('organizationId')->references('id')->on('organizations');
            $table->foreign('userId')->references('id')->on('users');
        });
        Schema::table('organization_user', function (Blueprint $table) {
            $table->foreign('organizationId')->references('id')->on('organizations');
            $table->foreign('userId')->references('id')->on('users');
        });
        Schema::table('locale_organization', function (Blueprint $table) {
            $table->foreign('localeId')->references('id')->on('locales');
            $table->foreign('organizationId')->references('id')->on('organizations');
        });
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
