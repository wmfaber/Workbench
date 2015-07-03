<?php
error_reporting(0);
Class Mascot_Class {
	
	static function first_capitials($str){
		$arr = explode(" ", $str);
		$new_string = "";
		foreach($arr as $value){
			$value = strtolower($value);
			$new_string .= ucfirst($value)." ";
		}
		return $new_string;
	}
	static function is_valid_utf8($str) {
    return (bool)preg_match("//u", $str);
	}
	
	static function insert_nalevering($import){
	
		$data_object = array();

		foreach($import['values'] as $key => $value) {
			$data_object[$key]['bedrijf'] = "nalevering";
			$data_object[$key]['aantal'] = "1";
			foreach($value as $k => $v)
			{
				$v = $v ? $v : "";
				$k = str_replace(" ", "", $k);
				switch($k){
					case "PRODUCT":
					$data_object[$key]['product'] = self::first_capitials($v);
					break;
					case "NAAM":
					$data_object[$key]['naam'] .= self::first_capitials($v);
					break;
					case "STRAAT":
					$data_object[$key]['adres'] = self::first_capitials($v);
					break;
					case "POSTCODE":
					$data_object[$key]['postcode'] = $v;
					break;
					case "PLAATS":
					$data_object[$key]['plaats'] = self::first_capitials($v);
					break;
					case "LAND":
					$data_object[$key]['land'] = self::first_capitials($v);
					break;
					case "ENVELOP":
					$data_object[$key]['envelop'] = $v;
					break;
					case "REDEN":
					$data_object[$key]['status'] = $v;
					break;
				}
			}
		}
		return $data_object;
	}
	
	static function insert_voordeelvanger($import){
		$new = array();
		foreach ($import['values'] as $num => $array) {
			foreach ($array as $key => $value){
				$new[$num][$key] = iconv('ASCII', 'UTF-8//IGNORE', $value);
			}
		}
		
		foreach($new as $key => $value) { 
			if($key != 2) {
				$data_object[$key]['bedrijf'] = "Voordeelvanger";
				$data_object[$key]['naam'] = "";
				$data_object[$key]['adres'] = "";
				
				foreach($value as $k => $v)
				{
					$v = $v ? $v : "";
					$k = str_replace(" ", "", $k);
					switch($k){
						case "1":
						$data_object[$key]['product'] = self::first_capitials($v);
						break;
						case "3":
						case "4":
						$data_object[$key]['naam'] .= self::first_capitials($v);
						break;
						case "5":
						case "6":
						case "7":
						$data_object[$key]['adres'] .= self::first_capitials($v);
						break;
						case "8":
						$data_object[$key]['postcode'] = $v;
						break;
						case "9":
						$data_object[$key]['plaats'] = self::first_capitials($v);
						break;
						case "10":
						$data_object[$key]['land'] = self::first_capitials($v);
						break;
						case "0":
						$data_object[$key]['partner'] = self::first_capitials($v);
						break;
						case "2":
						$data_object[$key]['aantal'] = $v;
						break;
					}
				}
				if(!isset($data_object[$key]['product']) ||  $data_object[$key]['product'] == '')
				{
					$data_object[$key]['product'] = "Geen Product naam gevonden";
				}
				$data_object[$key]['retour'] = "Voordeelvanger - T.A.V. Afd. Retouren - Postbus 1906 - 4801BX Breda - NL";
			}
		}
		return $data_object;
	}
	
	static function insert_6deals( $import ) {

		$data_object = array();

		foreach($import['values'] as $key => $value) {
			$data_object[$key]['bedrijf'] = "6deals";
			foreach($value as $k => $v)
			{
				$v = $v ? $v : "";
				$k = str_replace(" ", "", $k);
				switch($k){
					case "ItemName":
					$data_object[$key]['product'] = self::first_capitials($v);
					break;
					case "ShippingName":
					$data_object[$key]['naam'] = self::first_capitials($v);
					break;
					case "ShippingStreet":
					$data_object[$key]['adres'] = self::first_capitials($v);
					break;
					case "ShippingZip":
					$data_object[$key]['postcode'] = $v;
					break;
					case "ShippingCity":
					$data_object[$key]['plaats'] = self::first_capitials($v);
					break;
					case "ShippingCountryName":
					$data_object[$key]['land'] = self::first_capitials($v);
					break;
					case "OrderDate":
					$data_object[$key]['partner'] = $v;
					break;
					case "Quantity":
					$data_object[$key]['aantal'] = $v;
					break;
					case "OrderNumber":
					$data_object[$key]['vendor_order_id'] = $v;
					break;
				}
			}
			if(!isset($data_object[$key]['product']) ||  $data_object[$key]['product'] == '')
			{
				$data_object[$key]['product'] = "Geen Product naam gevonden";
			}
			$data_object[$key]['retour'] = "Postbus 263, 3860AG Nijkerk - NL";
			
		}

		return $data_object;
	}

	static function insert_1dayfly( $import ) {

		$data_object = array();

		foreach($import['values'] as $key => $value) {
			$data_object[$key]['bedrijf'] = "1dayfly";
			$data_object[$key]['naam'] = "";
			foreach($value as $k => $v)
			{
				$v = $v ? $v : "";
				$k = str_replace(" ", "", $k);
				switch($k){
					case "product":
					$data_object[$key]['product'] = self::first_capitials($v);
					break;
					case "aflevervoornaam":
					case "afleverachternaam":
					$data_object[$key]['naam'] .= self::first_capitials($v);
					break;
					case "afleveradres":
					$data_object[$key]['adres'] = self::first_capitials($v);
					break;
					case "afleverpostcode":
					$data_object[$key]['postcode'] = $v;
					break;
					case "afleverstad":
					$data_object[$key]['plaats'] = self::first_capitials($v);
					break;
					case "afleverland":
					$data_object[$key]['land'] = self::first_capitials($v);
					break;
					case "aantal":
					$data_object[$key]['aantal'] = $v;
					break;
					case "ordernummer":
					$data_object[$key]['vendor_order_id'] = $v;
					break;
				}
			}
			if(!isset($data_object[$key]['product']) ||  $data_object[$key]['product'] == '')
			{
				$data_object[$key]['product'] = "Geen Product naam gevonden";
			}
			$data_object[$key]['retour'] = "1DayFly.com BV - Saturnusstraat 60 unit 61 - 2516 AH DEN HAAG - NL";
		}

		return $data_object;
	}

	static function insert_actievandedag( $import ) {

		$data_object = array();

		foreach($import['values'] as $key => $value) {
			$data_object[$key]['bedrijf'] = "Actievandedag";
			$data_object[$key]['naam'] = "";
			$data_object[$key]['adres']  = '';
			foreach($value as $k => $v)
			{
				$v = $v ? $v : "";
				$k = str_replace(" ", "", $k);
				switch($k){
					case "2":
					$data_object[$key]['product'] = self::first_capitials($v);
					break;
					case "FirstName":
					case "LastName":
					$data_object[$key]['naam'] .= self::first_capitials($v);
					break;
					case "Addr1":
					case "Addr1No.":
					$data_object[$key]['adres'] .= self::first_capitials($v);
					break;
					case "Zip":
					$data_object[$key]['postcode'] = $v;
					break;
					case "City":
					$data_object[$key]['plaats'] = self::first_capitials($v);
					break;
					case "Country":
					$data_object[$key]['land'] = self::first_capitials($v);
					break;
					case "3":
					$data_object[$key]['aantal'] = $v;
					break;
					case "id":
					$data_object[$key]['vendor_order_id'] = $v;
					break;
				}
			}
			if(!isset($data_object[$key]['product']) ||  $data_object[$key]['product'] == '')
			{
				$data_object[$key]['product'] = "Geen Product naam gevonden";
			}
			$data_object[$key]['retour'] = "Postbus 263, 3860AG Nijkerk - NL";
		}

		return $data_object;
	}

	static function insert_groupdel( $import ) {

		$data_object = array();

		foreach($import['values'] as $key => $value) {
			$data_object[$key]['bedrijf'] = "groupdel";
			$data_object[$key]['naam'] = "";

			foreach($value as $k => $v)
			{
				$v = $v ? $v : "";
				$k = str_replace(" ", "", $k);
				switch($k){

					case "CustomerFirstname":
					case "CustomerLastname":
					$data_object[$key]['naam'] .= self::first_capitials($v);
					break;
					case "Address":
					$data_object[$key]['adres'] = self::first_capitials($v);
					break;
					case "Zipcode":
					$data_object[$key]['postcode'] = $v;
					break;
					case "City":
					$data_object[$key]['plaats'] = self::first_capitials($v);
					break;
					case "Country":
					$data_object[$key]['land'] = self::first_capitials($v);
					break;
					case "OrderIncrementID":
					$data_object[$key]['vendor_order_id'] = $v;
					break;
				}
			}
			if(!isset($data_object[$key]['product']) ||  $data_object[$key]['product'] == '')
			{
				$data_object[$key]['product'] = "Geen Product naam gevonden";
			}
			$data_object[$key]['retour'] = "Postbus 263, 3860AG Nijkerk - NL";
		}

		return $data_object;
	}

	static function insert_groupon( $import ) {

		$data_object = array();

		foreach($import['values'] as $key => $value) {
			$data_object[$key]['bedrijf'] = "groupon";
			$data_object[$key]['adres'] = "";
			foreach($value as $k => $v)
			{
				$v = $v ? $v : "";
				$k = str_replace(" ", "", $k);
				switch($k){
					case "item_name":
					$data_object[$key]['product'] = self::first_capitials($v);
					break;
					case "shipment_address_name":
					$data_object[$key]['naam'] .= self::first_capitials($v);
					break;
					case "shipment_address_street":
					case "shipment_address_street_2":
					$data_object[$key]['adres'] = self::first_capitials($v);
					break;
					case "shipment_address_postal_code":
					$data_object[$key]['postcode'] = $v;
					break;
					case "shipment_address_city":
					$data_object[$key]['plaats'] = self::first_capitials($v);
					break;
					case "shipment_address_country":
					$data_object[$key]['land'] = self::first_capitials($v);
					break;
					case "quantity_requested":
					$data_object[$key]['aantal'] = $v;
					break;
					case "order_date":
					$data_object[$key]['partner'] = $v;
					break;
					case "fulfillment_line_item_id":
					$data_object[$key]['vendor_order_id'] = $v;
					break;
					
				}
			}
			if(!isset($data_object[$key]['product']) ||  $data_object[$key]['product'] == '')
			{
				$data_object[$key]['product'] = "Geen Product naam gevonden";
			}
			$data_object[$key]['retour'] = "Groupon Returns - Eekboerstraat 25 - 7575AV Oldenzaal - NL";
		}

		return $data_object;
	}

	static function insert_ichica( $import ) {

		$data_object = array();

		foreach($import['values'] as $key => $value) {
			$data_object[$key]['bedrijf'] = "ichica";
			$data_object[$key]['naam'] = "";
			foreach($value as $k => $v)
			{
				$v = $v ? $v : "";
				$k = str_replace(" ", "", $k);
				switch($k){
					case "order_products":
					$data_object[$key]['product'] = self::first_capitials($v);
					break;
					case "order_shipp_firstname":
					case "order_shipp_lastname":
					$data_object[$key]['naam'] .= self::first_capitials($v);
					break;
					case "order_shipp_address":
					$data_object[$key]['adres'] = self::first_capitials($v);
					break;
					case "order_shipp_zipcode":
					$data_object[$key]['postcode'] = $v;
					break;
					case "order_shipp_city":
					$data_object[$key]['plaats'] = self::first_capitials($v);
					break;
					case "order_shipp_country_iso":
					$data_object[$key]['land'] = self::first_capitials($v);
					break;
					case "order_quantity":
					$data_object[$key]['aantal'] = $v;
					break;
					case "order_id":
					$data_object[$key]['vendor_order_id'] = $v;
					break;
				}
			}
			if(!isset($data_object[$key]['product']) ||  $data_object[$key]['product'] == '')
			{
				$data_object[$key]['product'] = "Geen Product naam gevonden";
			}
			$data_object[$key]['retour'] = "Postbus 263, 3860AG Nijkerk - NL";
		}

		return $data_object;
	}


	static function insert_marktplaats( $import ) {

		$data_object = array();

		foreach($import['values'] as $key => $value) {
			$data_object[$key]['bedrijf'] = "marktplaats";
			$data_object[$key]['naam'] = "";
			$data_object[$key]['adres'] = "";
			foreach($value as $k => $v)
			{
				$v = $v ? $v : "";
				$k = str_replace(" ", "", $k);
				switch($k){
					case "Firstname":
					case "Lastname":
					$data_object[$key]['naam'] .= self::first_capitials($v);
					break;
					case "Streetaddress":
					case "Housenumber":
					$data_object[$key]['adres'] .= self::first_capitials($v);
					break;
					case "Zipcode":
					$data_object[$key]['postcode'] = $v;
					break;
					case "City":
					$data_object[$key]['plaats'] = self::first_capitials($v);
					break;
					case "Redeemed":
					$data_object[$key]['product'] = self::first_capitials($v);
					break;
					case "Purchased":
					$data_object[$key]['partner'] = $v;
					break;
					case "Vouchercode":
					$data_object[$key]['vendor_order_id'] = $v;
					break;
				}
			}
			if(!isset($data_object[$key]['product']) ||  $data_object[$key]['product'] == '')
			{
				$data_object[$key]['product'] = "Geen Product naam gevonden";
			}
			$data_object[$key]['retour'] = "Postbus 263, 3860AG Nijkerk - NL";
		}

		return $data_object;
	}

	static function insert_onedayonly( $import ) {

		$data_object = array();

		foreach($import['values'] as $key => $value) {
			$data_object[$key]['bedrijf'] = "Onedayonly";
			$data_object[$key]['naam'] = "";
			$data_object[$key]['adres'] = "";
			foreach($value as $k => $v)
			{
				$v = $v ? $v : "";
				$k = str_replace(" ", "", $k);
				switch($k){
					case "titel":
					case "initialen":
					case "achternaam":
					$data_object[$key]['naam'] .= self::first_capitials($v);
					break;
					case "straat":
					case "nr.":
					case "nrtv.":
					$data_object[$key]['adres'] .= self::first_capitials($v);
					break;
					case "postcode":
					$data_object[$key]['postcode'] = $v;
					break;
					case "plaats":
					$data_object[$key]['plaats'] = self::first_capitials($v);
					break;
					case "product":
					$data_object[$key]['product'] = self::first_capitials($v);
					break;
					case "aantal":
					$data_object[$key]['aantal'] = $v;
					break;
					case "bedrijf":
					$data_object[$key]['bedrijf'] .= " ".self::first_capitials($v);
					break;
					case "land":
					$data_object[$key]['land'] = self::first_capitials($v);
					break;
					case "orderid":
					$data_object[$key]['vendor_order_id'] = $v;
					break;
				}
			}
			if(!isset($data_object[$key]['product']) ||  $data_object[$key]['product'] == '')
			{
				$data_object[$key]['product'] = "Geen Product naam gevonden";
			}
			$data_object[$key]['retour'] = "OneDayOnly: Postbus 20667 - 1001NR Amsterdam";
		}

		return $data_object;
	}

	static function insert_shedeals( $import ) {

		$data_object = array();
		$product = "";
		foreach($import['values'] as $key => $value) {
			if(isset($value['Product']))
			{
				$product = self::first_capitials($value['Product']);
			}
			
			$data_object[$key]['product'] = $product;
			$data_object[$key]['bedrijf'] = "Shedeals";
			$data_object[$key]['naam'] = "";
			$data_object[$key]['adres'] = "";
			
			foreach($value as $k => $v)
			{
				$v = $v ? $v : "";
				$k = str_replace(" ", "", $k);
				switch($k){
					case "FIRSTNAME":
					case "LASTNAME":
					$data_object[$key]['naam'] .= self::first_capitials($v);
					break;
					case "STREET":
					case "STREET2":
					$data_object[$key]['adres'] .= self::first_capitials($v);
					break;
					case "POSTALCODE":
					$data_object[$key]['postcode'] = $v;
					break;
					case "CITY":
					$data_object[$key]['plaats'] = self::first_capitials($v);
					break;
					case "QUANTITY":
					$data_object[$key]['aantal'] = $v;
					break;
					case "COUNTRYCODE":
					$data_object[$key]['land'] = self::first_capitials($v);
					break;
					case "DOCUMENTNO":
					$data_object[$key]['vendor_order_id'] = $v;
					break;
					
				}
			}
			if(!isset($data_object[$key]['product']) ||  $data_object[$key]['product'] == '')
			{
				$data_object[$key]['product'] = "Geen Product naam gevonden";
			}
			$data_object[$key]['retour'] = "Postbus 263, 3860AG Nijkerk - NL";
		}

		return $data_object;
	}

	static function insert_wegener( $import ) {
		$data_object = array();

		foreach($import['values'] as $key => $value) {
			$data_object[$key]['bedrijf'] = "Wegener";
			$data_object[$key]['naam'] = "";
			$data_object[$key]['adres'] = "";
			foreach($value as $k => $v)
			{
				$v = $v ? $v : "";
				$k = str_replace(" ", "", $k);
				switch($k){
					case "Voornaam":
					case "Tussenvoegsel":
					case "Achternaam":
					$data_object[$key]['naam'] .= self::first_capitials($v);
					break;
					case "Straat":
					case "Huisnummer":
					$data_object[$key]['adres'] .= self::first_capitials($v);
					break;
					case "Postcode":
					$data_object[$key]['postcode'] = $v;
					break;
					case "Plaats":
					$data_object[$key]['plaats'] = self::first_capitials($v);
					break;
					case "Productnaam":
					$data_object[$key]['product'] = self::first_capitials($v);
					break;
					case "Aantal stuks":
					$data_object[$key]['aantal'] = $v;
					break;
					case "Land":
					$data_object[$key]['land'] = self::first_capitials($v);
					break;
					case "Ordernummer":
					$data_object[$key]['vendor_order_id'] = $v;
					break;
				}
			}
			if(!isset($data_object[$key]['product']) ||  $data_object[$key]['product'] == '')
			{
				$data_object[$key]['product'] = "Geen Product naam gevonden";
			}
			$data_object[$key]['retour'] = "Postbus 263, 3860AG Nijkerk - NL";
		}
		return $data_object;
	}

	static function insert_sweetdeals( $import ) {

		$data_object = array();

		foreach($import['values'] as $key => $value) {

			$data_object[$key]['adres'] = "";
			$data_object[$key]['bedrijf'] = "Sweetdeals";
			$data_object[$key]['naam'] = "";
			foreach($value as $k => $v)
			{
				$v = $v ? $v : "";
				$k = str_replace(" ", "", $k);
				
				switch($k){
					case "DealName":
					$str = utf8_encode(self::first_capitials($v));
					$str = iconv("UTF-8", "ISO-8859-1",$str);
					$data_object[$key]['product'] .= $str;
					break;
					case "ShippingAddress":
					case "ShippingAddress2":
					$data_object[$key]['adres'] .= self::first_capitials($v);
					break;
					case "CustomerFirstName":
					case "CustomerLastName":
					$data_object[$key]['naam'] .= self::first_capitials($v);
					break;
					case "ShippingZip":
					$data_object[$key]['postcode'] = $v;
					break;
					case "ShippingCity":
					$data_object[$key]['plaats'] = self::first_capitials($v);
					break;
					case "ShippingCountry":
					$data_object[$key]['land'] = self::first_capitials($v);
					break;
					case "Quantity":
					$data_object[$key]['aantal'] = $v;
					break;
				}
			}
			if(!isset($data_object[$key]['product']) ||  $data_object[$key]['product'] == '')
			{
				$data_object[$key]['product'] = "Geen Product naam gevonden";
			}
			$data_object[$key]['retour'] = "Postbus 263, 3860AG Nijkerk - NL";
		}
			
		return $data_object;
	}

	static function insert_ticketveilingen( $import ) {

		$data_object = array();

		foreach($import['values'] as $key => $value) {
			if(!isset($value['Artcode']) || $value['Artcode'] == "") {
				if (isset($value['Omschrijving'])){
				$data_object[$key-1]['product'] .= self::first_capitials($value['Omschrijving']);
				}
				continue;
			}

			$data_object[$key]['naam'] = "";
			$data_object[$key]['adres'] = "";
			$data_object[$key]['bedrijf'] = "Ticketveilingen";
				foreach($value as $k => $v)
				{
					$v = $v ? $v : "";
					$k = str_replace(" ", "", $k);
					switch($k){
						case "Omschrijving":
						$data_object[$key]['product'] = self::first_capitials($v);
						break;
						case "Voornaam":
						case "Tussenvoegsels":
						case "Achternaam":
						$data_object[$key]['naam'] .= self::first_capitials($v);
						break;
						case "Adres":
						case "Huisnummer":
						$data_object[$key]['adres'] .= self::first_capitials($v);
						break;
						case "Postcode":
						$data_object[$key]['postcode'] = $v;
						break;
						case "Plaats":
						$data_object[$key]['plaats'] = self::first_capitials($v);
						break;
						case "ShippingCountryName":
						$data_object[$key]['land'] = self::first_capitials($v);
						break;
						case "Orderdatum":
						$data_object[$key]['partner'] = $v;
						break;
						case "Aantal":
						$data_object[$key]['aantal'] = $v;
						break;
						case "id":
						$data_object[$key]['vendor_order_id'] = $v;
						break;
					}
				}
				if(!isset($data_object[$key]['product']) ||  $data_object[$key]['product'] == '')
				{
					$data_object[$key]['product'] = "Geen Product naam gevonden";
				}
			$data_object[$key]['retour'] = "Postbus 263, 3860AG Nijkerk - NL";
		}

		return $data_object;
	}

	static function insert_vakantieveilingen( $import ) {

		$data_object = array();

		foreach($import['values'] as $key => $value) {
			$data_object[$key]['naam'] = "";
			$data_object[$key]['bedrijf'] = "Vakantieveilingen";
			foreach($value as $k => $v)
			{
				$v = $v ? $v : "";
				$k = str_replace(" ", "", $k);
				switch($k){
					case "cmsTitle":
					$data_object[$key]['product'] = $v;
					break;
					case "firstName":
					case "lastNamePrefix":
					case "lastName":
					$data_object[$key]['naam'] .= self::first_capitials($v);
					break;
					case "street":
					case "houseNumber":
					$data_object[$key]['adres'] = self::first_capitials($v);
					break;
					case "postalCode":
					$data_object[$key]['postcode'] = $v;
					break;
					case "city":
					$data_object[$key]['plaats'] = self::first_capitials($v);
					break;
					case "country":
					$data_object[$key]['land'] = self::first_capitials($v);
					break;
					case "tsCreated":
					$data_object[$key]['partner'] = $v;
					break;
					case "orderId":
					$data_object[$key]['vendor_order_id'] = $v;
					break;
				}
			}
			if(!isset($data_object[$key]['product']) ||  $data_object[$key]['product'] == '')
			{
				$data_object[$key]['product'] = "Geen Product naam gevonden";
			}
			$data_object[$key]['retour'] = "Postbus 263, 3860AG Nijkerk - NL";
		}

		return $data_object;
	}
	
	static function insert_koopjedeal( $import ) {

		$data_object = array();

		foreach($import['values'] as $key => $value) {
			
			if(!isset($value['product']) || $value['product'] == "") {
				continue;
			}
			
			$data_object[$key]['bedrijf'] = "Koopjedeal";
			foreach($value as $k => $v)
			{
				$v = $v ? $v : "";
				$k = str_replace(" ", "", $k);
				switch($k){
					case "product":
					$data_object[$key]['product'] = self::first_capitials($v);
					break;
					case "naam":
					$data_object[$key]['naam'] = self::first_capitials($v);
					break;
					case "adres":
					$data_object[$key]['adres'] = self::first_capitials($v);
					break;
					case "postcode":
					$data_object[$key]['postcode'] = $v;
					break;
					case "plaats":
					$data_object[$key]['plaats'] = self::first_capitials($v);
					break;
					case "land":
					$data_object[$key]['land'] = self::first_capitials($v);
					break;
					case "vendor_order_id":
					$data_object[$key]['vendor_order_id'] = $v;
					break;
				}
			}
			if(!isset($data_object[$key]['product']) ||  $data_object[$key]['product'] == '')
			{
				$data_object[$key]['product'] = "Geen Product naam gevonden";
			}
			$data_object[$key]['retour'] = "Koopjedeal BV - Apolloweg 64 - 8239DA Lelystad - NL";
		}

		return $data_object;
	}
	
}