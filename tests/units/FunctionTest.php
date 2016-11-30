<?php

/*
 * This file is part of Hiject.
 *
 * Copyright (C) 2016 Hiject Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/Base.php';

class FunctionTest extends Base
{
    public function testArrayColumnSum()
    {
        $input = [
            [
                'my_column' => 123,
            ],
            [
                'my_column' => 456.7,
            ],
            [],
        ];

        $this->assertSame(579.7, array_column_sum($input, 'my_column'));
    }

    public function testArrayColumnIndex()
    {
        $input = [
            [
                'k1' => 11,
                'k2' => 22,
            ],
            [
                'k1' => 11,
                'k2' => 55,
            ],
            [
                'k1' => 33,
                'k2' => 44,
            ],
            [],
        ];

        $expected = [
            11 => [
                [
                    'k1' => 11,
                    'k2' => 22,
                ],
                [
                    'k1' => 11,
                    'k2' => 55,
                ],
            ],
            33 => [
                [
                    'k1' => 33,
                    'k2' => 44,
                ],
            ],
        ];

        $this->assertSame($expected, array_column_index($input, 'k1'));
    }

    public function testArrayMergeRelation()
    {
        $relations = [
            88 => [
                'id'    => 123,
                'value' => 'test1',
            ],
            99 => [
                'id'    => 456,
                'value' => 'test2',
            ],
            55 => [],
        ];

        $input = [
            [],
            [
                'task_id' => 88,
                'title'   => 'task1',
            ],
            [
                'task_id' => 99,
                'title'   => 'task2',
            ],
            [
                'task_id' => 11,
                'title'   => 'task3',
            ],
        ];

        $expected = [
            [
                'my_relation' => [],
            ],
            [
                'task_id'     => 88,
                'title'       => 'task1',
                'my_relation' => [
                    'id'    => 123,
                    'value' => 'test1',
                ],
            ],
            [
                'task_id'     => 99,
                'title'       => 'task2',
                'my_relation' => [
                    'id'    => 456,
                    'value' => 'test2',
                ],
            ],
            [
                'task_id'     => 11,
                'title'       => 'task3',
                'my_relation' => [],
            ],
        ];

        array_merge_relation($input, $relations, 'my_relation', 'task_id');

        $this->assertSame($expected, $input);
    }
}
