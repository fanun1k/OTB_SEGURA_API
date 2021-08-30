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
<<<<<<< HEAD

	public $otbsInsert=[
		'name'=>'required|min_length[5]|max_length[225]' 
=======
	public $activitysInsert=[
		'longitude'=>'required|min_length[1]|max_length[255]',
		'latitude'=>'required|min_length[1]|max_length[255]',
		'alertID'=>'required|min_length[1]|max_length[255]',
		'userID'=>'required|min_length[1]|max_length[255]'
>>>>>>> a177ad54feade05b9ba0ff2ce78d7baa6e6983f4
	];
	
	//--------------------------------------------------------------------
	
	// Rules
	//--------------------------------------------------------------------
}
