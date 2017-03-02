<?php

namespace EDValidator\bundles\InputFileBundle\Models;
use EDValidator\bundles\CoreBundle\CSVReader\CSVReader;
use EDValidator\bundles\CoreBundle\Models\ValidateEmailList;

class ListProcessing
{
	private $filePath;

	private $delimitedBy;

	private $groupedBy;

	private $headerTable;

	private $validator;

	public function __construct($filePath, $delimitedBy, $groupedBy, $headerTable)
	{
		$this->filePath    = $filePath;
		$this->delimitedBy = $delimitedBy;
		$this->groupedBy   = $groupedBy;
		$this->headerTable = $headerTable;
	}

	public function execute()
	{

		$csvFile = new CSVReader($this->filePath, $this->delimitedBy, $this->groupedBy, $this->headerTable);

		$data = $this->extractEmails($csvFile->readyCsvFile(),  $this->headerTable);
		$this->validate($data);
	}

	private function extractEmails($list, $headerTable)
	{
		$out = array();
		foreach ($list as $value) {

			if($headerTable === false){
				foreach ($value as $item) {
					if(filter_var($item, FILTER_VALIDATE_EMAIL)){
						$out[] = $item;
					}
				}
				
			}else if(isset($value['email']) && filter_var($value['email'], FILTER_VALIDATE_EMAIL) ){
				$out[] = $value['email'];
			}

		}
		return $out;

	}

	private function validate( Array $list )
	{
		$this->validator = new ValidateEmailList( $list );
		$this->validator->validate();
		
	}

	public function exportValidList()
	{

	}


	public function exportInvalidList()
	{

	}

	public function getValidList()
	{
		if(isset($this->validator)){
			return $this->validator->getValidList();
		}
	}

	public function getInvalidList()
	{
		if(isset($this->validator)){
			return $this->validator->getInvalidList();
		}
	}
}