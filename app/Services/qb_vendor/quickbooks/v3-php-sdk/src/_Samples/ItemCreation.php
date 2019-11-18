<?php

//Replace the line with require "vendor/autoload.php" if you are using the Samples from outside of _Samples folder
include('../config.php');

use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Item;


$dataService = DataService::Configure(array(
  'auth_mode' => 'oauth2',
  'ClientID' => "Q01XQryw1MpEPRToT0oaUY2VwwcexgW8KbA961jGf9CG6iAo0j",
  'ClientSecret' => "pqnfQh4r90YaGkIQu2oMILbyg8O2K5oUJv6sHO5P",
  'accessTokenKey' =>  'eyJlbmMiOiJBMTI4Q0JDLUhTMjU2IiwiYWxnIjoiZGlyIn0..QzkwTBkRaGnBxv8ivvdn7A.OUYtvQ66AowySmuFkcAybj-qRGiIRUUcQiWYVIGVJzVhtmbJV3ROw04BAFlmXKUETwYhpCaKtXZtJ-0ogLLrr2NLfuAgaMvznfP2aDUN2bi7KzpYi0hd1DVevltwVG2-H8sMFrWCRwciNrImH4WxF38g9Jq3ZXvOnLvJk2RM8qxvKq5fec3-HE9Vdtjmg0WGMzjYCupw8GRVSnBQFbGWv97S4oExSzcH5DIRG9lvELResKaJMaX-YZ4UyYJSnB9nWHnRnOZC5zA07j3ykAWztw8x0QVUgTYMljIjgeM21kf_Ozyn9-BTPDNLuTcvo36JNbZf0ls05DfjIutqYAtF0QtvUNOJdgFezs1dwPabBbypbC0cFdqdfm99DjH_koaVlK3ppctvoSafHIIXzqZLxtJr1t8ax33bHoSi9WIEd4xvcp3ujrael1ztvUPzXReKuquPOnZmpJSzdU8bRRv_nCGtmdhQE-9K85ApeYt3-tDbWR0ll3gGTG5Iq87_CD9nWuwCsksNAsH-0fa4n8wVueLJpXnakvumed4mi1FvmcCAiNOuqmGxiudPduZDqaEWzAIGsFFi0HKtwr3k2RvdYTAhE8WWJ4EOmOgqQkQ27Tr6xJa1s7F-ihjU_o_Jy1YKpUcxjHlQqQhZuvrbRSIgTr7YeGnh1BMttNibu5dNEx9jkOkFSo1EVu1N5u-phtjAPvqFNBrEC-UxiceM8nFF1p7tZWQREzdZC4BU14na5x4.me_bdvCAiSd9LFdS_DfTBA',
  'refreshTokenKey' => "AB11577339082tV7IiDvcwvryERFZE2nwlEGEGiYXzwLrnQwVI",
  'QBORealmID' => "4611809164061768998",
  'baseUrl' => "development"
));

$dataService->setLogLocation("/Users/hlu2/Desktop/newFolderForLog");


$OAuth2LoginHelper = $dataService->getOAuth2LoginHelper();

$accessToken = $OAuth2LoginHelper->refreshToken();
$error = $OAuth2LoginHelper->getLastError();
if ($error != null) {
    echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
    echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
    echo "The Response message is: " . $error->getResponseBody() . "\n";
    return;
}
$dataService->updateOAuth2Token($accessToken);

$dateTime = new \DateTime('NOW');
$Item = Item::create([
      "Name" => "1111Office Sudfsfasdfpplies",
      "Description" => "This is the sales description.",
      "Active" => true,
      "FullyQualifiedName" => "Office Supplies",
      "Taxable" => true,
      "UnitPrice" => 25,
      "Type" => "Inventory",
      "IncomeAccountRef"=> [
        "value"=> 79,
        "name" => "Landscaping Services:Job Materials:Fountains and Garden Lighting"
      ],
      "PurchaseDesc"=> "This is the purchasing description.",
      "PurchaseCost"=> 35,
      "ExpenseAccountRef"=> [
        "value"=> 80,
        "name"=> "Cost of Goods Sold"
      ],
      "AssetAccountRef"=> [
        "value"=> 81,
        "name"=> "Inventory Asset"
      ],
      "TrackQtyOnHand" => true,
      "QtyOnHand"=> 100,
      "InvStartDate"=> $dateTime
]);


$resultingObj = $dataService->Add($Item);
$error = $dataService->getLastError();
if ($error) {
    echo "The Status code is: " . $error->getHttpStatusCode() . "\n";
    echo "The Helper message is: " . $error->getOAuthHelperError() . "\n";
    echo "The Response message is: " . $error->getResponseBody() . "\n";
}
else {
    echo "Created Id={$resultingObj->Id}. Reconstructed response body:\n\n";
    $xmlBody = XmlObjectSerializer::getPostXmlFromArbitraryEntity($resultingObj, $urlResource);
    echo $xmlBody . "\n";
}

/*

Example output:

Account[0]: Travel Meals
     * Id: NG:42315
     * AccountType: Expense
     * AccountSubType:

Account[1]: COGs
     * Id: NG:40450
     * AccountType: Cost of Goods Sold
     * AccountSubType:

...

*/
 ?>
