<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationProjectTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_project', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organizationId')->nullable();
            $table->unsignedBigInteger('projectId')->nullable();
            $table->foreign('organizationId')->references('id')->on('organizations');
            $table->foreign('projectId')->references('id')->on('projects');
        });
        Schema::create('locale_project', function(Blueprint $table) {
            $table->unsignedBigInteger('localeId')->nullable();
            $table->unsignedBigInteger('projectId')->nullable();
            $table->foreign('localeId')->references('id')->on('locales');
            $table->foreign('projectId')->references('id')->on('projects');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('organization_project');
    }
}
