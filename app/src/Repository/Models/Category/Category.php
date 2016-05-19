<?php

namespace Alroniks\Repository\Models\Category;

final class Category
{
    private $id;
    private $name;
    private $templated;

    public function __construct($name, $templated)
    {

        $this->name = $name;
        $this->templated = (bool)$templated;
    }


}


//<tag>
//    <id>4d66d1b6b2b0830ebe000002</id>
//    <name>SkinGraft</name>
//    <packages>1</packages>
//    <templated>1</templated>
//</tag>
