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
}