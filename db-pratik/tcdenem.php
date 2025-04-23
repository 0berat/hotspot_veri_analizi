<?php
$client = new SoapClient("https://tckimlik.nvi.gov.tr/Service/KPSPublic.asmx?WSDL");
try {    
$sonuc = $client->TCKimlikNoDogrula([        
'TCKimlikNo' => '-',        
'Ad' => '-',        
'Soyad' => '-',        
'DogumYili' => '-'    
]);    
if ($sonuc->TCKimlikNoDogrulaResult) {        
echo 'T.C. Kimlik No Doğru';    
} else {        
echo 'T.C. Kimlik No Hatalı';    
}} catch (Exception $e) {    
echo $e->$faultstring;
}

?>