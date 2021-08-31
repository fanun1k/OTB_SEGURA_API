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
		'email'=>'required|valid_email|is_unique[user.email]|min_length[6]|max_length[40]',
		'password'=>'required|min_length[5]|max_length[15]',
		'phone' => 'required|numeric|is_natural|exact_length[8]',
		'ci' => 'required|numeric|is_natural|is_unique[user.ci]|min_length[6]|max_length[10]',
		'type' => 'required|numeric|is_natural|exact_length[1]',
		'otbID' => 'required|numeric|is_natural|min_length[1]'
	];

	public $usersUpdate=[
		'name'=>'required|alpha|min_length[3]|max_length[60]',
		'password'=>'required|min_length[5]|max_length[15]',
		'phone' => 'required|numeric|is_natural|exact_length[8]',
		'type' => 'required|numeric|is_natural|exact_length[1]',
		'otbID' => 'required|numeric|is_natural|min_length[1]'
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
		'name'=>'required|min_length[6]|max_length[120]',
		'otbID'=> 'required|numeric|is_natural|min_length[1]'
	];
	
	//--------------------------------------------------------------------
	
	// Rules
	//--------------------------------------------------------------------
}
