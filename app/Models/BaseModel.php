<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\BAEQueryBuilder;

class BaseModel extends Model
{
    protected function newBaseQueryBuilder() {
	$conn = $this->getConnection();

        $grammar = $conn->getQueryGrammar();

        return new BAEQueryBuilder($conn, $grammar, $conn->getPostProcessor());
    }
    
    protected $dateFormat = 'Y-m-d H:i:s';


    public function incr($column, $amount = 1,$extra = []) {
       
        return parent::increment($column, $amount ,$extra);
    }

    public function decr($column, $amount = 1,$extra = []) {
        return parent::decrement($column, $amount,$extra);
    }


}