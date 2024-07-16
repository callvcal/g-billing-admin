
<?
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateCodeColumnInMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('menus', function (Blueprint $table) {
            // Add a new column to temporarily store the auto-incremented values
            $table->unsignedBigInteger('new_code')->nullable()->unique();
        });

        // Populate new_code with incrementing values
        DB::statement('SET @row_number = 0');
        DB::statement('UPDATE menus SET new_code = (@row_number:=@row_number + 1)');

        // Drop the old code column
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('code');
        });

        // Rename new_code to code and set it as auto-increment
        Schema::table('menus', function (Blueprint $table) {
            $table->unsignedBigInteger('new_code')->autoIncrement()->change();
        });

        Schema::table('menus', function (Blueprint $table) {
            $table->renameColumn('new_code', 'code');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropColumn('code');
            $table->string('code')->nullable();
        });
    }
}
