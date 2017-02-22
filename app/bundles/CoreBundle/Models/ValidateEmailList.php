<?php
//TODO to documentation
namespace EDValidate\bundles\CoreBundle\Models;

class ValidateEmailList
{
	private $list;

	protected $validList = array();

	protected $invalidList = array();

	public function __construct(Array $list)
	{
		$this->list = $list;
	}

	protected function setEmailInList($email, $list)
	{
		$email = filter_var($email, FILTER_VALIDATE_EMAIL);
		if($email){
			switch ($list) {
				case 'valid':
					$this->validList[] = $email;
					break;
				
				case 'invalid':
					$this->invalidList[] = $email;
					break;
			}
		}
	}

	public function getValidList()
	{
		return $this->validList;
	}

	public function getInvalidList()
	{
		return $this->invalidList;
	}

	public function validate()
	{
		foreach ($this->list as $email) {
			
			if( $this->checkDomain( $this->getDomainOfEmail( $email ) ) ){
				$this->setEmailInList($email, 'valid');
			}else{
				$this->setEmailInList($email, 'invalid');
			}
		}
	}

	private function checkDomain($domain)
	{
		return checkdnsrr($domain, 'MX');
	}

	private function getDomainOfEmail($email)
	{
		list($name, $domain) = explode('@', $email);
		return $domain;
	}
}