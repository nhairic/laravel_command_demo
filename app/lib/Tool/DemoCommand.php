<?php namespace Tool;
use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Filesystem\Filesystem;

class DemoCommand extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'demo:meetup';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command to display.demo';

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
                if ($this->argument('step') == 'sequence'){
                    foreach($this->getStep() as $key => $step){
                        $this->printStep($step);
                        if($key !== 'end'){
                            if (!$this->confirm('Do you wish to continue? [y|n]')){
                                break;
                            }
                        }
                    }
                }else{
                    $step = $this->getStep($this->argument('step'));
                    $this->printStep($step);
                }
	}
        public function printStep($step) {
                if($step['method'] == 'printPatternByStep'){
                    $this->printPatternByStep($step['text'], $step['modulo'], $step['speed']);
                }else{
                    $this->printByStep($step['text'], $step['modulo'], $step['speed'], $step['style']);
                }
        }
        public function printByStep($text, $modulo, $speed = 050000000, $code= 'info', $word = false) {
        $count = strlen($text);
        $this->output->write("<$code>");
        for ($i = 0; $i < $count; $i++) {
            $t = mb_substr($text, $i, 1);
            if ($modulo != 0 && $i % $modulo == 0 && $i != 0) {
                $this->output->write("\n");
            }
            
            if($t=='~')
                    $t= "</$code><comment>";
            if($t=='µ')
                    $t="</comment><$code>";
            
            $this->output->write($t);
            time_nanosleep(0, $speed);
        }
        $this->output->write("</$code>");
        $this->output->write( "\n");
    }
    public function printPatternByStep($text, $modulo, $speed = 050000000) {
        $count = strlen($text);
        for ($i = 0; $i < $count; $i++) {
            if ($modulo != 0 && $i % $modulo == 0 && $i != 0) {
                $this->output->write("\n");
            }
            if($text[$i] == " ") {
                $this->output->write("<error> </error>");
            }else{
                $this->output->write("<question> </question>");
            }
            time_nanosleep(0, $speed);
        }
        $this->output->write( "\n");
    }

    /**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return array(
			array('step'             , InputArgument::REQUIRED, 'Step of the demo.'),
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
			array('opt', 'opt', InputOption::VALUE_OPTIONAL|InputOption::VALUE_IS_ARRAY, 'An array option.', array()),
		);
	}
        protected function getStep($step = 'all'){
            
            $step0 = '';
            $step0 .='                                                        ';
            $step0 .=' L    AAAA RRR    AAAA V       V EEEE L                 ';
            $step0 .=' L    A  A R  R   A  A V       V E    L                 ';
            $step0 .=' L    AAAA RRR    AAAA  V     V  EEEE L                 ';
            $step0 .=' L    A  A R  R   A  A    V V    E    L                 ';
            $step0 .=' LLLL A  A R   R  A  A     V     EEEE LLLL              ';
            $step0 .='                                                        ';
            
            $step1  =' Bienvenue au Meetup                                        ';
            $step1 .=' Et maintenant passer la commande.                          ';
            $step1 .=' Sommaire :                                                 ';
            $step1 .='    1) Présentation d\'artisan                               ';
            $step1 .='    2) les bases d\'artisan                                  ';
            $step1 .='    3) Créer une commande                                   ';
            $step1 .='    4) Passons à la pratique                                ';

            $step2 = "1)Présentation d'artisan\n";
            $step2 .= "Artisan est le script permettant d'utiliser Laravel en ligne de commande.\n";
            $step2 .= "Il est basé sur le composant Console de Symfony. Durant le developpement d'un projet on est souvent amené à utiliser artisan.";
            $step2 .= "Le plus souvent pour générer des \"controller\" et des \"model\".\n";


            $step3 = "2)Les bases d'artisan\n";
            $step3 .= "Laravel dispose de plusieurs commandes de base utilisables par artisan.\n";
            $step3 .= "Pour Lancer une commande artisan ilfaut se placer à la racine du site et écrire la ligne sous la forme \n~'php artisan sacommande [argument(s)] [--sonoption=valeur]'µ(exemple php artisan migrate:create customer_basket_create_table --env='prod').\n";
            $step3 .= "Pour connaître toute la liste des commandes artisan il suffit de lancer cette commande : ~'php artisan list'µ\n";
            $step3 .= "Pour afficher l'aide d'une commande : ~'php artisan help [nomdelacommande]'µ (Exemple: php artisan help migrate:make)\n";
            $step3 .= "Deux options importantes sont disponibles pour toutes les commandes --env et --bench. --env permet de spécifier à quel environnement la commande s'applique(local, recette etc..). --bench spécifie le 'le package'.";

            $step4  = "3) Créer une commande\n";
            $step4 .= "Pour créér une commande on lance une commande !.\n";
            $step4 .= "~php artisan command:make FooCommandµ\n";
            $step4 .= "La commande générera un fichier php 'prérempli', par défaut il le place dans le répertoire app/commands.\n";
            $step4 .= "Des options permettent de changer le répertoire, d'ajouter un namespace et de nommer la commande.\n";
            $step4 .= "Pour pouvoir utiliser la commande il suffit de la renseigner dans le fichier ~app/start/artisan.phpµ.\n";

            $step5  = "4) Passons à la pratique\n";
            $step5 .= "Voyons ce que l'on peut faire!.\n";

            $step6  = "Merci à vous tous de m'avoir écouté.\n";
            $step6 .= "Cette petite demo est disponible sur https://github.com/nhairic/laravel_command_demo.git .\n";

            $aStep = array(
                0 => array('text' => $step0, 'method' => 'printPatternByStep', 'speed' => 050000000, 'modulo' => 56),
                1 => array('text' => $step1, 'method' => 'printByStep', 'speed' => 070000000, 'modulo' => 60, 'style'=> 'comment'),
                2 => array('text' => $step2, 'method' => 'printByStep', 'speed' => 070000000, 'modulo' => 0, 'style'=> 'comment'),
                3 => array('text' => $step3, 'method' => 'printByStep', 'speed' => 070000000, 'modulo' => 0, 'style'=> 'info'),
                4 => array('text' => $step4, 'method' => 'printByStep', 'speed' => 070000000, 'modulo' => 0, 'style'=> 'info'),
                5 => array('text' => $step5, 'method' => 'printByStep', 'speed' => 070000000, 'modulo' => 0, 'style'=> 'info'),
                'end' => array('text' => $step6, 'method' => 'printByStep', 'speed' => 070000000, 'modulo' => 0, 'style'=> 'info'),
            );
            if($step == 'all'){
                return $aStep;
            }else{
                return $aStep[$step];
            }
        }
}
