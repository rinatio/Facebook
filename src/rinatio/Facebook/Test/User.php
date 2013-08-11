<?php

namespace rinatio\Facebook\Test;

class User
{
    public function __construct(array $data)
    {
        foreach ($data as $k=> $v) {
            $this->{$k} = $v;
        }
    }
}
