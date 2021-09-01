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
		'Name'=>'required|min_length[3]|max_length[100]',
		'Email'=>'required|min_length[6]|max_length[35]',
		'Password'=>'required|min_length[5]|max_length[60]',
		'Phone' => 'required|exact_length[8]',
		'Ci' => 'required|min_length[6]|max_length[15]',
		'Type' => 'required|exact_length[1]',
		'OtbID' => 'required|min_length[1]'
	];

	public $usersUpdate=[
		'name'=>'min_length[3]|max_length[100]',
		'password'=>'min_length[5]|max_length[35]',
		'phone' => 'exact_length[60]',
		'type' => 'exact_length[1]'
	];

	public $otbsInsert=[
		'Name'=>'required|min_length[5]|max_length[100]' 
	];
	public $otbsUpdate=[
		'name'=>'min_length[5]|max_length[100]'
	];
	
	public $activitysInsert=[
		'longitude'=>'required|min_length[1]|max_length[255]',
		'latitude'=>'required|min_length[1]|max_length[255]',
		'alertID'=>'required|min_length[1]|max_length[255]',
		'userID'=>'required|min_length[1]|max_length[255]'
	];

	public $alarmsInsert=[
		'Name'=>'required|min_length[4]|max_length[100]',
		'OtbID'=> 'required|min_length[1]'
	];

	public $alarmsUpdate=[
		'name'=>'min_length[4]|max_length[100]'
	];
	
	//--------------------------------------------------------------------
	
	// Rules
	//--------------------------------------------------------------------
}
