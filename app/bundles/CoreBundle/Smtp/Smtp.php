<?php
/**
 * Class Smtp responsible to conversation with server
 * 
 * @author André Matias <dev.andrematias@gmail.com>
 * @version 0.0.1
 * @license GPL3
 * @package EDValidator	
 * @see http://www.samlogic.net/articles/smtp-commands-reference.htm 		Examples to SMTP and Client conversation
 * 		https://users.cs.cf.ac.uk/Dave.Marshall/PERL/node175.html
 *
 * TO DO Implementation of methods to use constants:
 * DATA, RSET, SEND, SOML, SAML, VRFY, EXPN, HELP
 */ 
namespace EDValidator\CoreBundle\Smtp;

class Smtp
{
	/**
	 * Nonstandard success response.
	 * @see http://www.rfc-base.org/txt/rfc-876.txt
	 * @var integer
	 */
	const $NOSTANDART_SUCCESS_RESPONSE = 200;
	
	/**
	 * System status, or system help reply
	 * @var integer
	 */
	const $SYSTEM_STATUS = 211;

	/**
	 * Help message
	 * @var integer
	 */
	const $HELP_MESSAGE = 214;

	/**
	 * <domain> Service ready
	 * @var integer
	 */
	const $SERVICE_READY = 220;

	/**
	 * <domain> Service closing transmission channel
	 * @var integer
	 */
	const $CLOSE_TRANSMISSION_CHANNEL = 221;
	
	/**
	 * Requested mail action okay, completed
	 * @var integer
	 */
	const $COMPLETED = 250;

	/**
	 * User not local; will forward to <forward-path>
	 * @var integer
	 */
	const $USER_NOT_LOCAL = 251;

	/**
	 * Cannot VRFY user, but will accept message and attempt delivery
	 * @var integer
	 */
	const $USER_NOT_VERIFIED = 252;	

	/**
	 * Start mail input; end with <CRLF>.<CRLF>
	 * @var integer
	 */
	const $INIT_MAIL_INPUT = 354;

	/**
	 * <domain> Service not available, closing transmission channel
	 * @var integer
	 */
	const $SERVICE_NOTE_AVALIABLE = 421;

	/**
	 * Requested mail action not taken: mailbox unavailable
	 * @var integer
	 */
	const $MAIL_ACTION_MAILBOX_UNAVALIABLE = 450;

	/**
	 * Requested action aborted: local error in processing
	 * @var integer
	 */
	const $ERROR_IN_PROCESSING = 451;

	/**
	 * Requested action not taken: insufficient system storage
	 * @var integer
	 */
	const $INSUFFICIENT_STORAGE = 452;

	/**
	 * Syntax error, command unrecognised
	 * @var integer
	 */
	const $COMMAND_UNRECOGNISED = 500;
	
	/**
	 * Syntax error in parameters or arguments
	 * @var integer
	 */
	const $ARGS_SYNTAX_ERROR = 501;

	/**
	 * Command not implemented
	 * @var integer
	 */
	const $COMMAND_NOT_IMPLEMENTED = 502;

	/**
	 * Bad sequence of commands
	 * @var integer
	 */
	const $BAD_SEQUENCE_OF_COMMANDS = 503;

	/**
	 * Command parameter not implemented
	 * @var integer
	 */
	const $PARAM_NOT_IMPLEMENTED = 504;
	
	/**
	 * <domain> does not accept mail
	 * @see http://www.rfc-base.org/txt/rfc-1846.txt
	 * @var integer
	 */
	const $DOMAIN_NOT_ACCEPT_EMAIL = 521;

	/**
	 * Access denied
	 * @var integer
	 */
	const $ACCESS_DENIED = 530;

	/**
	 * Requested action not taken: mailbox unavailable
	 * @var integer
	 */
	const $REQUEST_MAILBOX_UNAVALIABLE = 550;

	/**
	 * User not local; please try <forward-path>
	 * @var integer
	 */
	const $USER_NOT_LOCAL_TRY = 551;

	/**
	 * Requested mail action aborted: exceeded storage allocation
	 * @var integer
	 */
	const $EXCEEDED_STORAGE = 552;

	/**
	 * Requested action not taken: mailbox name not allowed
	 * @var integer
	 */
	const $MAILBOX_NOT_ALLOWED = 553;

	/**
	 * Transaction failed
	 * @var integer
	 */
	const $TRANSACTION_FAILED = 554;

	/**
	 * Command to request to Initiate the conversation
	 * @var string
	 */
	const $REQUEST_HELO = 'HELO';

	/**
	 * Command to request to Initiate the conversation Extended HELO
	 * @var string
	 */
	const $REQUEST_EHLO = 'EHLO';

	/**
	 * Command to specifies the e-mail address of the sender.
	 * @var string
	 */
	const $REQUEST_MAIL = 'MAIL FROM:';

	/**
	 * Command to specifies the e-mail address of the recipient.
	 * @var string
	 */
	const $REQUEST_RCPT = 'RCPT TO:';

