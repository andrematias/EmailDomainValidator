<?php
/**
 * Classe Template
 * Motor de templates
 * Responsável por carregar, alterar e substituir valores dos arquivos templates
 *
 * @link http://github.com/andrematias
 * @link andrersmatias@gmail.com
 * @version 0.1
 * @author André Matias
 */
namespace EDValidator\bundles\CoreBundle\Template;

class Template
{
    /**
     * O arquivo com o template
     * @var string
     */
    protected $file;

    /**
     * Array com as tags e os valores atribuidos
     * @var array
     */
    protected $values = array();

    /**
     * Tags encontradas no template
     * @var array
     */
    protected $vars = array();

    /**
     * Regex para as tags [_*_]
     * @var string
     */
    private static $regex = "\[\_[a-z0-9]+\_\]";


    /**
     * Atribui um valor para a tag
     * @param string $name nome da tag
     * @param string $value valor da tag
     */
    public function setValue($name, $value)
    {
        $this->values['[_'.$name.'_]'] = $value;
    }

    /**
     * Retorna o valor da tag passada pelo parametro
     * @param string $name nome da tag
     * @return string
     */
    public function getValue($name)
    {
        return $this->values['[_'.$name.'_]'];
    }

    /**
     * Captura todas as tags utilizadas no template que contenha a sintaxe
     * [_*_]
     * @param var file template $template
     * @return array
     */
    public function getVars(&$template)
    {
        $matches = array();
        
        preg_match_all("/".self::$regex."/", $template, $matches);

        $var = array_unique($matches[0]);
        
        for($i = 0; $i < count($var); $i++){
            $this->vars[] = $var[$i];
        }

        return $this->vars;
    }

    /**
     * Carrega para a classe o template passado por parametro
     * @param string $template
     * @param string $dir
     * @throws \Exception erro no carregamento do arquivo $template
     */
    public function load($template, $dir = NULL)
    {
        try{
            if(file_exists($dir.$template)){
                $this->file = preg_replace("/<!--[^>]*-->/", '',file_get_contents($dir.$template));
            }else{
                throw new \Exception('Falha ao carregar o template, verifique se o arquivo '.$dir.$template.' existe.');
            }
        }  catch (\Exception $err){
            echo $err->getMessage()."<br>";
        }
    }

    /**
     * Retorna o template com os valores já atribuidos
     * @return string
     */
    public function render()
    {
        return str_replace(array_keys($this->values), $this->values, $this->file);
    }

    /**
     * Mescla todos os valores dos templates passados por parametro
     * @param Obj Template $templates
     * @param flag|string $break
     * @return string
     */
    public static function merge($templates, $break = "\n")
    {

        $out = "";
        foreach ($templates as $template) {
            if(get_class($template) !== __NAMESPACE__.'\Template'){
                echo "Template inválido!!!";
            }else{
                $content = $template->render();
                $out  .= $content.$break;
            }
        }
        return $out;
    }

}
