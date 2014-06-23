<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
class Group extends Eloquent{
    	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'groups';
        public function users()
        {
            return $this->belongsToMany('User');
        }

}
