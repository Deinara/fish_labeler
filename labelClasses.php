<?php

class FishLabel
{
	private $properties = [];
	
	private $error = false;
	
	public function __construct()
    {
		$this->getValues();
	}
	
	private function getValues()
    {
		$data = file_get_contents("fish_list.json");
		if ($data==false){
			$this->error = true;
			return;
		}
		
		$decoded = json_decode($data, true);
		if ($decoded==false){
			$this->error = true;
			return;
		}
		
		$this->properties = $decoded;
	
	    return;
	}
	
	private function createCard($fishName)
	{
		$fishInfo = $this->properties[$fishName];
		$card = "";
		$card .= "<div class='cardcontainer'>";
		
		$card .= "<div class='cardimagecontainer'>";
		$card .= "<img src='".$this->findImage($fishInfo['image_url'])."' alt='".$fishInfo['name']."' class='cardimage'>";
		$card .= "</div>";
		
		$card .= "<div class='cardinfocontainer'>";
		$card .= "<div class='cardinfoheader'>";
		$card .= "<span class='cardtitle'>".$fishInfo['name']."</span><br>";
		$card .= "<span class='cardsubtitle'>".$fishInfo['latin_name']."</span>";
		$card .= "</div>";		
		$card .= "<div class='cardinfodata'>";
		
		//check if info is there and display it
		if(!empty($fishInfo['region']))
		{
			$card .= "<i class='fa fa-globe'></i> ".$fishInfo['region'];
	    }
		$card .= "</br>";
		
		if(!empty($fishInfo['food_type']))
		{
			$card .= "<i class='fa fa-cutlery'></i> ".$fishInfo['food_type'];
	    }
		$card .= "</br>";
		
		if(!empty($fishInfo['temper']))
		{
			$card .= "<i class='fa fa-smile-o'></i> ".$fishInfo['temper'];
	    }
		$card .= "</br>";
		
		if(!empty($fishInfo['group_size']))
		{
			$card .= "<i class='fa fa-users'></i> ".$fishInfo['group_size'];
	    }
		$card .= "</br>";
		
		$card .= "</div>";		//cardinfodata
		$card .= "<div class='cardinfonumbers'>";
		
		//check if info is there and display it
		if(!empty($fishInfo['length']['min']) || !empty($fishInfo['length']['max']) || !empty($fishInfo['length']['avg']))
		{
			$card .= "<i class='fa fa-arrows-h'></i> ".$this->tripleInfoToOne($fishInfo['length'])." cm";
	    }
		$card .= "</br>";
		
		if(!empty($fishInfo['temps']['min']) || !empty($fishInfo['temps']['max']) || !empty($fishInfo['temps']['ideal']))
		{
			
			$card .= "<i class='fa fa-thermometer-half'></i>&nbsp&nbsp".$this->tripleInfoToOne($fishInfo['temps'])." &degC";
	    }
		$card .= "</br>";
		
		if(!empty($fishInfo['min_aquarium_size']))
		{
			$card .= "<i class='fa fa-square-o'></i> ".$fishInfo['min_aquarium_size']." cm";
	    }
		$card .= "</br>";
		
		if(!empty($fishInfo['pH']['min']) || !empty($fishInfo['pH']['max']) || !empty($fishInfo['pH']['ideal']))
		{
			$card .= "<i class='fa fa-lemon-o'></i> ".$this->tripleInfoToOne($fishInfo['pH']);
	    }
		$card .= "</br>";
		
		$card .= "</div>";	//cardinfonumbers	
		$card .= "</div>"; //cardinfocontainer
		
		$card .= "</div>"; //cardcontainer
		
		return $card;
	}
	
	private function findImage($url)
	{
		if (!empty($url))
		{
			if (strpos("www", $url) === false && strpos("http", $url) === false){
				if (file_exists ( $url ))
				{
					return $url;
				}
			}
			$array = get_headers($url);
			$string = $array[0];
			if(strpos($string,"200"))
			{
			    return $url;
			}
		}
		return 'no_image.png';
	}
	
	private function tripleInfoToOne($info)
	{
		$condensed = "";
		$one=$info['min'];
		$two = (empty($info['avg'])) ? "" : $info['avg'];
		$two = (empty($info['ideal'])) ? $two : $info['ideal'];
		$three=$info['max'];
		
		if (empty($one))
		{
			$condensed .= "&nbsp&nbsp&nbsp";
		}
		else {
			$condensed .= $one." - ";
		}
		if (empty($two))
		{
			$condensed .= "&nbsp&nbsp&nbsp";
		}
		else {
			$condensed .= "(".$two.")";
		}
		if (empty($three))
		{
			$condensed .= "&nbsp&nbsp&nbsp";
		}
		else {
			$condensed .= " - ".$three;
		}
		return $condensed;
	}
	
	private function createAttribution($fishName)
	{
		$fishInfo = $this->properties[$fishName];
		
		$attrib = "";
		$attrib .= "<div class='attributionline'>";
		$attrib .= $fishInfo['name']." image license: ".$fishInfo['image_license'].".";
		if ($fishInfo['image_requires_attribution']==true){
			$attrib .= "<br>".$fishInfo['image_attribution'];
			if (!empty($fishInfo['image_attribution_url'])){
				$attrib .= " - <a href='".$fishInfo['image_attribution_url']."''>".substr($fishInfo['image_attribution_url'], 0, 50);
				if (strlen($fishInfo['image_attribution_url'])>50){
					$attrib .= "...";
				}
				$attrib .= "</a>";
			}
		}
		$attrib .= "</div><br>";
		
		return $attrib;
	}
	
	private function listAllFishNames()
	{
		$list = [];
		foreach ($this->properties as $key => $value)
		{
			$list[] = $key;
		}
		
		return $list;
	}
	
	public function createAllCards()
	{
		$cards = "";
		
        $fishNames = $this->listAllFishNames();
		
		foreach ($fishNames as $value)
		{
			$cards .= $this->createCard($value);
		}
		
		return $cards;
	}
	
	public function createAllAttributions()
	{
		$attribs = "";
		
        $fishNames = $this->listAllFishNames();
		
		foreach ($fishNames as $value)
		{
			$attribs .= $this->createAttribution($value);
		}
		
		return $attribs;
	}
}