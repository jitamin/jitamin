<?php

/*
 * This file is part of Jitamin.
 *
 * Copyright (C) 2016 Jitamin Team
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Jitamin\Core;

/**
 * Thumbnail Generator.
 */
class Thumbnail
{
    protected $metadata = [];
    protected $srcImage;
    protected $dstImage;

    /**
     * Create a thumbnail from a local file.
     *
     * @static
     *
     * @param string $filename
     *
     * @return Thumbnail
     */
    public static function createFromFile($filename)
    {
        $self = new static();
        $self->fromFile($filename);

        return $self;
    }

    /**
     * Create a thumbnail from a string.
     *
     * @static
     *
     * @param string $blob
     *
     * @return Thumbnail
     */
    public static function createFromString($blob)
    {
        $self = new static();
        $self->fromString($blob);

        return $self;
    }

    /**
     * Load the local image file in memory with GD.
     *
     * @param string $filename
     *
     * @return Thumbnail
     */
    public function fromFile($filename)
    {
        $this->metadata = getimagesize($filename);
        $this->srcImage = imagecreatefromstring(file_get_contents($filename));

        return $this;
    }

    /**
     * Load the image blob in memory with GD.
     *
     * @param string $blob
     *
     * @return Thumbnail
     */
    public function fromString($blob)
    {
        if (!function_exists('getimagesizefromstring')) {
            $uri = 'data://application/octet-stream;base64,'.base64_encode($blob);
            $this->metadata = getimagesize($uri);
        } else {
            $this->metadata = getimagesizefromstring($blob);
        }

        $this->srcImage = imagecreatefromstring($blob);

        return $this;
    }

    /**
     * Resize the image.
     *
     * @param int $width
     * @param int $height
     *
     * @return Thumbnail
     */
    public function resize($width = 250, $height = 100)
    {
        $srcWidth = $this->metadata[0];
        $srcHeight = $this->metadata[1];
        $dstX = 0;
        $dstY = 0;

        if ($width == 0 && $height == 0) {
            $width = 100;
            $height = 100;
        }

        if ($width > 0 && $height == 0) {
            $dstWidth = $width;
            $dstHeight = floor($srcHeight * ($width / $srcWidth));
            $this->dstImage = imagecreatetruecolor($dstWidth, $dstHeight);
        } elseif ($width == 0 && $height > 0) {
            $dstWidth = floor($srcWidth * ($height / $srcHeight));
            $dstHeight = $height;
            $this->dstImage = imagecreatetruecolor($dstWidth, $dstHeight);
        } else {
            $srcRatio = $srcWidth / $srcHeight;
            $resizeRatio = $width / $height;

            if ($srcRatio <= $resizeRatio) {
                $dstWidth = $width;
                $dstHeight = floor($srcHeight * ($width / $srcWidth));
                $dstY = ($dstHeight - $height) / 2 * (-1);
            } else {
                $dstWidth = floor($srcWidth * ($height / $srcHeight));
                $dstHeight = $height;
                $dstX = ($dstWidth - $width) / 2 * (-1);
            }

            $this->dstImage = imagecreatetruecolor($width, $height);
        }

        imagecopyresampled($this->dstImage, $this->srcImage, $dstX, $dstY, 0, 0, $dstWidth, $dstHeight, $srcWidth, $srcHeight);

        return $this;
    }

    /**
     * Save the thumbnail to a local file.
     *
     * @param string $filename
     *
     * @return Thumbnail
     */
    public function toFile($filename)
    {
        imagejpeg($this->dstImage, $filename);
        imagedestroy($this->dstImage);
        imagedestroy($this->srcImage);

        return $this;
    }

    /**
     * Return the thumbnail as a string.
     *
     * @return string
     */
    public function toString()
    {
        ob_start();
        imagejpeg($this->dstImage, null);
        imagedestroy($this->dstImage);
        imagedestroy($this->srcImage);

        return ob_get_clean();
    }

    /**
     * Output the thumbnail directly to the browser or stdout.
     */
    public function toOutput()
    {
        imagejpeg($this->dstImage, null);
        imagedestroy($this->dstImage);
        imagedestroy($this->srcImage);
    }
}
