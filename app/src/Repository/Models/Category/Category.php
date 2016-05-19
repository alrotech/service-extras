<?php

namespace alroniks\repository\models;

class Model {

    protected $id;

    protected function identifier($value)
    {
        $this->id = substr(md5(md5($value)), 10);
    }

    public function toArray()
    {
        return [];
    }
}

final class Category extends Model
{
    private $name;
    private $templated;

    public function __construct($name, $templated)
    {
        $this->identifier($name);
        $this->name = $name;
        $this->templated = (bool)$templated;
    }
    
    public function getPackages()
    {
        
    }
    
    
}


//<tag>
//    <id>4d66d1b6b2b0830ebe000002</id>
//    <name>SkinGraft</name>
//    <packages>1</packages>
//    <templated>1</templated>
//</tag>
