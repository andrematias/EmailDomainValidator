<?php
namespace EDValidator\bundles\InputFileBundle\Views\InputFile;
use EDValidator\bundles\CoreBundle\Abstracts\View;
use EDValidator\bundles\CoreBundle\Template\Template;

class InputFile extends View
{
	private $args;

	public function __construct($args = null)
	{
		$this->args = $args;
	}

	public function render()
	{
		$this->form();
	}

	private function form()
	{
		$metaTypes = new Template();
		$metaTypes->load('meta_types.html', __DIR__.'/../../../CoreBundle/Views/default/');

		$styleSheets = new Template();
		$styleSheets->load('stylesheets.html', __DIR__.'/../../../CoreBundle/Views/default/');
		$styleSheets->setValue('media_directory', $GLOBALS['site_paths']['media']);

		$form = new Template();
		$form->load('content.html', __DIR__.'/layout/');
		$form->setValue('action_form', '');
		
		$descriptions = null;

		if(!is_null($this->args['status'])){

			$descriptions  = "- <b>Status:</b> {$this->args['status']} <br>";

			if($this->args['status'] === 'error'){
				$descriptions .= "- <b>Error:</b> {$this->args['typeError']} <br>";
			}

			$descriptions .= "- <b>Total e-mail valids:</b> {$this->args['totalValids']} <br>";
			$descriptions .= "- <b>Total e-mail invalids:</b> {$this->args['totalInvalids']} <br>";
		}

		$form->setValue('more_details', $descriptions);


		$formTemplate = new Template();
		$formTemplate->load('default.html', __DIR__.'/../../../CoreBundle/Views/default/');
		$formTemplate->setValue('page_title', 'Email Domain Validator');
		$formTemplate->setValue('extends_meta_types', $metaTypes->render());
		$formTemplate->setValue('extends_css', $styleSheets->render());
		$formTemplate->setValue('extends_content', $form->render());

		printf($formTemplate->render());
	}
}
