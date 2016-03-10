<?php

use Illuminate\Database\Eloquent\Model;

class Images extends Model
{

    /**
     * This skill is associated with many sites
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function sites()
    {
        return $this->belongsToMany('Sites');
    }

    /**
     * An image is associated with a skill
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function skill()
    {
        return $this->belongsTo('Skills');
    }
}