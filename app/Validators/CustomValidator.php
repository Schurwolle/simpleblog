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
                if(starts_with($photo, '/pictures/')) $photo = substr($photo, 1);
                if(starts_with($thumb[1], '/pictures/')) $thumb[1] = substr($thumb[1], 1);
                if((!starts_with($photo, 'pictures/') && (!filter_var($photo, FILTER_VALIDATE_URL))) || (!getimagesize($photo)) || ((!starts_with($thumb[1], 'pictures/')) && (!filter_var($thumb[1], FILTER_VALIDATE_URL))) || (!getimagesize($thumb[1])))
                {
                    return false;
                }
            }
        }
        return true;
	}
}