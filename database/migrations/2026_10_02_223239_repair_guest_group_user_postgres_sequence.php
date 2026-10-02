<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement(<<<'SQL'
            DO $$
            DECLARE
                sequence_name regclass;
                maximum_id bigint;
                sequence_value bigint;
            BEGIN
                LOCK TABLE guest_group_user IN ACCESS EXCLUSIVE MODE;

                SELECT COALESCE(
                    pg_get_serial_sequence('guest_group_user', 'id')::regclass,
                    (
                        SELECT dependency.refobjid::regclass
                        FROM pg_attrdef AS defaults
                        JOIN pg_attribute AS column_info
                            ON column_info.attrelid = defaults.adrelid
                            AND column_info.attnum = defaults.adnum
                        JOIN pg_depend AS dependency
                            ON dependency.classid = 'pg_attrdef'::regclass
                            AND dependency.objid = defaults.oid
                            AND dependency.refclassid = 'pg_class'::regclass
                        JOIN pg_class AS sequence_info
                            ON sequence_info.oid = dependency.refobjid
                            AND sequence_info.relkind = 'S'
                        WHERE defaults.adrelid = 'guest_group_user'::regclass
                            AND column_info.attname = 'id'
                    )
                ) INTO sequence_name;

                IF sequence_name IS NULL THEN
                    RAISE EXCEPTION 'No ID sequence found for guest_group_user';
                END IF;

                SELECT MAX(id) INTO maximum_id FROM guest_group_user;
                EXECUTE format('SELECT last_value FROM %s', sequence_name) INTO sequence_value;

                IF maximum_id IS NOT NULL AND maximum_id >= sequence_value THEN
                    PERFORM setval(sequence_name, maximum_id, true);
                END IF;
            END $$;
        SQL);
    }

    /**
     * Sequence repairs cannot be reversed without risking duplicate IDs.
     */
    public function down(): void {}
};
