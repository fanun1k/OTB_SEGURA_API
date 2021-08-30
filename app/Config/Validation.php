<?php

namespace Config;

use CodeIgniter\Validation\CreditCardRules;
use CodeIgniter\Validation\FileRules;
use CodeIgniter\Validation\FormatRules;
use CodeIgniter\Validation\Rules;

class Validation
{
	//--------------------------------------------------------------------
	// Setup
	//--------------------------------------------------------------------

	/**
	 * Stores the classes that contain the
	 * rules that are available.
	 *
	 * @var string[]
	 */
	public $ruleSets = [
		Rules::class,
		FormatRules::class,
		FileRules::class,
		CreditCardRules::class,
	];

	/**
	 * Specifies the views that are used to display the
	 * errors.
	 *
	 * @var array<string, string>
	 */
	public $templates = [
		'list'   => 'CodeIgniter\Validation\Views\list',
		'single' => 'CodeIgniter\Validation\Views\single',
	];
	public $alerts=[
		'name'=>'required|min_length[3]|max_length[255]'
	];
	public $usersInsert=[
		'name'=>'required|min_length[3]|max_length[255]',
		'password'=>'required|min_length[6]|max_length[255]',
		'phone' => 'required|min_length[3]|max_length[255]',
		'ci' => 'required|min_length[3]|max_length[255]',
		'type' => 'required|min_length[3]|max_length[255]',
		'otbID' => 'required|min_length[3]|max_length[255]'
	];
	
	//--------------------------------------------------------------------
	
	// Rules
	//--------------------------------------------------------------------
}
