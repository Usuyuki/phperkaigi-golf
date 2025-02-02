<?php

/**
 * This file was generated by Atlas. Changes will be overwritten.
 */

declare(strict_types=1);

namespace Playground\Web\DataSource\MySQL\Player;

use Atlas\Table\Table;

/**
 * @method PlayerRow|null fetchRow($primaryVal)
 * @method PlayerRow[] fetchRows(array $primaryVals)
 * @method PlayerTableSelect select(array $whereEquals = [])
 * @method PlayerRow newRow(array $cols = [])
 * @method PlayerRow newSelectedRow(array $cols)
 */
class PlayerTable extends Table
{
    //マイグレーションとシーダー的なもの？
    const DRIVER = 'mysql';

    const NAME = 'players';

    const COLUMNS = [
        'id' => [
            'name' => 'id',
            'type' => 'mediumint unsigned',
            'size' => 7,
            'scale' => 0,
            'notnull' => true,
            'default' => null,
            'autoinc' => true,
            'primary' => true,
            'options' => null,
        ],
        'fortee_name' => [
            'name' => 'fortee_name',
            'type' => 'varchar',
            'size' => 255,
            'scale' => null,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'display_name' => [
            'name' => 'display_name',
            'type' => 'varchar',
            'size' => 191,
            'scale' => null,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
        'created_at' => [
            'name' => 'created_at',
            'type' => 'datetime',
            'size' => null,
            'scale' => null,
            'notnull' => true,
            'default' => null,
            'autoinc' => false,
            'primary' => false,
            'options' => null,
        ],
    ];

    const COLUMN_NAMES = [
        'id',
        'fortee_name',
        'display_name',
        'created_at',
    ];

    const COLUMN_DEFAULTS = [
        'id' => null,
        'fortee_name' => null,
        'display_name' => null,
        'created_at' => null,
    ];

    const PRIMARY_KEY = [
        'id',
    ];

    const AUTOINC_COLUMN = 'id';

    const AUTOINC_SEQUENCE = null;
}