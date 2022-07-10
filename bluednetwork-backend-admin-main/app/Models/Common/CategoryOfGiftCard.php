<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           CategoryOfGiftCard.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     14/08/2021, 8:02 AM
 */

namespace App\Models\Common;

use App\Abstracts\BaseModel;
use App\Traits\Common\HasSlug;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kalnoy\Nestedset\NestedSet;
use Kalnoy\Nestedset\NodeTrait;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Sluggable\SlugOptions;

/**
 * Class CategoryOfGiftCard
 *
 * @package App\Models\Common
 */
class CategoryOfGiftCard extends BaseModel implements HasMedia
{
    use NodeTrait, HasSlug, InteractsWithMedia;

    protected $table = 'category_of_gift_cards';
    /**
     * @var string[]
     */
    protected $fillable = [
        'enabled',
        'title',
        'slug',
        NestedSet::LFT,
        NestedSet::RGT,
        NestedSet::PARENT_ID,
    ];

    protected $appends = [
        'category_icon'
    ];

    /**
     * Get the options for generating the slug.
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function getCategoryIconAttribute(): ?string
    {
        $mediaItem = $this->getFirstMediaUrl('category_of_giftcards');
        if (!is_null($mediaItem) && !empty($mediaItem)) {
            return $mediaItem;
        }
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->title) . '&color=7F9CF5&background=EBF4FF';
    }
}
