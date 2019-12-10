<?php

namespace App;

interface ApiInterface
{
    /**
     * @param $type string
     * @param $id int
     * @return mixed
     */
    public function request($type, $id);
}