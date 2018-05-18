<?php
function neo_check_client($email){
$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => "https://api.mindbodyonline.com/0_5/ClientService.asmx",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
  CURLOPT_POSTFIELDS => "<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n<soap:Envelope xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:soap=\"http://schemas.xmlsoap.org/soap/envelope/\">\r\n  <soap:Body>\r\n    <GetClients xmlns=\"http://clients.mindbodyonline.com/api/0_5\">\r\n      <Request>\r\n        <SourceCredentials>\r\n                        <SourceName>EpicWebSolutionsLLC</SourceName>\r\n                        <Password>YFQNG8aGFbWrKxZRdUa1CZpU3rw=</Password>\r\n                        <SiteIDs>\r\n                           <int>-99</int>\r\n                        </SiteIDs>\r\n                     </SourceCredentials>\r\n                     <UserCredentials>\r\n               <Username>Siteowner</Username>\r\n               <Password>apitest1234</Password>\r\n               <SiteIDs>\r\n                  <int>-99</int>\r\n               </SiteIDs>\r\n               <LocationID>0</LocationID>\r\n            </UserCredentials>\r\n        \r\n                             <XMLDetail>Full</XMLDetail>\r\n\r\n        <SearchText>".$email."</SearchText>\r\n      </Request>\r\n    </GetClients>\r\n  </soap:Body>\r\n</soap:Envelope>",
  CURLOPT_HTTPHEADER => array(
    "cache-control: no-cache",
    "content-type: text/xml",
    "postman-token: e65eaf01-5ef2-f9d1-bfb1-bfa2e54e9fc9",
    "soapaction: http://clients.mindbodyonline.com/api/0_5/GetClients"
  ),
));

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
  //echo $response;
}

$response = preg_replace("/(<\/?)(\w+):([^>]*>)/", "$1$2$3", $response);
$xml = new SimpleXMLElement($response);

$body = $xml->xpath('//soapBody')[0];
$array = json_decode(json_encode((array)$body), TRUE); 
return $array;
}





$isLead = 0;
$array = neo_check_client('info@yahoo.com');
///this is to check client have signup in our system or not 
if($array['GetClientsResponse']['GetClientsResult']['ResultCount']==0){
	///add your code to insert new lead
}else{
	///this is used to check if appointment done or not 
	$UniqueID = $array['GetClientsResponse']['GetClientsResult']['Clients']['Client']['ID'];
	$FirstAppointmentDate = $array['GetClientsResponse']['GetClientsResult']['Clients']['Client']['FirstAppointmentDate'];
	if($FirstAppointmentDate!=''){
		$isLead = 1;
	}
	//echo $UniqueID;
	//$array = neo_check_client_visit($UniqueID);
//	print_r($array);

}