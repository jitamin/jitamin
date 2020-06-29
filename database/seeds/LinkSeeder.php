<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use Phinx\Seed\AbstractSeed;

class LinkSeeder extends AbstractSeed
{
    /**
     * Run Method.
     */
    public function run()
    {
        $data = [
            [
                'label'       => 'relates to',
                'opposite_id' => 0,
            ],
            [
                'label'       => 'blocks',
                'opposite_id' => 3,
            ],
            [
                'label'       => 'is blocked by',
                'opposite_id' => 2,
            ],
            [
                'label'       => 'duplicates',
                'opposite_id' => 5,
            ],
            [
                'label'       => 'is duplicated by',
                'opposite_id' => 4,
            ],
            [
                'label'       => 'is a child of',
                'opposite_id' => 7,
            ],
            [
                'label'       => 'is a parent of',
                'opposite_id' => 6,
            ],
            [
                'label'       => 'targets milestone',
                'opposite_id' => 9,
            ],
            [
                'label'       => 'is a milestone of',
                'opposite_id' => 8,
            ],
            [
                'label'       => 'fixes',
                'opposite_id' => 11,
            ],
            [
                'label'       => 'is fixed by',
                'opposite_id' => 10,
            ],
        ];

        $links = $this->table('links');
        $links->insert($data)
              ->save();
    }
}
