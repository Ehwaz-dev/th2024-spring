<?php
namespace App\Models;
use App\Utils\Sqid;

use Illuminate\Database\Eloquent\Casts\Attribute;
trait HasSqid
{
    /**
     * Get the obfuscated version of the model Id.
     *
     * @see https://sqids.org
     */
    protected function sqid(): Attribute
    {
        return Attribute::make(
            get: fn () => Sqid::encode($this->id)
        );
    }

    /**
     * Retrieve the model for a bound value.
     *
     * @param mixed $value
     * @param string|null $field
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function resolveRouteBinding($value, $field = null)
    {
        return $this->resolveRouteBindingQuery($this, Sqid::decode($value), 'id')->first();
    }
}
