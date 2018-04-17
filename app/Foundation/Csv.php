<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Foundation;

use SplFileObject;

/**
 * CSV Writer/Reader.
 */
class Csv
{
    /**
     * CSV delimiter.
     *
     * @var string
     */
    private $delimiter = ',';

    /**
     * CSV enclosure.
     *
     * @var string
     */
    private $enclosure = '"';

    /**
     * CSV/SQL columns.
     *
     * @var array
     */
    private $columns = [];

    /**
     * Constructor.
     *
     * @param string $delimiter
     * @param string $enclosure
     */
    public function __construct($delimiter = ',', $enclosure = '"')
    {
        $this->delimiter = $delimiter;
        $this->enclosure = $enclosure;
    }

    /**
     * Get list of delimiters.
     *
     * @static
     *
     * @return array
     */
    public static function getDelimiters()
    {
        return [
            ','  => t('Comma'),
            ';'  => t('Semi-colon'),
            '\t' => t('Tab'),
            '|'  => t('Vertical bar'),
        ];
    }

    /**
     * Get list of enclosures.
     *
     * @static
     *
     * @return array
     */
    public static function getEnclosures()
    {
        return [
            '"' => t('Double Quote'),
            "'" => t('Single Quote'),
            ''  => t('None'),
        ];
    }

    /**
     * Check boolean field value.
     *
     * @static
     *
     * @param mixed $value
     *
     * @return int
     */
    public static function getBooleanValue($value)
    {
        if (!empty($value)) {
            $value = trim(strtolower($value));

            return $value === '1' || $value[0]
            === 't' || $value[0]
            === 'y' ? 1 : 0;
        }

        return 0;
    }

    /**
     * Output CSV file to standard output.
     *
     * @static
     *
     * @param array $rows
     */
    public static function output(array $rows)
    {
        $csv = new static();
        $csv->write('php://output', $rows);
    }

    /**
     * Define column mapping between CSV and SQL columns.
     *
     * @param array $columns
     *
     * @return Csv
     */
    public function setColumnMapping(array $columns)
    {
        $this->columns = $columns;

        return $this;
    }

    /**
     * Read CSV file.
     *
     * @param string   $filename
     * @param callable $callback Example: function(array $row, $line_number)
     *
     * @return Csv
     */
    public function read($filename, $callback)
    {
        $file = new SplFileObject($filename);
        $file->setFlags(SplFileObject::READ_CSV);
        $file->setCsvControl($this->delimiter, $this->enclosure);
        $line_number = 0;

        foreach ($file as $row) {
            $row = $this->filterRow($row);

            if (!empty($row) && $line_number > 0) {
                call_user_func_array($callback, [$this->associateColumns($row), $line_number]);
            }

            $line_number++;
        }

        return $this;
    }

    /**
     * Write CSV file.
     *
     * @param string $filename
     * @param array  $rows
     *
     * @return Csv
     */
    public function write($filename, array $rows)
    {
        $fp = fopen($filename, 'w');
        // wirte BOM header,Solve utf8 chinese grabled problem.
        fwrite ( $fp , chr ( 0xEF ) . chr ( 0xBB ) . chr ( 0xBF ) );
        if (is_resource($fp)) {
            foreach ($rows as $row) {
                fputcsv($fp, $row, $this->delimiter, $this->enclosure);
            }

            fclose($fp);
        }

        return $this;
    }

    /**
     * Associate columns header with row values.
     *
     * @param array $row
     *
     * @return array
     */
    private function associateColumns(array $row)
    {
        $line = [];
        $index = 0;

        foreach ($this->columns as $sql_name => $csv_name) {
            if (isset($row[$index])) {
                $line[$sql_name] = $row[$index];
            } else {
                $line[$sql_name] = '';
            }

            $index++;
        }

        return $line;
    }

    /**
     * Filter empty rows.
     *
     * @param array $row
     *
     * @return array
     */
    private function filterRow(array $row)
    {
        return array_filter($row);
    }
}
