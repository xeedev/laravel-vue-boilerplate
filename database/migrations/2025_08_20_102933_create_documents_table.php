<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('original_name');       // Original file name
            $table->string('stored_name');         // Stored file name
            $table->string('folder')->default('uploads'); // Folder
            $table->string('mime_type')->nullable();      // File type
            $table->unsignedBigInteger('size')->default(0); // File size
            $table->timestamps();                  // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
