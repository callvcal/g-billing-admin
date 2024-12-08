<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $this->updateForeignKeyToNullOnDelete('wallet_transactions', 'user_id', 'users');
        $this->updateForeignKeyToNullOnDelete('wallet_transactions', 'credit_wallet_id', 'wallets');
        $this->updateForeignKeyToNullOnDelete('wallet_transactions', 'debit_wallet_id', 'wallets');
        $this->updateForeignKeyToNullOnDelete('wallet_transactions', 'reward_point_id', 'rewards');

        $this->updateForeignKeyToNullOnDelete('referals', 'user_id', 'users');

        $this->updateForeignKeyToNullOnDelete('reward_transactions', 'user_id', 'users');
        $this->updateForeignKeyToNullOnDelete('reward_transactions', 'referal_id', 'referals');
        $this->updateForeignKeyToNullOnDelete('reward_transactions', 'reward_id', 'rewards');
    }

    /**
     * Update a foreign key to set NULL on delete.
     *
     * @param string $tableName
     * @param string $columnName
     * @param string $referencedTable
     */
    private function updateForeignKeyToNullOnDelete(string $tableName, string $columnName, string $referencedTable): void
    {
        Schema::table($tableName, function (Blueprint $table) use ($columnName, $referencedTable) {
            $table->dropForeign([$columnName]); // Drop the old foreign key
            $table->foreign($columnName)
                ->references('id')->on($referencedTable)
                ->nullOnDelete(); // Add the new foreign key with nullOnDelete
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add reverse logic here if necessary
    }
};
