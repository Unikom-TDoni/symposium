<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRejectionsTable extends Migration
{
    public function up()
    {
        Schema::create('rejections', function (Blueprint $table) {
            $table->char('id', 36)->primary();
            $table->string('talk_revision_id', 36);
            $table->string('conference_id', 36)->index('rejections_conference_id_foreign');
            $table->timestamps();
            $table->unique(['talk_revision_id', 'conference_id'], 'rejections_talk_revision_conference_unique');
        });

        Schema::table('rejections', function (Blueprint $table) {
            $table->foreign('conference_id')->references('id')->on('conferences')->onUpdate('RESTRICT')->onDelete('CASCADE');
            $table->foreign('talk_revision_id')->references('id')->on('talk_revisions')->onUpdate('RESTRICT')->onDelete('CASCADE');
        });

    }

    public function down()
    {
        Schema::table('rejections', function (Blueprint $table) {
            $table->dropForeign('acceptances_conference_id_foreign');
            $table->dropForeign('acceptances_talk_revision_id_foreign');
        });

        Schema::dropIfExists('rejections');
    }
}
