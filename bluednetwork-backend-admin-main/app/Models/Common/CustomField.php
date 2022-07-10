<?php
/*
 * Copyright (C) 2021,  Chistel Brown,  - All Rights Reserved
 * @project                  bds
 * @file                           CustomField.php
 * @author                  Chistel Brown
 * @site                          <http://twitter.com/chistelbrown>
 * @email                      chistelbrown@gmail.com
 * @lastmodified     15/08/2021, 3:36 PM
 */

namespace App\Models\Common;

use App\Abstracts\BaseModel;
use App\Collections\FieldCollection;
use App\Traits\Common\HasSlug;
use Database\Factories\CustomFieldFactory;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Spatie\Sluggable\SlugOptions;

/**
 * Class CustomField
 *
 * @package App\Models\Common
 */
class CustomField extends BaseModel
{
    use SoftDeletes, HasFactory, HasSlug;

    /**
     * @var string
     */
    const TYPE_CHECKBOX = 'checkbox';

    /**
     * @var string
     */
    const TYPE_NUMBER = 'number';

    /**
     * @var string
     */
    const TYPE_RADIO = 'radio';

    /**
     * @var string
     */
    const TYPE_SELECT = 'select';

    /**
     * @var string
     */
    const TYPE_TEXT = 'text';

    /**
     * @var string
     */
    const TYPE_TEXTAREA = 'textarea';


    const TYPE_FILE = 'file';
    const TYPE_IMAGE = 'image';
    const TYPE_BOOLEAN = 'boolean';

    /**
     * @var string
     */
    protected $table = 'custom_fields';

    /**
     * @var string[]
     */
    protected $guarded = ['id'];
    /**
     * @var string[]
     */
    protected $fillable = [
        'has_values',
        'type',
        'title',
        'key',
        'description',
        'answers',
        'required',
        'default_value',
        'order',
        'fieldable_id',
        'fieldable_type',
        'validation_rules',
        'has_values',
        'file_types',
        'max_file_size',
        'archived_at',
    ];

    protected $casts = [
        'answers' => 'array',
        'file_types' => 'array',
    ];

    /**
     *
     */
    /*public static function boot()
    {
        parent::boot();
        self::creating(function (self $field) {
            $lastFieldOnCurrentModel = $field->fieldable->customFields()->orderBy('order', 'desc')->first();
            $field->order = ($lastFieldOnCurrentModel ? $lastFieldOnCurrentModel->order : 0) + 1;
        });
    }*/

    /**
     * @return CustomFieldFactory|Factory
     */
    protected static function newFactory()
    {
        return CustomFieldFactory::new();
    }

    /**
     * Create a new field collection.
     *
     * @param array $models
     * @return FieldCollection
     */
    public function newCollection(array $models = []): FieldCollection
    {
        return new FieldCollection($models);
    }

    /**
     * Simple mutator for file types.
     *
     * @param array $value
     */
    public function setFileTypesAttribute(array $value)
    {
        $this->attributes['file_types'] = json_encode(array_values($value));
    }

    /**
     * @param $value
     * @return int|null
     */
    public function getMaxFileSizeAttribute($value): ?int
    {
        return (int) $value ?: null;
    }

    /**
     * @return bool
     */
    public function isFile(): bool
    {
        return $this->type === self::TYPE_FILE;
    }

    /**
     * @return bool
     */
    public function isImage(): bool
    {
        return $this->type === self::TYPE_IMAGE;
    }

    /**
     * @return bool
     */
    public function isNumber(): bool
    {
        return in_array($this->type, ['numeric', 'currency']);
    }

    /**
     * Returns true if the field is one of the following types: radio, drop-down-list or checkboxlist.
     *
     * @return bool
     */
    public function optionable(): bool
    {
        return in_array($this->type, ['radio', 'drop-down-list', 'checkboxlist', 'checkbox', 'select']);
    }


    public function delete()
    {
        // You cannot delete a field if it has field dependents
        /*if ($this->dependents()->count()) {

        }*/

        return parent::delete();
    }

    /**
     * @return SlugOptions
     */
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('key')
            ->usingSeparator('_');
    }

    /**
     * @param $required
     * @return array
     */
    private function fieldValidationRules($required): array
    {
        return [
            self::TYPE_CHECKBOX => $required ? ['accepted', 'in:0,1'] : ['in:0,1'],
            self::TYPE_NUMBER => ['integer'],
            self::TYPE_SELECT => [
                'string',
                'max:255',
                $this->answers ? Rule::in(json_decode($this->answers, true)) : '',
            ],
            self::TYPE_RADIO => [
                'string',
                'max:255',
                $this->answers ? Rule::in(json_decode($this->answers, true)) : '',
            ],
            self::TYPE_TEXT => [
                'string',
                'max:255',
            ],
            self::TYPE_TEXTAREA => [
                'string',
            ],
            self::TYPE_FILE => [
                $this->file_types ? 'mimes:' . implode(',', $this->file_types) : ''
            ],
            self::TYPE_IMAGE => [
                'image',
            ],
        ];
    }

    /**
     * @return MorphTo
     */
    public function fieldable(): MorphTo
    {
        return $this->morphTo();
    }

    /**
     * @return HasMany
     */
    public function responses(): HasMany
    {
        return $this->hasMany(CustomFieldResponse::class, 'custom_field_id');
    }

    /**
     * Archive the model.
     *
     * @return $this
     */
    public function archive(): static
    {
        $this->forceFill([
            'archived_at' => now(),
        ])->save();

        return $this;
    }

    /**
     * Unarchive the model.
     *
     * @return $this
     */
    public function unarchive(): static
    {
        $this->forceFill([
            'archived_at' => null,
        ])->save();

        return $this;
    }

    /**
     * @return mixed
     */
    public function getValidationRulesAttribute(): mixed
    {
        $typeRules = $this->fieldValidationRules($this->required)[$this->type];
        array_unshift($typeRules, $this->required ? 'required' : 'nullable');

        return $typeRules;
    }
}
