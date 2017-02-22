<?php
/**
 * Classe para manipular arquivos CSV
 * @author: André Matias
 * @link: andrersmatias@gmail.com
 * @link: http://github.com/andrematias
 */
namespace EDValidator\bundles\CoreBundle\CSVReader;

use EDValidator\bundles\CoreBundle\Exceptions\CsvException;
use EDValidator\bundles\CoreBundle\Models\Log;

class CsvReader{
    public $sourceFile;
    private static $headerTable;
    private static $delimiterBy;
    private static $stringEncapsulatedBy;
    private static $countTotalLines;
    private static $countTotalColumns;
    private static $columnsData = array();
    private static $contentData = array();
    private $csvContent         = array();

    /**
     * Método construtor da classe, configura os valores passados nos parametros.
     *
     * @param string $sourceFile            Caminho absoluto para um arquivo formatado para csv
     * @param string $delimiterBy           Separador das colunas, por padrão o valor é , (vírgula)
     * @param char $stringEncapsulatedBy    Encapsulador de string com espaços, por padrão o valor é "
     * @param boolean $headerTable          Indica se o arquivo contém ou conterá a linha de cabeçalho
     *                                      por padrão o valor é false
     * @access public
     * @return void
     */
    public function __construct($sourceFile, $delimiterBy = ",", $stringEncapsulatedBy = "\"", $headerTable = false)
    {
        $this->sourceFile           = $sourceFile;
        self::$headerTable          = $headerTable;
        self::$stringEncapsulatedBy = $stringEncapsulatedBy;
        self::$delimiterBy          = $delimiterBy;
    }

    /**
     * Método para configurar os valores
     * @param array $contentArray   Matriz com os valores que o csv conterá
     *                              quando for criado através do método writeCsvFile
     * @see writeCsvFile();
     * @access public
     * @return void
     */
    public function setCsvContent(Array $contentArray)
    {
        if(is_array($contentArray) && !empty($contentArray)){
            $this->csvContent = $contentArray;
        }
    }


    /**
     * Método para carregar o csv em uma matriz
     * @return array Matriz com os valores do csv
     */
    public function readyCsvFile()
    {
        $fileLines = $this->loadDataFileToArray();
        $fileLines = self::replaceTheEncapsulationOfString($fileLines, 0);
        $fileLines = self::removeWhiteSpaces($fileLines, 0);

        self::$columnsData = $this->getColumnsKeys($fileLines);

        if(self::$headerTable === true){
            $this->associationValuesWithTheKeys($fileLines);
        }else{
            $this->includeSimpleValuesInTheArray($fileLines);
        }
        return $this->csvContent;
    }

    /**
     * Método para criar um novo arquivo com a formatação csv configurada no
     * construtor.
     *
     * @see __construct()
     * @return int                Retorna a quantidade de bytes incluida no arquivo
     * @throws CsvException          Caso a propriedade csvContent não contenha uma
     *                            matriz com os valores do novo csv.
     */
    public function writeCsvFile()
    {
        try{

            if(empty($this->csvContent)){
                throw new CsvException('Falha ao criar o arquivo, verifique se o método setCsvContent foi estabelecido.');
            }

            $csvData = null;

            foreach ($this->csvContent as $content){
                self::$columnsData = array_keys($content);
                self::$contentData = array_values($content);
                $csvData .= implode(';',self::$contentData)."\n";
            }
            
            $headerKeys = (implode(';', self::$columnsData))."\n";

            if(self::$headerTable === true){
                return $this->persistDataInFile($this->sourceFile, $headerKeys.$csvData);
            }else{
                return $this->persistDataInFile($this->sourceFile, $csvData);
            }
        }catch(CsvException $ex){
            Log::register($ex);
        }
    }

    /**
     *
     * @param string $file             Caminho absoluto para o arquivo.
     * @param string $data             O conteúdo que sera salvo no arquivo.
     * @param boolean $overwriteFile   Verifica se a estrutura interna quer que
     *                                 o arquivo seja substrituido pelo novo.
     * @access private
     * @return int                     quantidade de bytes salvo no arquivo.
     * @throws CsvException              Excessão caso o arquivo exista e o sistema
     *                                 não pretende substitui-lo.
     */
    private function persistDataInFile($file, $data, $overwriteFile = true)
    {
        try{

            if(!file_exists($file)){
                return file_put_contents($file, $data, FILE_APPEND);
            }else if($overwriteFile){
                $this->deleteFile($file);
                return file_put_contents($file, $data, FILE_APPEND);
            }else{
                throw new CsvException('Arquivo ['.$file.'] já existe e não pode ser reescrito!!!');
            }
        }catch(CsvException $ex){
            Log::register($ex);
        }
    }

    /**
     * Método responsável por deletar arquivos.
     *
     * @access public
     * @param string $filePath  O caminho absoluto para o arquivo.
     * @return boolean          True se o arquivo for deletado.
     */
    public function deleteFile($filePath)
    {
        if(file_exists($filePath) && is_writable($filePath)){
            return unlink($filePath);
        }
    }

