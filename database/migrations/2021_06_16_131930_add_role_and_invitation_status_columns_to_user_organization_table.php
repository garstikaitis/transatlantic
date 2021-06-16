<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRoleAndInvitationStatusColumnsToUserOrganizationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('organization_user', function (Blueprint $table) {
            $table->enum('role', ['VIEWER', 'COMPANY_ADMIN', 'EDITOR'])->default('VIEWER');
            $table->enum('invitation_status', ['PENDING', 'ACCEPTED', 'EXPIRED'])->default('PENDING');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_organization', function (Blueprint $table) {
            $table->dropColumn('role');
            $table->dropColumn('invitation_status');
        });
    }
}
