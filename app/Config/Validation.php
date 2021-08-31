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
	public $alertsUpdate=[
		'name'=>'min_length[0]|max_length[255]',
		'state'=>'min_length[1]|max_length[1]'
	];

	public $usersInsert=[
		'name'=>'required|alpha|min_length[3]|max_length[60]',
		'email'=>'required|valid_email|is_unique|min_length[6]|max_length[30]',
		'password'=>'required|min_length[5]|max_length[15]',
		'phone' => 'required|alpha|min_length[8]|max_length[15]',
		'ci' => 'required|alpha|is_unique|min_length[6]|max_length[10]',
		'type' => 'required|numeric|is_natural|alpha|min_length[1]|max_length[1]',
		'otbID' => 'required|numeric|is_natural|alpha|min_length[1]'
	];

	public $otbsInsert=[
		'name'=>'required|min_length[5]|max_length[225]' 
	];
	
	public $activitysInsert=[
		'longitude'=>'required|min_length[1]|max_length[255]',
		'latitude'=>'required|min_length[1]|max_length[255]',
		'alertID'=>'required|min_length[1]|max_length[255]',
		'userID'=>'required|min_length[1]|max_length[255]'
	];

	public $alarmsInsert=[
		'name'=>'required|min_length[6]|max_length[30]',
		'otbID'=> 'required|min_length[1]|max_length[255]'
	];
	
	//--------------------------------------------------------------------
	
	// Rules
	//--------------------------------------------------------------------
}
