<?php
/**
 * @name 		: S C A T F (Scippi Class and Template System)
 * @desc		: A large PHP-Framework for projectcreation. Includes a complete Database-, tempalte- and modulesystem
 * @author 		: scrippi (David Steiman)
 * @access 		: David Steiman & Enrico Falkenhain
 * @copyright 	: 2009, David Steiman
 * @version 	: 0.1a
 */
?>
<?php
/**
 * A Class for Displaying dynamic Templates
 *
 * Example:
 * 
 * $obj = new Template("/path/to/templtes/","/path/to/tempaltes_c");
 * 
 * $variable = "test1";
 * 
 * $obj->AddVar("var1",$variable);
 * $obj->AddVar("var2",array(1,2,3));
 * 
 * $fetched_content = $obj->fetch();
 * 
 * $obj->display();
 * 
 * Using Template Functions:
 * 
 * {$var} 				= no functions
 * {$var|strip_tags} 	= using a function without additional parameters
 * {$var|substr::0,-1} 	= using a function with parameters
 * 
 * External Assigns:
 * 
 * {$var=1} or {$var="string"} or {$var=$var+1}
 * 
 * {$var    =  1} or {$var = 1} will NOT work!
 * 
 */
$sTPL_PATH = "";
class Template
{
	/**
	 * For more perfomance switch this var to false. On later changes it will be needed to empty the compiled templates folder
	 *
	 * @var bool
	 */
	private $Auto_Template_Reload = true;
	
	protected $templatePath;
	
	protected $sourceName;
	
	protected $targetName;
	
	protected $targetPath;
	
	protected $tmpPath;
	
	protected $CompiledContent = "";
	
	private $openedTemplate = false;
	
	private $TemplateContent = "";
	
