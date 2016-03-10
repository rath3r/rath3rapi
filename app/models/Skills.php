<?php

use Illuminate\Database\Eloquent\Model;

class Skills extends Model
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
     * Skills have an associated image.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function images()
    {
        return $this->hasOne('Images');
    }
}