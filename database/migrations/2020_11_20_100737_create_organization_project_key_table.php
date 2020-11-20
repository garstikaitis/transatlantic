<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrganizationProjectKeyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('organization_project_key', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('organizationId')->nullable();
            $table->unsignedBigInteger('projectId')->nullable();
            $table->string('key')->nullable();
            $table->foreign('organizationId')->references('id')->on('organizations');
            $table->foreign('projectId')->references('id')->on('projects');
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
        Schema::dropIfExists('organization_project_key');
    }
}