    /**
     * Método para realizar o download do arquivo trabalhado no objeto instanciado
     *
     * @access public
     * @return void
     */
    public function downloadThisFileCsv()
    {
        header('Content-Description: File Transfer');
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="'.$this->sourceFile.'"');

        readfile($this->sourceFile);
    }

    /**
     * Método para associar os valores com as chaves, caso o parametro headerTable
     * for configurado como true.
     *
     * @see __construct()
     * @access public
     * @param array $fileLines  Array com as linhas de um arquivo csv
     * @return void
     */
    private function associationValuesWithTheKeys(Array $fileLines)
    {
        self::$countTotalLines = count($fileLines);

        $matrixKey = 0;

        for($lineNumber = 1; self::$countTotalLines > $lineNumber; $lineNumber++){
            $lines[$lineNumber] = self::replaceTheEncapsulationOfString($fileLines, $lineNumber);
            $lines[$lineNumber] = self::removeWhiteSpaces($lines[$lineNumber], $lineNumber);
            $csvData[$lineNumber]   = self::getValuesByLine($lines[$lineNumber], $lineNumber);

            $valueIndex = 0;
            foreach (self::$columnsData as $key) {
                $key = strtolower($key);
                $this->csvContent[$matrixKey][$key] = $csvData[$lineNumber][$valueIndex];
                $valueIndex++;
            }
            $matrixKey++;
        } 
    }

    /**
     * Método para incluir os valores na matriz sem uma chave.
     *
     * @access private
     * @param array $fileLines  Array com as linhas de um arquivo csv
     * @return void
     */
    private function includeSimpleValuesInTheArray(Array $fileLines)
    {
        $matrixKey = 0;
        foreach ($fileLines as $line) {
            $line = self::removeWhiteSpaces($line);
            $line = self::replaceTheEncapsulationOfString($line);
            $csvData = self::getValuesByLine($line);
            
            self::$countTotalColumns = count(self::$columnsData);
            
            for($valueKey = 0; self::$countTotalColumns > $valueKey; $valueKey++){
                $this->csvContent[$matrixKey][$valueKey] = $csvData[$valueKey];
            }
            $matrixKey++;
        }
    }

    /**
     * Método para carregar em um array as linhas de um csv informado na
     * propriedade sourceFile.
     *
     * @see __construct()
     * @access private
     * @return array    Array com as linhas do csv informado.
     */
    private function loadDataFileToArray()
    {
        $fileLines = file($this->sourceFile);
        return $fileLines;
    }

    /**
     *
     * @param mixed string|array $fileLines     Array com as linhas de um csv ou uma
     *                                          string.
     * @param int $index                        Chave do array, caso fileLines seja um
     *                                          array, por padrão inicia em 0.
     * @return array
     */
    private function getValuesByLine($fileLines, $index = 0)
    {
        if(is_array($fileLines)){
            $value = explode(self::$delimiterBy, $fileLines[$index]);
            return $value;
        }else{
            $value = explode(self::$delimiterBy, $fileLines);
            return $value;
        }
    }

    /**
     * Método utilizado para recuperar as colunas de um csv
     *
     * @access private
     * @param array $fileLines      Array com as linhas de um csv
     * @return array                Array com as keys.
     */
    private function getColumnsKeys(Array $fileLines)
    {
        $keys = explode(self::$delimiterBy, $fileLines[0]);

        for($countKeys = 0; count($keys) > $countKeys; $countKeys++){
            $keys = self::removeWhiteSpaces($keys, $countKeys);
        }
        return $keys;
    }

    /**
     * Método utilizado para remover os caracteres de encapsulamento das strings
     * que contém espaços.
     *
     * @access private
     * @param mixed array|string $fileLines      Array com as linhas de um csv, ou uma string.
     * @param int $index                         Indice do array, valor padrão é 0.
     * @return array|string                      Valores formatados sem os caracteres de
     *                                           encapsulamento de strings com espaços.
     */
    private static function replaceTheEncapsulationOfString($fileLines, $index = 0)
    {
        if(is_array($fileLines)){
            $fileLines[$index] = str_replace(self::$stringEncapsulatedBy, '', $fileLines[$index]);
            return $fileLines;
        }else{
            $fileLines = str_replace(self::$stringEncapsulatedBy, '', $fileLines);
            return $fileLines;
        }
    }

    /**
     * Método para remover os espaços em branco entre o inicio e fim de uma string
     *
     * @access private
     * @param string|array $fileLines   Array com as linhas de um csv ou string
     *                                  para remoção dos espaços em branco.
     * @param int $index                Indice do array, por padrão o valor é 0
     * @return string|array             Array ou string com o valor formatado
     */
    private static function removeWhiteSpaces($fileLines, $index = 0)
    {
        if(is_array($fileLines)){
            $fileLines[$index] = trim($fileLines[$index]);
            return $fileLines;
        }else{
            $fileLines = trim($fileLines);
            return $fileLines;
        }
    }

    public function csvToJson()
    {
        $jsonOutput = null;

        foreach ($this->readyCsvFile() as $csvData){
            $jsonOutput .= json_encode($csvData, JSON_PRETTY_PRINT).",\n";
        }
        $jsonOutput = "[\n".rtrim($jsonOutput, ",\n")."\n]";
        
        return $jsonOutput;
    }
}