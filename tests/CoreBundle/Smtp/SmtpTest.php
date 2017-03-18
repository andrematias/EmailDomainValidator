<?php

// TO DO Documentation
namespace EDValidatorTests\CoreBundle\Smtp;

use EDValidatorTests\Abstracts\PhpUnitEDValidator;
use EDValidator\CoreBundle\Smtp\Smtp;

class SmtpTest extends PhpUnitEDValidator{

    const FROM_EMAIL = 'dev.andrematias@gmail.com';

    private $smtp;
     
   protected function setup()
   {
       $this->smtp = new Smtp('127.0.0.1', 'localhost');
        $this->smtp->connectServer();
   }

   public function testGetServiceResponse()
   { 
       $responseServer = $this->invokeMethod( $this->smtp, 'getServiceResponse', array() );
       $this->assertInternalType('string', $responseServer);
   }
   
   public function testServerConnection( )
   {
       $responseServer =  $responseServer = $this->invokeMethod( $this->smtp, 'getServiceResponse', array() );

       $expected = "/^".Smtp::SERVICE_READY."/";
       
       $this->assertRegExp($expected, $responseServer, "The Server Response isn't ".Smtp::SERVICE_READY);

   }
    
    public function testInitConversationWithHelo()
    {
        if( preg_match( "/^".Smtp::SERVICE_READY."/", $this->invokeMethod( $this->smtp, 'getServiceResponse', array() ) ) ){
            $this->invokeMethod( $this->smtp, 'initConversationWithHelo', array() );
        }

        $responseServer = $this->invokeMethod( $this->smtp, 'getServiceResponse', array() );
        
        $expected = "/^".Smtp::COMPLETED."/";
       
        $this->assertRegExp($expected, $responseServer, "The Server Response isn't ".Smtp::COMPLETED);
        
    }


    public function testSetMailFrom()
    {
        if( preg_match( "/^".Smtp::SERVICE_READY."/", $this->invokeMethod( $this->smtp, 'getServiceResponse', array() ) ) ){
            $this->invokeMethod( $this->smtp, 'setMailFrom', array( 'dev.andrematias@gmail.com' ) );
        }

        $responseServer = $this->invokeMethod( $this->smtp, 'getServiceResponse', array() );
        
        $expected = "/^".Smtp::COMPLETED."/";
       
        $this->assertRegExp($expected, $responseServer, "The Server Response isn't ".Smtp::COMPLETED);
        
    }

   
   public function testCloseServerConnectionReturn()
   {
       $actual = $this->smtp->closeConnection();
       $this->assertInternalType('boolean', $actual);
       return $actual;        
   }
   
   /**
    * @depends testCloseServerConnectionReturn
    */
   public function testCloseServerConnection( $connectionStatus ) 
   {
      $this->assertEquals(true, $connectionStatus);
      
   }
       
}
