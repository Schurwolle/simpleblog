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
                if(starts_with($photo, '/pictures/') && (!file_exists(base_path().'/public'.$photo)) || starts_with($thumb[1], '/pictures/') && (!file_exists(base_path().'/public'.$thumb[1])) || ((!starts_with($photo, '/pictures/')) && (!$this->is_image($photo))) || ((!starts_with($thumb[1], '/pictures/')) && (!$this->is_image($thumb[1]))))
                {
                	return false;
                }
            }
        }
        return true;
	}

	private function is_image($url)
	{
		$url_headers = @get_headers($url,1);
		if (isset($url_headers['Content-Type']) && strpos($url_headers['Content-Type'], 'image/') !== FALSE)
		{
		    return true;
		}
		return false;
	}
}