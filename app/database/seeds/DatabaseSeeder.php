<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();
                DB::table('group_user')->truncate();
		$this->call('GroupTableSeeder');
		$this->call('UserTableSeeder');
		$this->call('ArticleTableSeeder');
	}

}

class GroupTableSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
                DB::table('groups')->truncate();
                $administrator = array(
                    'name'          => 'Administrator',
                    'identifier'    => 'administrator',
                    'description'   => 'Group of administrator'
                );
                $vip = array(
                    'name'          => 'VIP',
                    'identifier'    => 'vip',
                    'description'   => 'User who have access to private site'
                );
                $moderator = array(
                    'name'          => 'Moderator',
                    'identifier'    => 'moderator',
                    'description'   => 'Group of moderator'
                );
                $editor = array(
                    'name'          => 'Editor',
                    'identifier'    => 'editor',
                    'description'   => 'Group of user who create article'
                );
                Group::create($administrator);
                Group::create($vip);
                Group::create($moderator);
                Group::create($editor);
	}

}

class ArticleTableSeeder extends Seeder {
    /**
     *
     * @var int
     */
    protected $titleMinChar     = 50;
    /**
     *
     * @var int
     */
    protected $titleMaxChar     = 100;
    /**
     *
     * @var int
     */
    protected $teaserMinChar    = 100;
    /**
     *
     * @var int
     */
    protected $teaserMaxChar    = 150;
    /**
     *
     * @var int
     */
    protected $bodyMinChar      = 200;
    /**
     *
     * @var int
     */
    protected $bodyMaxChar      = 300;
    /**
     * 
     */
    public function run()
    {
        $users = User::all();
        $users = $users->toArray();
        DB::table('articles')->truncate();
        for($i=0; $i<10000; $i++){
            $key = array_rand($users, 1);
            Article::create($this->createArticleData($users[$key]));
        }
    }
    /**
     * 
     * @return array
     */
    protected function createArticleData($user){
        return array(
                'title'     => Text::getContent($this->createTitleCountChar(), 'txt', true),
                'teaser'    => Text::getContent($this->createTeaserCountChar(), 'plain', true),
                'body'      => Text::getContent($this->createBodyCountChar(), 'html', true),
                'user_id'   => $user['id']
            );
    }
    /**
     * 
     * @return int
     */
    protected function createTitleCountChar(){
        return rand($this->titleMinChar, $this->titleMaxChar);
    }
    /**
     * 
     * @return int
     */
    protected function createTeaserCountChar(){
        return rand($this->teaserMinChar, $this->teaserMaxChar);
    }
    /**
     * 
     * @return int
     */
    protected function createBodyCountChar(){
        return rand($this->bodyMinChar, $this->bodyMaxChar);
    }

}
class UserTableSeeder extends Seeder {
    /**
     *
     * @var int
     */
    protected $nameMinChar     = 10;
    /**
     *
     * @var int
     */
    protected $nameMaxChar     = 20;
    /**

    /**
     * 
     */
    public function run()
    {
        DB::table('users')->delete();
        User::create(array('name' => 'administrator', 'email' => 'broweteric@gmail.com'));
        for($i=0; $i<100; $i++){
            User::create($this->createUserData($i))->groups()->sync(array(rand(2,4)));
        }
    }
    /**
     * 
     * @return array
     */
    protected function createUserData($int){
        return array(
                'name'     => Text::getContent($this->createNameCountChar(), 'txt', true),
                'email'    => $this->createEmail($int),
            );
    }
    /**
     * 
     * @return int
     */
    protected function createNameCountChar(){
        return rand($this->nameMinChar, $this->nameMaxChar);
    }
    /**
     * 
     * @return int
     */
    protected function createEmail($int){
        return 'maildetest-' . $int . '@test.fr';
    }
}