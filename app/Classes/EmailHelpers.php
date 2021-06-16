<?php

namespace App\Classes;

use GuzzleHttp\Client;

class EmailHelpers
{
	private string $to;
	private string $templateId;
	private array $variables;
	private Client $httpClient; 

	public function __construct(string $to, string $templateId, array $variables)
	{
		$this->to = $to;
		$this->templateId = $templateId;
		$this->variables = $variables;
		$this->httpClient = new Client([
			'base_uri' => 'https://api.mailersend.com/', 
			'headers' => [
				'Authorization' => 'Bearer ' . env('MAILERSEND_TOKEN')
			]
		]);
	}

	public function sendEmail() {

		$response = $this->httpClient->post('/v1/email', [
			'form_params' => [
				'from' => [
					'email' => env('MAIL_FROM_ADDRESS'),
					'name' => env('MAIL_FROM_NAME')
				],
				'to' => [
					[
						'email' => $this->to
					]
				],
				'template_id' => $this->templateId,
				'variables' => [
					[
						'email' => $this->to,
						'substitutions' => $this->variables
					]
				]
			]
		]);

		
	}


}