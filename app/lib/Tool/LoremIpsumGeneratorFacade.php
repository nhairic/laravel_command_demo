<?php namespace Tool;

use Illuminate\Support\Facades\Facade;

class LoremIpsumGeneratorFacade extends Facade {

    protected static function getFacadeAccessor() { return 'loremIpsum'; }

}

