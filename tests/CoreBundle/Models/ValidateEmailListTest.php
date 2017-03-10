<?php
//TODO to documentation
namespace EDValidateTests\CoreBundle\Models;
use EDValidateTests\Abstracts\PhpUnitEDValidate;
use EDValidator\bundles\CoreBundle\Models\ValidateEmailList;

class ValidateEmailListTest extends PhpUnitEDValidate
{
	private $validate;

	public function setup()
	{
		$this->validate = new ValidateEmailList(
			array(
				'andre@enterprise.ppg.br',
				'jefferson@enterprise.ppg.br',
				'dev.andrematias@gmail.com',
				'test@invaliddomaingmail.com',
				'test@invaliddomaingl.com'
			)
		);
	}

	public function testPrivateMethodGetDomainOfEmail()
	{
		

		$domain = $this->invokeMethod($this->validate, 'getDomainOfEmail', array('andre@invaliddomainnamegmail.com'));

		$expected = 'invaliddomainnamegmail.com';
		$this->assertEquals($expected, $domain);
		return $domain;

	}
	
	/**
	 * @depends testPrivateMethodGetDomainOfEmail
	 * @param  string $domain
	 * @return assert
	 */
	public function testPrivateMethodCheckDomain($domain)
	{
		$expected = false;
	
		$this->assertEquals($expected, $this->invokeMethod($this->validate, 'checkDomain', array($domain)));
	}

	public function testGetValidList()
	{
		$expected = array(
				'andre@enterprise.ppg.br',
				'jefferson@enterprise.ppg.br',
				'dev.andrematias@gmail.com'
			);
		$this->validate->validate();
		$this->assertEquals($expected, $this->validate->getValidList());
	}


	public function testGetInvalidList()
	{
		$expected = array(
				'test@invaliddomaingmail.com',
				'test@invaliddomaingl.com'
			);
		$this->validate->validate();
		$this->assertEquals($expected, $this->validate->getInvalidList());
	}
	
}