	private $vars = array();
	/**
	 * Template Class Construct
	 *
	 * @param string $TPL_Path
	 * @param string $TMP_Path
	 */
	public function __construct($TPL_Path,$TMP_Path)
	{
		global $sTPL_PATH;
		$sTPL_PATH = $TPL_PATH;
		$this->templatePath = $TPL_Path;
		$this->sourceName = $this->templatePath;
		$this->targetName = md5($this->sourceName) . ".php";
		$this->targetPath = $TMP_Path . $this->targetName;
		$this->tmpPath = $TMP_Path;
	}
	/**
	 * Renames the Path to the new correct path given from render()
	 *
	 * @param unknown_type $sourceName
	 */
	private function renameTargetName($sourceName)
	{
		$this->sourceName = $this->templatePath . $sourceName;
		$this->targetName = md5($this->sourceName) . ".php";
		$this->targetPath = $this->tmpPath . $this->targetName;
	}
	/**
	 * Adds a variable to the Tempalte
	 *
	 * @param string $varName
	 * @param mixed $varValue
	 */
	public function addVar($varName,$varValue)
	{
		$this->vars[$varName] = $varValue;
	}
	/**
	 * Operates the complete rendering process and saving of the files
	 *
	 * @param bool $output
	 * @return string
	 */
	public function render($tpl,$output = true)
	{
		$this->renameTargetName($tpl);
		//Make TPL
		$tpl_file = $this->targetPath;
		//Checking
		if(!file_exists($tpl_file))
		{
			$this->createCompiledTemplateFile();
		}
		if($this->Auto_Template_Reload)
		{
			if(filectime($this->sourceName) != $this->readCreationTime())
				$this->createCompiledTemplateFile();
		}
		//Render 
		foreach ($this->vars as $varName => $varValue)
			${$varName} = $varValue;
		if(!$output)
			ob_start();
		require($this->targetPath);
		if(!$output)
		{
			$Fetched = ob_get_clean();
			return $Fetched;
		}
	}
	/**
	 * Returns the rendered Content
	 *
	 * @return string
	 */
	public function fetch($tpl)
	{
		return $this->render($tpl,false);
	}
	/**
	 * Displays the rendered Content
	 *
	 * @param string tpl
	 */
	public function display($tpl)
	{
		$this->render($tpl,true);
	}
	/**
	 * Returns the time of creation of the tmp file
	 *
	 * @return int
	 */
	private function readCreationTime()
	{
		$this->openTemplate();
		preg_match("/CREATION_TIME = \[([0-9]*?)\]/Ssi",$this->TemplateContent,$out);
		return $out[1];
	}
	/**
	 * Opens once the template
	 *
	 */
	private function openTemplate()
	{
		if(!$this->openedTemplate)
		{
			$content = file_get_contents($this->targetPath);
			$this->TemplateContent = $content;
		}
	}
	/**
	 * Creates the compiled data
	 *
	 */
	private function createCompiledTemplateFile()
	{
		//Machs auf
		$fp = fopen($this->sourceName,"r");
		$content = fread($fp,filesize($this->sourceName));
		fclose($fp);
		$Compiled = $this->parseHTML($content);
		$this->CompiledContent = $Compiled;
		$Comment = "<?php".PHP_EOL."
					/**".PHP_EOL.
					"*".PHP_EOL.
					"*   @name: Compiled Template file from {$this->templatePath}.tpl".PHP_EOL.
					"*   @author: scrippi, David Steiman (c) 2009".PHP_EOL.
					"*   @copyright: created by the Scrippi Class and Template Framework (c) 2009".PHP_EOL.
					"*   Do not change ANYTHING in this File, Fatal errors and performance issues my result.".PHP_EOL.
					"**/".str_repeat(PHP_EOL,3);
		$TimeMark = '/** CREATION_TIME = [' . time() . '] **/'.str_repeat(PHP_EOL,3)."?>";
		
		$Product = $Comment.$TimeMark.$Compiled;
		
		$fp = fopen($this->targetPath . $tpl,"w");
		fwrite($fp,$Product);
		fclose($fp);
		$this->openTemplate();
	}
	/**
	 * Major Parser, neaerly as strong as smarty, but with less then 30 lines instead of some thousands :D
	 *
	 * @param string $html
	 * @return string
	 */
	private function parseHTML($html)
	{
		//Inkludieren von TPLs
		$html = self::tpl_include($html);
        
        //Kommentare
        $html = preg_replace("/\{\*(.*?)\*\}/Ssi","",$html);
		//PHP soll ja auch direkt gehen
		$html = str_replace(array("{php}","{/php}"),array("<?","?>"),$html);
		//Einzel Variablen erkennen
		preg_match_all('/\{\$[\w\-\+\_\>\(\)\$\&\"\'\?\:\.\,\=\s\/\[\]]*\}/Ssi',$html,$out);
		$replace = array();
		$search = array();
		foreach ($out[0] as $value)
		{
			$search[] = '/\{(\$[\w\\-\+\_\>\(\)\$\"\'\&\?\:\.\,\=\s\/\[\]]*)\}/Ssi';
			$replace[] = '<? echo $1;?>';
		}
		$html = preg_replace_callback($search,array("Template","var_callback"),$html);
		//Einzel Variablen mit Funktion erkennen
		preg_match_all('/\{\$[\w\-\_\>\&\(\)\$\"\'\?\:\.\=\s\[\]]*\|.*?\}/Ssi',$html,$out);
		$replace = array();
		$search = array();
		foreach ($out[0] as $value)
		{
			$search[] = '/\{(\$[\w\-\_\>\(\)\$\"\'\?\:\.\=\s\[\]]*)\|(.*?)\}/';
			$replace[] = 'func_callback';
		}
		$html = preg_replace_callback($search,array("Template","func_callback"),$html);
		//Struktur enden definieren
		$html = preg_replace("/\{\/.*?\}/Ssi","<? } ?>",$html);
		//nun Strukturen definieren, sollte bis auf class eig alles sauber gehen
		preg_match_all("/\{([a-zA-Z]*?) (.*?)\}/Ssi",$html,$out);
		foreach ($out[1] as $key => $value)
		{
			if ($value == "elseif")
				$replace = "<? } ";
			else 
				$replace = "<? ";
			//Fehler abfangen
			preg_match("/(.*?)\(.*?\)/i",$out[2][$key],$funco);
			if($value == "function" && isset($funco[1]) && function_exists($funco[1]))
			{
				$start = strpos($html,$out[2][$key]) - 10;
				$end = strpos($html,"}",$start+1);
				$end = strpos($html,"}",$end+1) + 1;
				$html = sc_strcut($html,$start,$end);
				continue;
			}
			else 
				$replace .= $value == "function" ? "{$value} {$out[2][$key]} { ?>" : "{$value}({$out[2][$key]}) { ?>";
			$html = str_replace($out[0][$key],$replace,$html);
		}
		//Else Block
		$html = str_replace("{else}","<? } else { ?>",$html);
		//Funktionen ausfuehren
        $html = preg_replace("/\{([a-zA-Z0-9]*)\((.*?)\)\}/","<? $1($2); ?>",$html);
        
        //Konstanten
        $html = preg_replace("#\{\@(.*?)\}#Ssi","<? echo($1) ?>",$html);
        
		//Funktionen ausgeben
		$re = "/\{:([a-zA-Z0-9\_]*?)\(([a-zA-Z0-9\*\/\$\s:\.\\-\+\(\)\_'\",\[\]]*?)\)\}/U";
		$html = preg_replace($re,"<? echo $1($2); ?>",$html);
		//Add Ons
		$html = self::ModuleTags($html);
		return $html;
		//P.S.: Ganz ehrlich.....keine tausend Zeilen :/
	}
	private static function tpl_include($html)
	{
		return preg_replace_callback("/\{include [\"'](.*?)[\"']\}/i",array("Template","tpl_include_open"),$html);
	}
	private static function tpl_include_open($args)
	{
		global $sTPL_PATH;
		$tplPath = ROOT_PATH ."/tpl/" . $args[1];
		return file_exists($tplPath) ? self::tpl_include(file_get_contents(str_replace(array("'",'"'),"",$tplPath))) : "Could not import $tplPath";
	}
	private static function var_callback($args)
	{
		if(strrpos($args[1],"=")===false)
		{
			return "<?php echo ".$args[1]."; ?>";
		}
		else 
		{
			return "<?php ".$args[1]."; ?>";
		}
	}
	private static function func_callback($args)
	{
		//Teststring
		if(!preg_match("/(.*?)::(.*)/i",$args[2],$out))
		{
			if(!function_exists($args[2]))
				die("Template Error: Function {$args[2]} does not exist.");
			$function = $args[2];
		}
		else 
		{
			if(!function_exists($out[1]))
				die("Template Error: Function {$out[1]} does not exist.");
			$function = $out[1];
			$paramstring = $out[2];
		}
		
		$return = "<?php echo {$function}({$args[1]}";
		 if(isset($paramstring))
		 	$return .= ",{$paramstring})";
		 else 
		 	$return .= ")";
		 $return .= ";?>";
		 
		 return $return;
	}
	/**
	 * The ModuleTag Addon: Compiles <Modules> to class inplementing
	 *
	 * @param unknown_type $html
	 * @return unknown
	 */
	private function ModuleTags($html)
	{
		preg_match_all("/<Module (.*?) \/>/Ssi",$html,$out);
		foreach ($out[1] as $key => $value)
		{
			$parts = explode("HTML:",$value);
			//uns Interessirt nur der erste teil, rest is NoobHTML :)
			$arr = "\$attributes = array(" .preg_replace("/([a-zA-Z]*?)\=\"(.*?)\"/i",'"$1" => "$2", ',$parts[0]) . ");";
			eval($arr);
			if(!isset($attributes["name"]) || !isset($attributes["group"]))
				Core::ShowErrorPage("ParseError","Uncomplete Module Tag in {$this->sourceName}<br />Please note to add the group <b>and</b> the name attribute, and also the 'HTML:' extension in <Module> Tags.<br>Also do not forget, a ModuleTag is only valid by closing with a backslash.");
			else 
			{
				if(isset($attributes["outside"]) && $attributes['outside'] == "no")
				{
					$ModuleContent = Core::loadClass($attributes["group"],$attributes["name"]);
					$PutIn = $ModuleContent;
				}
				else 
				{
					$ModuleContent = Core::loadClass($attributes["group"],$attributes["name"]);
					$PutIn = '<div '.$parts[1].'>'.PHP_EOL.$ModuleContent.PHP_EOL."</div>";
				}
			}
			$html = str_replace($out[0][$key],$PutIn,$html);
		}
		
		return $html;
	}
	public function test()
	{
		$tpl = '
		{include "inc.tpl"}
		{$normale_variable[test]}
		{$function_ohne_parameter|strtolower}
		{$function_mit_parametern|substr::0,-1}
		{$q=1}
		{$q}
		{function button($test)}
			<div>{$test}</div>
			{/function}
		{button("<b>einfach so</b>")}
		{$text = "langer langer text"}
		{$text}
		{$text|truncate::20,"..."}
		{:date()}
		';
		
		echo self::parseHTML($tpl);
	}
};
function truncate($string, $max = 20, $replacement = '')
{
    if (strlen($string) <= $max)
    {
        return $string;
    }
    $leave = $max - strlen ($replacement);
    return substr_replace($string, $replacement, $leave);
}
#Template::test();
?>