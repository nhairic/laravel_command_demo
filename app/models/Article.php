<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Article extends Eloquent{
    	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'articles';
        public function user()
        {
            return $this->belongsTo('User');
        }

        public function scopeOfGroup($query, $id){
            return $query
                    ->addSelect('articles.*','u.name as uname','gr.name as grname', 'gr.id as gr_id' )
                    ->join('users as u', 'articles.user_id', '=', 'u.id' )
                    ->join('group_user as gp', 'gp.user_id', '=', 'u.id' )
                    ->join('groups as gr', 'gr.id', '=', 'gp.group_id' )
                    ->where('gr.id', $id);
        }
}
