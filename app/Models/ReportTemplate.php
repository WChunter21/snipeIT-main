<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Watson\Validating\ValidatingTrait;

class ReportTemplate extends Model
{
    use HasFactory;
    use ValidatingTrait;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'options' => 'array',
    ];

    protected $fillable = [
        'user_id',
        'name',
        'options',
    ];

    protected $rules = [
        'name' => 'required|unique:report_templates,name',
        'options' => 'array',
    ];

    protected static function booted()
    {
        // Scope to current user
        static::addGlobalScope('current_user', function (Builder $builder) {
            if (auth()->check()) {
                $builder->where('user_id', auth()->id());
            }
        });
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function checkmarkValue(string $property): string
    {
        // Assuming we're using the null object pattern,
        // return the default value if the object is not saved yet.
        if (is_null($this->id)) {
            return '1';
        }

        // Return the property's value if it exists
        // and return the default value if not.
        return $this->options[$property] ?? '0';
    }

    public function radioValue(string $property, $value, $isDefault = false): bool
    {
        // @todo: this method feels more like "radioShouldBeChecked" or something...
        // @todo: improve the variable names...

        $propertyExists = array_has($this->options, $property);

        if (!$propertyExists && $isDefault) {
            return true;
        }

        if ($propertyExists && $this->options[$property] === $value) {
            return true;
        }

        return false;
    }

    public function selectValue(string $property, string $model = null)
    {
        if (!isset($this->options[$property])) {
            return null;
        }

        $value = $this->options[$property];

        if (is_array($value)) {
            $value = $value[0];
        }

        // If a model is provided then we should ensure we only return
        // the value if the model still exists.
        if ($model) {
            $foundModel = $model::find($value);

            return $foundModel ? $foundModel->id : null;
        }

        return $value;
    }

    public function selectValues(string $property, string $model = null): iterable
    {
        if (!isset($this->options[$property])) {
            return [];
        }

        // @todo: I think this was added to support the null object pattern
        // @todo: Check if this is still needed and if so, add a test for it (testParsingSelectValues()).
        // if ($this->options[$property] === [null]) {
        //     return null;
        // }

        // If a model is provided then we should ensure we only return
        // the ids of models that exist and are not deleted.
        if ($model) {
            return $model::findMany($this->options[$property])->pluck('id');
        }

        // Wrap the value in an array if needed.
        if (!is_array($this->options[$property])) {
            return [$this->options[$property]];
        }

        return $this->options[$property];
    }

    public function textValue(string $property): string
    {
        // Assuming we're using the null object pattern,
        // return the default value if the object is not saved yet.
        if (is_null($this->id)) {
            return '';
        }

        // Return the property's value if it exists
        // and return the default value if not.
        return $this->options[$property] ?? '';
    }
}