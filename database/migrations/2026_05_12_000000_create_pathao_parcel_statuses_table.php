<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pathao_parcel_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('consignment_id')->nullable()->index();
            $table->string('merchant_order_id')->nullable()->index();
            $table->string('status')->nullable()->index();
            $table->json('payload');
            $table->timestamp('received_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pathao_parcel_statuses');
    }
};
