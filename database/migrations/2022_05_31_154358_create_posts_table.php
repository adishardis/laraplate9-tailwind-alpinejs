<?php

use App\Models\User;
use App\Enums\PostStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->string('slug')->nullable();
            $table->text('description');
            $table->enum('status', PostStatus::values())->default(PostStatus::DRAFT->value);
            $table->boolean('is_edited')->default(0);
            $table->foreignIdFor(User::class)->nullable()->index();
            $table->integer('created_at')->nullable();
            $table->integer('updated_at')->nullable();
            $table->integer('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('posts');
    }
};
