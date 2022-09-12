<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateLabelNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('label_notes', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->unsignedBigInteger('label_id')->nullable();
            $table->foreign("label_id")
            ->references("id")
            ->on("labels")
            ->onDelete("cascade")
            ->onUpdate("cascade");
            $table->unsignedBigInteger('note_id')->nullable();
            $table->foreign("note_id")
            ->references("id")
            ->on("notes")
            ->onDelete("cascade")
            ->onUpdate("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('label_notes');
    }
}
