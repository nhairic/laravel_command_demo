<?php
namespace Tool;

use Illuminate\Support\ServiceProvider;
class Tool extends ServiceProvider{
    public function register()
    {
        $this->app->bind('loremIpsum', function()
        {
            return new LoremIpsumGenerator;
        });
        $this->app->bind('demoCommand', function()
        {
            return new DemoCommand();
        });
    }
}