	/**
	 * Command to set the DATA command starts the transfer of the message contents
	 * @var string
	 */
	const $REQUEST_DATA = 'DATA';

	/**
	 * Command to Abort the current mail transaction.
	 * @var string
	 */
	const $REQUEST_RSET = 'RSET';

	/**
	 * Command to Sends a message to a user's terminal instead of a mailbox.
	 * @var string
	 */
	const $REQUEST_SEND = 'SEND';

	/**
	 * Commando to sends a message to a user's terminal if they are logged on; 
	 * otherwise, sends the message to the user's mailbox.
	 * @var string
	 */
	const $REQUEST_SOML = 'SOML';

	/**
	 * Command to sends a message to a user's terminal and to a user's mailbox
	 * @var string
	 */
	const $REQUEST_SAML = 'SAML';

	/**
	 * Command to Verifies the existence and user name of a given mail address.
	 * Attention: Not recommended, this command can be blocked by firewalls
	 * @var string
	 */
	const $REQUEST_VRFY = 'VRFY';

	/**
	 * Command to indicate that using an mailing list
	 * @var string
	 */
	const $REQUEST_EXPN = 'EXPN';

	/**
	 * Command for help from the mail server
	 * @var string
	 */
	const $REQUEST_HELP = 'HELP';

	/**
	 * Command to indicate that the conversation is over.
	 * @var string
	 */
	const $REQUEST_QUIT = 'QUIT';


	/**
	 * MX type record in DNS to direct the emails of the domain to server
	 * hosting the accounts of users of domain
	 * @var string
	 */
	protected $mxRegister;

	/**
	 * Hostname of the server actual
	 * @var string
	 */
	protected $httpHost;

	/**
	 * Limit of the buffer of the TCP connection in kbytes
	 * @var int
	 */
	protected $tcpBufferLimit;

	/**
	 * Email used for send requests
	 * @var string
	 */
	protected $from;

	/**
	 * Port to conect the TCP
	 * @var int
	 */
	protected $port;

	/**
	 * Output to SMTP requests
	 * @var array
	 */
	private $smtpOutput;

	/**
	 * The connection with server
	 * @var handle
	 */
	private $connection;


	/**
	 * Construct method of this class
	 * @param string $mxRegister 	Host from server email
	 * @param string $from 			Email to From
	 * @param string $httpHost 		Hostname that server
	 * @param integer $port 		Port number to access TCP
	 */
	public function __construct( $mxRegister, $from, $httpHost, $port = 25 )
	{
		$this->mxRegister     = $mxRegister;
		$this->from 	      = $from;
		$this->httpHost       = $httpHost;
		$this->port 	      = $port;
	}

	/**
	 * Open the connection with mail server
	 * @return handle
	 */
	public function connectServer()
	{
		$this->connection = fsockopen( $this->mxRegister, $this->port );
	}

	/**
	 * Close the connection with mail server
	 * @return boolean
	 */
	public function closeConnection( )
	{
		return fclose( $this->connection );
	}

	/**
	 * Get responses of the mail server
	 * @param  integer $tcpBufferLimit 	 	Limit of the buffer to reply output
	 * @return string
	 */
	protected function getServiceResponse( $tcpBufferLimit = 1024)
	{
		$this->tcpBufferLimit = $tcpBufferLimit;

		return fgets($this->connection, $tcpBufferLimit);
	}

	/**
	 * Start the conversation with the mail server
	 * @return void
	 */
	protected function initConversationWithHelo()
	{
		fputs($this->connection, "{self::HELO} {$this->httpHost} \n\r");
	}

	/**
	 * Start the conversation with mail server, but with the extended helo
	 * @return void
	 */
	protected function initConversationWithEhlo()
	{
		fputs($this->connection, "{self::EHLO} {$this->httpHost} \n\r");
	}

	/**
	 * Set the From of the email
	 * @return void
	 */
	protected function setMailFrom()
	{
		fputs($serverConnection, "{self::MAIL_FROM} <{$this->from}> \r\n");
	}

	/**
	 * @param  string Email to send messages
	 * @return void
	 */
	protected function receptedTo( $email )
	{
		fputs($this->connection, "{self::RCPT_TO} <{$email}> \r\n");
	}

	/**
	 * Send Message data email
	 * 
	 * @param  Smtp Connection with server
	 * @param  string $from 	Email address from send emails
	 * @param  Array $header 	subject, message, attachment, contentType:[text/plain][multipart/mixed][text/html]...
	 * @see  https://msdn.microsoft.com/en-us/library/ms526560(v=exchg.10).aspx 	References to MIME Messages
	 * @return string
	 */
	protected function sendData( Smtp $serverConnection, $from, Array $header )
	{
		//TO DO implementation
	}

	/**
	 * Exit of conversation with mail server
	 * @return void
	 */
	protected function quitConversation()
	{
		fputs($serverConnection, "{self::QUIT}\r\n");
	}

}