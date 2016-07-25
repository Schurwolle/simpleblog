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
                if(starts_with($photo, '/pictures/') && (!file_exists(base_path().'/public'.$photo)) || starts_with($thumb[1], '/pictures/') && (!file_exists(base_path().'/public'.$thumb[1])) || ((!starts_with($photo, '/pictures/')) && ((!$this->url_exists($photo)) || (!getimagesize($photo)))) || ((!starts_with($thumb[1], '/pictures/')) && ((!$this->url_exists($thumb[1])) || (!getimagesize($thumb[1])))))
                {
                	return false;
                }
            }
        }
        return true;
	}

	private function url_exists($url)
	{
		$url_headers = @get_headers($url);
		if($url_headers[0] == 'HTTP/1.1 404 Not Found') {
		    return false;
		}
		return true;
	}
}