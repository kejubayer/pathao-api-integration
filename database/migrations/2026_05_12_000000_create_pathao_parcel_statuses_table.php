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
            $table->unsignedBigInteger('store_id')->nullable()->index();
            $table->string('event')->nullable()->index();
            $table->decimal('delivery_fee', 10, 2)->nullable();
            $table->timestamp('pathao_updated_at')->nullable();
            $table->timestamp('pathao_timestamp')->nullable();
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
