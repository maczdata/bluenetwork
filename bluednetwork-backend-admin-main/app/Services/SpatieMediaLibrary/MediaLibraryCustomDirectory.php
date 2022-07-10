<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           MediaLibraryCustomDirectory.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     12/08/2021, 1:04 PM
 */

namespace App\Services\SpatieMediaLibrary;

use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\PathGenerator\PathGenerator;

/**
 * Class MediaLibraryCustomDirectory
 * @package App\Services
 */
class MediaLibraryCustomDirectory implements PathGenerator
{
    /**
     * @param Media $media
     * @return string
     */
    public function getPath(Media $media): string
    {
        switch ($media->model_type) {
            case 'user':
                return 'users' . DIRECTORY_SEPARATOR . $this->getBasePath($media);
                break;
            case 'service':
                return 'services' . DIRECTORY_SEPARATOR . $this->getBasePath($media);
                break;
            case 'gift_card':
                return 'gift_cards' . DIRECTORY_SEPARATOR . $this->getBasePath($media);
                break;
            case 'service_variant':
                return 'service_variants' . DIRECTORY_SEPARATOR . $this->getBasePath($media);
                break;
            default:
                return $this->getBasePath($media);
        }
    }

    /**
     * @param Media $media
     * @return string
     */
    public function getPathForConversions(Media $media): string
    {
        return $this->getPath($media) . 'thumbnails' . DIRECTORY_SEPARATOR;
    }

    /**
     * @param Media $media
     * @return string
     */
    public function getPathForResponsiveImages(Media $media): string
    {
        return $this->getPath($media) . 'responsive-images' . DIRECTORY_SEPARATOR;
    }

    /*
      * Get a unique base path for the given media.
      */
    protected function getBasePath(Media $media): string
    {
        return $media->uuid . DIRECTORY_SEPARATOR;
    }
}
