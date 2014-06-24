<?php

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Filesystem\Filesystem;

class export extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'export:make';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command to export data.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire()
	{
                ini_set('display_errors', 'on');
                ini_set('error_reporting', 'all');
                $this->printInfo();

                if ($this->confirm('Do you wish to continue? [y|n]'))
                {
                    try{
                        $method = $this->argument('export_identifier');
                        $param = null;
                        if($this->argument('param') != ''){
                            $param = $this->argument('param');
                        }
                            call_user_func('export::' . $method, $param);
                        }
                        catch (Exception $e){
                            $this->error($e->getMessage());
                        }
                }
	}
        protected function printInfo(){
                            $this->info('********************************************');
		$this->info('*************** Data Export ****************');
		$this->info('********************************************');
		$this->comment('Parameters will be display');
		$this->comment('A confirm will be ask');
		$this->info('DataBase       : ' . DB::getDatabaseName());
		$this->info('Host           : ' . DB::getConfig('host'));
		$this->info('Path           : ' . $this->argument('path'));
		$this->info('File Name      : ' . $this->argument('fileName'));
                $this->info('Export         : ' . $this->argument('export_identifier'));
                $this->info('Parameter      : ' . $this->argument('param'));
                $this->info('Delimiter (csv): ' . $this->option('delimiter'));
                $this->info('Enclosure (csv): ' . $this->option('enclosure'));
                $opt = $this->option('opt');
                if(!empty($opt)){
                    $this->info('Opt: [' . implode(',', $opt) . ']');
                }   
		$this->info('============================================');
        }
	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('path'             , InputArgument::REQUIRED, 'Path to export file.'),
			array('fileName'         , InputArgument::REQUIRED, 'Name of the file.'),
			array('export_identifier', InputArgument::REQUIRED, 'Identifier of the expot\'s process .'),
			array('format'           , InputArgument::OPTIONAL, 'Export format (csv, xml ...).', 'csv'),
			array('param'            , InputArgument::OPTIONAL, 'Parameter for method.'),
		);
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return array(
			array('opt', 'o', InputOption::VALUE_OPTIONAL|InputOption::VALUE_IS_ARRAY, 'An array option.', array()),
			array('enclosure', 'e', InputOption::VALUE_OPTIONAL, 'Enclosure for csv export.', '"'),
			array('delimiter', 'd', InputOption::VALUE_OPTIONAL, 'Delimiter for csv export', ';'),
		);
	}
        /**
         * 
         * @param type $articles
         * @throws Exception
         */
        public function exportArticleToCSV(&$articles){
                                $delimiter = export::option('delimiter');
                                $enclosure = export::option('enclosure');
                                $filename  = $this->createArticleExportFileName();
                                $fp        = fopen($filename, 'a+');
                                if($fp)
                                {
                                    foreach ($articles as $article)
                                    {
                                        $array = $article->toArray();
                                        $array['user_name'] = $array['user']['name'];
                                        $array['user_email'] = $array['user']['email'];
                                        unset($array['user']);
                                        if(fputcsv($fp , $array, $delimiter, $enclosure) === false){
                                            throw new Exception('csv has encountered a problem !');
                                        }
                                    }
                                    fclose($fp);
                                }else{
                                    throw new Exception('Cannot create csv file');
                                }
        }
        /**
         * 
         * @param int $id
         */
        public function exportArticle($id){
            /**
             * Supprime l'ancien fichier
             */
            $this->unlinkArticleExportFile();
            $this->info(trans('export.export_begin'));
            
            Article::with('user')->ofGroup($id)->chunk(200, function($articles)
                            {
                                switch(export::argument('format')){
                                    case 'csv' :
                                        export::exportArticleToCSV($articles);
                                        break;
                                    default :
                                        throw new Exception('No method for ' . export::argument('format') . '');
                                }
                            });
            $this->info(trans('export.end_of_export'));
        }
        /**
         * return void
         */
        protected function unlinkArticleExportFile(){
            $filename =  $this->createArticleExportFileName();
            if(file_exists($filename)){
                unlink($filename);
            }
        }
        /**
         * 
         * @return string
         */
        protected function createArticleExportFileName(){
            $path       = export::argument('path');
            $fileName   = export::argument('fileName');
            $format     = export::argument('format');
            return $path . '/' . $fileName . '.' . $format;
        }

}
