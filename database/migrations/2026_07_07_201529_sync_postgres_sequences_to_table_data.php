<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement(<<<'SQL'
            do $$
            declare
                sequence_record record;
                max_table_id bigint;
            begin
                for sequence_record in
                    select
                        table_schema,
                        table_name,
                        column_name,
                        pg_get_serial_sequence(format('%I.%I', table_schema, table_name), column_name) as sequence_name
                    from information_schema.columns
                    where table_schema = current_schema()
                        and column_default like 'nextval(%'
                loop
                    execute format(
                        'select max(%I) from %I.%I',
                        sequence_record.column_name,
                        sequence_record.table_schema,
                        sequence_record.table_name
                    ) into max_table_id;

                    if max_table_id is null then
                        execute 'select setval($1, 1, false)' using sequence_record.sequence_name;
                    else
                        execute 'select setval($1, $2, true)' using sequence_record.sequence_name, max_table_id;
                    end if;
                end loop;
            end $$;
        SQL);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
