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

	public function validateCKEImgs($attribute, $value, $parameters, $validator)
	{
		if(preg_match_all('#<a href="[^<>"]*"[^<>]*><img [^<>]*src="[^<>"]*"[^<>]*/></a>#', $value, $matches))
        {
            for ($i = 0; $i < count($matches[0]); $i++)
            {                
                $photo = substr($matches[0][$i], 9, strpos($matches[0][$i], '"', 9) - 9);
                preg_match('#src="(.*?)"#', $matches[0][$i], $thumb);
                if(!starts_with($photo, '/pictures/') && getimagesize($photo) == false || (!starts_with($thumb[1], '/pictures/') && getimagesize($thumb[1]) == false))
                {
                    return false;
                }
            }
        }
        return true;
	}
}