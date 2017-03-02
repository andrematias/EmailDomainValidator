<?php
namespace EDValidator\bundles\InputFileBundle\Controllers;
use EDValidator\bundles\CoreBundle\Abstracts\Controller;
use EDValidator\bundles\CoreBundle\PostRequest\PostRequest;
use EDValidator\bundles\CoreBundle\GetFiles\GetFiles;
use EDValidator\bundles\InputFileBundle\Models\ListProcessing;
use EDValidator\bundles\InputFileBundle\Views\InputFile\InputFile AS InputFileView;

class InputFile extends Controller
{

	private $description = array(
		'status' => NULL,
		'totalValids' => 0,
		'totalInvalids' => 0,
		'typeError' => NULL
	);

	public function index()
	{

		$post = new PostRequest();

		if($post->getValue('upload') === 'upload-data'){

			$post->setUploadPath($GLOBALS['paths']['uploads']);

			if( $post->getFiles(new GetFiles()) && ($post->getFileTypeInfo() === 'csv') ){

				$filePath = $post->getUploadPath().$post->getFileNameInfo();
				$process = new ListProcessing($filePath, $post->getValue('delimiter'), $post->getValue('grouping'), (bool)$post->getValue('table-header'));
				$process->execute();

				$validsEmails = $process->getValidList();
				$invalidsEmails = $process->getInvalidList();

				$this->description['status'] = 'success';
				$this->description['totalValids'] = count($validsEmails);
				$this->description['totalInvalids'] = count($invalidsEmails);
			}else{
				$this->description['status'] = 'error';
				$this->description['typeError'] = "This is not a valid csv file!";
				unlink($post->getUploadPath().$post->getFileNameInfo());
			}
		}

		parent::view(new InputFileView($this->description));
	}

}