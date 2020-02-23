<?php

function hopdb_state_list()
	{
	return array(
		'AL'=>"Alabama", 'AK'=>"Alaska",  'AZ'=>"Arizona",  'AR'=>"Arkansas",  'CA'=>"California",  'CO'=>"Colorado",  'CT'=>"Connecticut",  
		'DE'=>"Delaware",  'DC'=>"District Of Columbia",  'FL'=>"Florida",  'GA'=>"Georgia",  'HI'=>"Hawaii",  'ID'=>"Idaho",  'IL'=>"Illinois",  'IN'=>"Indiana",  
		'IA'=>"Iowa",  'KS'=>"Kansas",  'KY'=>"Kentucky",  'LA'=>"Louisiana",  'ME'=>"Maine",  'MD'=>"Maryland",  'MA'=>"Massachusetts",  'MI'=>"Michigan",  
              'MN'=>"Minnesota",  'MS'=>"Mississippi",  'MO'=>"Missouri",  'MT'=>"Montana",'NE'=>"Nebraska",'NV'=>"Nevada",'NH'=>"New Hampshire",'NJ'=>"New Jersey",
              'NM'=>"New Mexico",'NY'=>"New York",'NC'=>"North Carolina",'ND'=>"North Dakota",'OH'=>"Ohio",  'OK'=>"Oklahoma",  'OR'=>"Oregon",  'PA'=>"Pennsylvania",  
              'RI'=>"Rhode Island",  'SC'=>"South Carolina",  'SD'=>"South Dakota",'TN'=>"Tennessee",  'TX'=>"Texas",  'UT'=>"Utah",  'VT'=>"Vermont",  
              'VA'=>"Virginia", 'WA'=>"Washington",  'WV'=>"West Virginia",  'WI'=>"Wisconsin",  'WY'=>"Wyoming");
	}

function hopdb_state_name($st)	{	$k = hopdb_state_list();return $k[strtoupper($st)];}
function hopdb_is_state($st)	{	$k = hopdb_state_list();return $k[strtoupper($st)]!="";}

function hopdb_state_select($name="editstate",$st="",$full_name=false)
	{
	$s = "";
	$s = $s . "<select name=\"$name\">\n";
	$s = $s . " <option></option>\n";
	foreach (hopdb_state_list() as $a=>$b)
		$s = $s . " <option ".(strtoupper($st)==$a?"selected='selected' ":"")."value='$a'>".($full_name?$b:$a)."</option>\n";
	$s = $s . "</select>\n";
	return $s;
	}

function hopdb_country_selector($target)
	{
	$countries = array( 
		"us"=>"United States", 
		"ca"=>"Canada", 
		"gb"=>"England", 
		"il"=>"Israel",
		"fr"=>"France", 
		"de"=>"Germany", 
		"br"=>"Brazil", 
		"jp"=>"Japan", 

		"cn"=>"China", 
		"kr"=>"South Korea", 
		"mx"=>"Mexico",
		"it"=>"Italy", 
		"nl"=>"Netherlands",
		"dk"=>"Denmark",
		"pl"=>"Poland",
		"ru"=>"Russia",
		"bo"=>"Bolivia",

		"gr"=>"Greece",
		"lv"=>"Latvia", 
		"lb"=>"Lebanon", 
		"tz"=>"Tanzania", 
		"gh"=>"Ghana",
		"za"=>"south africa",
		"zw"=>"zimbabwe",
		"mz"=>"mozambique",
		);

	$s = "<div>";
	$n = 0;
	foreach ($countries as $abbr=>$name)
		{
		if($abbr=="") continue;
		$s = $s . "<img style='border: black 1px dotted;margin-right:3px;' src='".hopdb_plugin_url('/images/countries/'.$abbr.'.png')."' alt='$name' title='$name' onClick='javascript:$target.value=\"$name\"' width='16' height='11'/>";
		if (++$n % 8 == 0) $s = $s . "<br/>";
		}

	$s = $s . "</div>";
	return $s;
	}

