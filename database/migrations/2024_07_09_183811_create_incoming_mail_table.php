<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('incoming_mail', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->integer('id')->autoIncrement();
            $table->integer('institution_id')->nullable();
            $table->integer('classification_id')->nullable();
            $table->integer('type_mail_content_id')->nullable();
            $table->string('number')->unique();
            $table->string('name');
            $table->date('date');
            $table->text('description')->nullable();
            $table->text('attachment')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->integer('deleted_by')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
            $table->timestamp('deleted_at')->nullable();

            // Foreign Key
            $table->foreign('institution_id')->references('id')->on('institution');
            $table->foreign('classification_id')->references('id')->on('classification');
            $table->foreign('type_mail_content_id')->references('id')->on('type_mail_content');
            $table->foreign('created_by')->references('id')->on('users');
            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('deleted_by')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('incoming_mail');
    }
};
