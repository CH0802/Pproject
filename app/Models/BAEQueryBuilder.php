<?php
namespace App\Models;

use Illuminate\Database\Query\Builder as QueryBuilder;

class BAEQueryBuilder extends QueryBuilder {
	
	/**
	 * @描述 数组形式的条件查询
     * @param $where
     * @return Illuminate\Database\Query\Builder
	 * @example Entity::arrWhere(['is_where' => true, 'is_succrss' => 'yes'])->get();
	 * 
     */
	public function arrWhere($where){
            
		if(empty($where) || !is_array($where)){
			throw new \Exception('Params type error!');
		}
		$model = $this;
		foreach ($where as $key => $val) {
			$temp_key = explode(" ",$key);
                        
			if (is_array($val)) {
				if(isset($temp_key[1]) && $temp_key[1] == '!='){
					$model = $this->whereNotIn($temp_key[0], $val);
				}else{
					$model = $this->whereIn($key, $val);
				}
			} else {
				if(count($temp_key)>1){
                                      
					$model = $this->where($temp_key[0],$temp_key[1],$val);
                                        
				}else{
					$model = $this->where($key, $val);
				}
			}
		}
		return $model;
	}
	
	
	/***
	 * 数组型的排序
	 */
	
	public function arrOrderBy($orderBy){
		if(empty($orderBy) || !is_array($orderBy)){
			throw new \Exception('Params type error!');
		}
		$model = $this;
		foreach ($orderBy as $field => $sort) {
			$model = $this->orderBy($field, $sort);
		}
		return $model;
	}
        
        
}