function hopdb_country_code($country)
	{
	if (strtolower($country)=="england") return "GB";
	foreach(hopdb_country_codes() as $a=>$b)
		if (strtolower($country)==strtolower($b)) return $a;
	return "";
	}

function hopdb_country_name($ccode,$pretty=true)
	{
	$x = hopdb_country_codes();
	$n = $x[strtoupper($ccode)];
	if ($pretty) $n = ucwords(strtolower($n));
	return $n;
	}


function hopdb_country_codes()
	{
	return array(
'AF' => 'AFGHANISTAN',
'AX' => 'ÅLAND ISLANDS',
'AL' => 'ALBANIA',
'DZ' => 'ALGERIA',
'AS' => 'AMERICAN SAMOA',
'AD' => 'ANDORRA',
'AO' => 'ANGOLA',
'AI' => 'ANGUILLA',
'AQ' => 'ANTARCTICA',
'AG' => 'ANTIGUA AND BARBUDA',
'AR' => 'ARGENTINA',
'AM' => 'ARMENIA',
'AW' => 'ARUBA',
'AU' => 'AUSTRALIA',
'AT' => 'AUSTRIA',
'AZ' => 'AZERBAIJAN',
'BS' => 'BAHAMAS',
'BH' => 'BAHRAIN',
'BD' => 'BANGLADESH',
'BB' => 'BARBADOS',
'BY' => 'BELARUS',
'BE' => 'BELGIUM',
'BZ' => 'BELIZE',
'BJ' => 'BENIN',
'BM' => 'BERMUDA',
'BT' => 'BHUTAN',
'BO' => 'BOLIVIA',  // Plurinational State of Bolivia
'BQ' => 'SINT EUSTATIUS AND SABA BONAIRE',
'BA' => 'BOSNIA AND HERZEGOVINA',
'BW' => 'BOTSWANA',
'BV' => 'BOUVET ISLAND',
'BR' => 'BRAZIL',
'IO' => 'BRITISH INDIAN OCEAN TERRITORY',
'BN' => 'BRUNEI DARUSSALAM',
'BG' => 'BULGARIA',
'BF' => 'BURKINA FASO',
'BI' => 'BURUNDI',
'KH' => 'CAMBODIA',
'CM' => 'CAMEROON',
'CA' => 'CANADA',
'CV' => 'CAPE VERDE',
'KY' => 'CAYMAN ISLANDS',
'CF' => 'CENTRAL AFRICAN REPUBLIC',
'TD' => 'CHAD',
'CL' => 'CHILE',
'CN' => 'CHINA',
'CX' => 'CHRISTMAS ISLAND',
'CC' => 'COCOS (KEELING) ISLANDS',
'CO' => 'COLOMBIA',
'KM' => 'COMOROS',
'CG' => 'CONGO',
'CD' => 'THE DEMOCRATIC REPUBLIC OF THE CONGO',
'CK' => 'COOK ISLANDS',
'CR' => 'COSTA RICA',
'CI' => "COTE D'IVOIRE",
'HR' => 'CROATIA',
'CU' => 'CUBA',
'CW' => 'CURAÇAO',
'CY' => 'CYPRUS',
'CZ' => 'CZECH REPUBLIC',
'DK' => 'DENMARK',
'DJ' => 'DJIBOUTI',
'DM' => 'DOMINICA',
'DO' => 'DOMINICAN REPUBLIC',
'EC' => 'ECUADOR',
'EG' => 'EGYPT',
'SV' => 'EL SALVADOR',
'GQ' => 'EQUATORIAL GUINEA',
'ER' => 'ERITREA',
'EE' => 'ESTONIA',
'ET' => 'ETHIOPIA',
'FK' => 'FALKLAND ISLANDS (MALVINAS)',
'FO' => 'FAROE ISLANDS',
'FJ' => 'FIJI',
'FI' => 'FINLAND',
'FR' => 'FRANCE',
'GF' => 'FRENCH GUIANA',
'PF' => 'FRENCH POLYNESIA',
'TF' => 'FRENCH SOUTHERN TERRITORIES',
'GA' => 'GABON',
'GM' => 'GAMBIA',
'GE' => 'GEORGIA',
'DE' => 'GERMANY',
'GH' => 'GHANA',
'GI' => 'GIBRALTAR',
'GR' => 'GREECE',
'GL' => 'GREENLAND',
'GD' => 'GRENADA',
'GP' => 'GUADELOUPE',
'GU' => 'GUAM',
'GT' => 'GUATEMALA',
'GG' => 'GUERNSEY',
'GN' => 'GUINEA',
'GW' => 'GUINEA-BISSAU',
'GY' => 'GUYANA',
'HT' => 'HAITI',
'HM' => 'HEARD ISLAND AND MCDONALD ISLANDS',
'VA' => 'HOLY SEE (VATICAN CITY STATE)',
'HN' => 'HONDURAS',
'HK' => 'HONG KONG',
'HU' => 'HUNGARY',
'IS' => 'ICELAND',
'IN' => 'INDIA',
'ID' => 'INDONESIA',
'IR' => 'ISLAMIC REPUBLIC OF IRAN',
'IQ' => 'IRAQ',
'IE' => 'IRELAND',
'IM' => 'ISLE OF MAN',
'IL' => 'ISRAEL',
'IT' => 'ITALY',
'JM' => 'JAMAICA',
'JP' => 'JAPAN',
'JE' => 'JERSEY',
'JO' => 'JORDAN',
'KZ' => 'KAZAKHSTAN',
'KE' => 'KENYA',
'KI' => 'KIRIBATI',
'KP' => 'NORTH KOREA', //  DEMOCRATIC PEOPLES REPUBLIC OF KOREA
'KR' => 'SOUTH KOREA', //  REPUBLIC OF KOREA
'KW' => 'KUWAIT',
'KG' => 'KYRGYZSTAN',
'LA' => 'LAO PEOPLES DEMOCRATIC REPUBLIC',
'LV' => 'LATVIA',
'LB' => 'LEBANON',
'LS' => 'LESOTHO',
'LR' => 'LIBERIA',
'LY' => 'LIBYAN ARAB JAMAHIRIYA',
'LI' => 'LIECHTENSTEIN',
'LT' => 'LITHUANIA',
'LU' => 'LUXEMBOURG',
'MO' => 'MACAO',
'MK' => 'THE FORMER YUGOSLAV REPUBLIC OF MACEDONIA',
'MG' => 'MADAGASCAR',
'MW' => 'MALAWI',
'MY' => 'MALAYSIA',
'MV' => 'MALDIVES',
'ML' => 'MALI',
'MT' => 'MALTA',
'MH' => 'MARSHALL ISLANDS',
'MQ' => 'MARTINIQUE',
'MR' => 'MAURITANIA',
'MU' => 'MAURITIUS',
'YT' => 'MAYOTTE',
'MX' => 'MEXICO',
'FM' => 'FEDERATED STATES OF MICRONESIA',
'MD' => 'REPUBLIC OF MOLDOVA',
'MC' => 'MONACO',
'MN' => 'MONGOLIA',
'ME' => 'MONTENEGRO',
'MS' => 'MONTSERRAT',
'MA' => 'MOROCCO',
'MZ' => 'MOZAMBIQUE',
'MM' => 'MYANMAR',
'NA' => 'NAMIBIA',
'NR' => 'NAURU',
'NP' => 'NEPAL',
'NL' => 'NETHERLANDS',
'NC' => 'NEW CALEDONIA',
'NZ' => 'NEW ZEALAND',
'NI' => 'NICARAGUA',
'NE' => 'NIGER',
'NG' => 'NIGERIA',
'NU' => 'NIUE',
'NF' => 'NORFOLK ISLAND',
'MP' => 'NORTHERN MARIANA ISLANDS',
'NO' => 'NORWAY',
'OM' => 'OMAN',
'PK' => 'PAKISTAN',
'PW' => 'PALAU',
'PS' => 'OCCUPIED PALESTINIAN TERRITORY',
'PA' => 'PANAMA',
'PG' => 'PAPUA NEW GUINEA',
'PY' => 'PARAGUAY',
'PE' => 'PERU',
'PH' => 'PHILIPPINES',
'PN' => 'PITCAIRN',
'PL' => 'POLAND',
'PT' => 'PORTUGAL',
'PR' => 'PUERTO RICO',
'QA' => 'QATAR',
'RE' => 'RÉUNION',
'RO' => 'ROMANIA',
'RU' => 'RUSSIAN FEDERATION',
'RW' => 'RWANDA',
'BL' => 'SAINT BARTHÉLEMY',
'SH' => 'ASCENSION AND TRISTAN DA CUNHA SAINT HELENA',
'KN' => 'SAINT KITTS AND NEVIS',
'LC' => 'SAINT LUCIA',
'MF' => 'SAINT MARTIN (FRENCH PART)',
'PM' => 'SAINT PIERRE AND MIQUELON',
'VC' => 'SAINT VINCENT AND THE GRENADINES',
'WS' => 'SAMOA',
'SM' => 'SAN MARINO',
'ST' => 'SAO TOME AND PRINCIPE',
'SA' => 'SAUDI ARABIA',
'SN' => 'SENEGAL',
'RS' => 'SERBIA',
'SC' => 'SEYCHELLES',
'SL' => 'SIERRA LEONE',
'SG' => 'SINGAPORE',
'SX' => 'SINT MAARTEN (DUTCH PART)',
'SK' => 'SLOVAKIA',
'SI' => 'SLOVENIA',
'SB' => 'SOLOMON ISLANDS',
'SO' => 'SOMALIA',
'ZA' => 'SOUTH AFRICA',
'GS' => 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS',
'SS' => 'SOUTH SUDAN',
'ES' => 'SPAIN',
'LK' => 'SRI LANKA',
'SD' => 'SUDAN',
'SR' => 'SURINAME',
'SJ' => 'SVALBARD AND JAN MAYEN',
'SZ' => 'SWAZILAND',
'SE' => 'SWEDEN',
'CH' => 'SWITZERLAND',
'SY' => 'SYRIAN ARAB REPUBLIC',
'TW' => 'PROVINCE OF CHINA TAIWAN',
'TJ' => 'TAJIKISTAN',
'TZ' => 'UNITED REPUBLIC OF TANZANIA',
'TH' => 'THAILAND',
'TL' => 'TIMOR-LESTE',
'TG' => 'TOGO',
'TK' => 'TOKELAU',
'TO' => 'TONGA',
'TT' => 'TRINIDAD AND TOBAGO',
'TN' => 'TUNISIA',
'TR' => 'TURKEY',
'TM' => 'TURKMENISTAN',
'TC' => 'TURKS AND CAICOS ISLANDS',
'TV' => 'TUVALU',
'UG' => 'UGANDA',
'UA' => 'UKRAINE',
'AE' => 'UNITED ARAB EMIRATES',
'GB' => 'UNITED KINGDOM',
'US' => 'UNITED STATES',
'UM' => 'UNITED STATES MINOR OUTLYING ISLANDS',
'UY' => 'URUGUAY',
'UZ' => 'UZBEKISTAN',
'VU' => 'VANUATU',
'VE' => 'BOLIVARIAN REPUBLIC OF VENEZUELA',
'VN' => 'VIET NAM',
'VG' => 'BRITISH VIRGIN ISLANDS',
'VI' => 'U.S. VIRGIN ISLANDS',
'WF' => 'WALLIS AND FUTUNA',
'EH' => 'WESTERN SAHARA',
'YE' => 'YEMEN',
'ZM' => 'ZAMBIA',
'ZW' => 'ZIMBABWE',
		);
	}

?>