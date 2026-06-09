<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Add database indexes that the chat and RFQ tracker queries
     * rely on. Purely additive — no data is changed.
     *
     * Why each one:
     *  - messages (sender_id, receiver_id, created_at):
     *      Speeds up the chat history query in ChatBox::getMessagesProperty().
     *  - messages (receiver_id, read_at):
     *      Speeds up the unread badge count (and markAsRead).
     *  - messages (deleted_by):
     *      Speeds up the notDeletedForMe() scope's filter.
     *  - messages (created_at):
     *      Speeds up getLatestChatPartnerId()'s "ORDER BY created_at DESC" lookup.
     *  - rfqs (status):
     *      Speeds up RfqTracker::getMetricsProperty()'s COUNT-by-status queries
     *      and the status filter on the tracker page.
     */
    public function up(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->index(['sender_id', 'receiver_id', 'created_at'], 'messages_sender_receiver_created_idx');
            $table->index(['receiver_id', 'read_at'], 'messages_receiver_read_idx');
            $table->index('deleted_by', 'messages_deleted_by_idx');
            $table->index('created_at', 'messages_created_at_idx');
        });

        Schema::table('rfqs', function (Blueprint $table) {
            $table->index('status', 'rfqs_status_idx');
        });
    }

    public function down(): void
    {
        Schema::table('messages', function (Blueprint $table) {
            $table->dropIndex('messages_sender_receiver_created_idx');
            $table->dropIndex('messages_receiver_read_idx');
            $table->dropIndex('messages_deleted_by_idx');
            $table->dropIndex('messages_created_at_idx');
        });

        Schema::table('rfqs', function (Blueprint $table) {
            $table->dropIndex('rfqs_status_idx');
        });
    }
};
