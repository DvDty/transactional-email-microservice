<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOutboundEmailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('outbound_emails', function (Blueprint $table) {
            $table->id();

            $table->boolean('success');
            $table->string('driver');
            $table->string('recipient');
            $table->string('subject')->nullable();
            $table->text('content')->nullable();
            $table->text('error_message')->nullable()->default(null);

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
        Schema::dropIfExists('outbound_emails');
    }
}
