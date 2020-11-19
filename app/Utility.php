<?php

namespace App;

trait Utility 
{
    public function store($inputs = [], $id = null, $multiple = false) 
    {
        if($id) { $this->find($id)->update($inputs); return $id; }
        else {
            if($multiple) { $this->insert($inputs); }
            else { return $this->create($inputs)->id; }
        }
    }
}