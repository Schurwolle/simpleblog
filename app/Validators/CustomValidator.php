<?php 
namespace App\Validators;

class CustomValidator 
{
	public function validateMaxImgs($attribute, $value, $parameters, $validator)
	{
		$oldfiles = glob('pictures/'.$parameters[0].'lb*');
		if(count($oldfiles) + count($value) > 5)
		{
			return false;
		}
		return true;
	}
}