<?php

use Illuminate\Database\Eloquent\Model;

class Sites extends Model
{


    /**
     * Get the skills associated with the article
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function skills() {

        return $this->belongsToMany('Skills');

    }

    /**
     * Get the skills associated with the article
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function images() {

        return $this->belongsToMany('Images');

    }
}