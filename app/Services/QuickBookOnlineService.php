<?php

namespace App\Services;

use Illuminate\Http\Request;

require "qb_vendor/autoload.php";

use QuickBooksOnline\API\Core\ServiceContext;
use QuickBooksOnline\API\DataService\DataService;
use QuickBooksOnline\API\PlatformService\PlatformService;
use QuickBooksOnline\API\Core\Http\Serialization\XmlObjectSerializer;
use QuickBooksOnline\API\Facades\Customer;
use QuickBooksOnline\API\Facades\Invoice;
use QuickBooksOnline\API\Facades\Payment;
use QuickBooksOnline\API\Facades\Item;
use QuickBooksOnline\API\Facades\Line;
use QuickBooksOnline\API\Facades\TaxRate;
use QuickBooksOnline\API\Facades\TaxService;
use App\Models\Customer as CustomerModel;
use App\Models\QuickbookToken;
use App\Models\TaxType;

use QuickBooksOnline\API\Exception\SdkException;
use QuickBooksOnline\API\Exception\ServiceException;
use Exception;

class QuickBookOnlineService {
    
    public function __construct() {
        $quickbookTokenObj = QuickbookToken::find(1);
        $this->dataService = DataService::Configure(array(
            'auth_mode' => 'oauth2',
            'ClientID' => env('QUICKBOOK_CLIENT_ID'),
            'ClientSecret' =>  env('QUICKBOOK_CLIENT_SECRET'),
            'RedirectURI' => env('QUICKBOOK_REDIRECT_URI'), #We are not using it right now
            'refreshTokenKey' => $quickbookTokenObj->refresh_token_key,
            'scope' => env('QUICKBOOK_SCOPE'),
            'QBORealmID' => env('QUICKBOOK_REALM_ID'),
            'baseUrl' => env('QUICKBOOK_BASE_URL')
        ));
        $this->dataService->throwExceptionOnError(true);
    }
    
    /*
     * (Note: $dataService->Query gives Array of Objects)
     * $qb_status = 1,  means whole process completed. If $invoice_id made and still payment is pending then $qb_status should be 0.
     */
    
    public function updateTokens(){
        $OAuth2LoginHelper = $this->dataService->getOAuth2LoginHelper();
        $refreshedAccessTokenObj = $OAuth2LoginHelper->refreshToken();
        $this->dataService->updateOAuth2Token($refreshedAccessTokenObj);

        $quickbookTokenObj = QuickbookToken::find(1);
        $quickbookTokenObj->access_token_key = $refreshedAccessTokenObj->getAccessToken();
        $quickbookTokenObj->refresh_token_key = $refreshedAccessTokenObj->getRefreshToken();
        $quickbookTokenObj->save();
        
        return $this->dataService;
    }
    
    public function start($order, $order_payment_status){
        try{ 
            #Update Tokens
            $dataService = $this->updateTokens();
            
            #Check and create customer
            $customer_qb_id = $this->checkAndCreateCustomer($dataService, $order);
            #Create Invoice
            if(!empty($customer_qb_id)){
                $invoice_id = $this->updateOrCreateInvoice($dataService, $customer_qb_id, $order); 
                #$order->invoice_id = $invoice_id;
                $order->qb_status = 0;
                #Create Payment
                if(!empty($invoice_id) && $order_payment_status == 2){
                    $qb_payment_status = $this->updateOrCreatePayment($dataService, $customer_qb_id, $invoice_id, $order);
                    if($qb_payment_status){
                        $order->qb_status = 1;
                    }
                }
                if(!empty($invoice_id) && $order_payment_status == 1){
                    $order->qb_status = 1;
                }
            }else{
                #$order->qb_status = 0;
                #$order->save();
                return false;
            }
            $order->save();
            return true;
        } catch (SdkException $e){
            $order->qb_status = 0;
            $order->save();
            logError($e->getMessage().'Function Name: QuickBookOnlineService->start()');
        } catch (ServiceException $e){
            $order->qb_status = 0;
            $order->save();
            logError($e->getMessage().'Function Name: QuickBookOnlineService->start()');
        } catch (Exception $e){
            $order->qb_status = 0;
            $order->save();
            logError($e->getMessage().'Function Name: QuickBookOnlineService->start()');
        }
    }
    
    function downloadInvoiceAsPdf($order, $dir = null){
        try{
            #Update Tokens
            $dataService = $this->updateTokens();

            $invoice_id = $order->invoice_id;
            $targetInvoiceArr = $dataService->Query("select * from Invoice where Id = '$invoice_id'");
            if(!empty($targetInvoiceArr) && sizeof($targetInvoiceArr) == 1){
                $invoiceObj = current($targetInvoiceArr);
            }
            
            if($dir){
                $directoryForThePDF = $this->dataService->DownloadPDF($invoiceObj, "D:\invoice_pdfs");
                #echo "PDF is downloaded at: " .$directoryForThePDF;
                return true;
            }else{
                $result = $dataService->DownloadPDF($invoiceObj);
                $file_name = 'invoice#'.$order->invoice_number.'.pdf';
                header ( "Content-Disposition: attachment; filename=" . $file_name );
                echo $result;
                #die();
                return true;
            }
            
        } catch (SdkException $e){
            logError($e->getMessage().'Function Name: QuickBookOnlineService->downloadInvoiceAsPdf()');
        } catch (ServiceException $e){
            logError($e->getMessage().'Function Name: QuickBookOnlineService->downloadInvoiceAsPdf()');
        } catch (Exception $e){
            logError($e->getMessage().'Function Name: QuickBookOnlineService->downloadInvoiceAsPdf()');
        }
    }
    
    function checkAndCreateCustomer($dataService, $order){
        try{
            $customer_qb_id = $order->customer->customer_qb_id;
            $customerArr = $dataService->Query("SELECT * FROM Customer where Id='$customer_qb_id'");
            if (!$customerArr || (0 == count($customerArr))) {
                $customerModelObj = CustomerModel::find($order->customer->id);
                $newCustomerObj = Customer::create([
                            "BillAddr" => [
                                "Line1" => $customerModelObj->funeral_home->address_line1,
                                "Line2" => $customerModelObj->funeral_home->address_line2,
                                "City" => $customerModelObj->funeral_home->city,
                                "Country" => $customerModelObj->funeral_home->country->country,
                                "CountrySubDivisionCode" => "",
                                "PostalCode" => $customerModelObj->funeral_home->postal_code
                            ],
                            "Notes" => "",
                            "Title" => "",
                            "GivenName" => "",
                            "MiddleName" => "",
                            "FamilyName" => "",
                            "Suffix" => "",
                            "FullyQualifiedName" => "",
                            "CompanyName" => $customerModelObj->company_name,
                            "DisplayName" => $customerModelObj->company_name,
                            "PrimaryPhone" => [
                                "FreeFormNumber" => $customerModelObj->telephone
                            ],
                            "PrimaryEmailAddr" => [
                                "Address" => $customerModelObj->email_id
                            ]
                ]);

                $resultingCustomerObj = $dataService->Add($newCustomerObj);
                $customer_qb_id = $resultingCustomerObj->Id;
                $customerModelObj->customer_qb_id = $customer_qb_id;
                $customerModelObj->save();
            }
            return $customer_qb_id;
        } catch (SdkException $e){ 
            logError($e->getMessage().'Function Name: QuickBookOnlineService->checkAndCreateCustomer()');
        } catch (ServiceException $e){ 
            logError($e->getMessage().'Function Name: QuickBookOnlineService->checkAndCreateCustomer()');
        } catch (Exception $e){ 
            logError($e->getMessage().'Function Name: QuickBookOnlineService->checkAndCreateCustomer()');
        }
    }
    
    function updateOrCreateInvoice($dataService, $customer_qb_id, $order){
        try{
            $global_tax_code_id = $this->getTaxCodeId($dataService, $order);
            $itemsLineArr = $this->createItemsLineArr($dataService, $order);
            if($order->invoice_id == 0){
                $invoiceObj = Invoice::create([
                    "DocNumber" => $order->order_number,
                    "Line" => $itemsLineArr,
                    #This key-value pair is required if the company is US-based and we want to apply custom tax value.
                    "TxnTaxDetail" => [
                        "TxnTaxCodeRef" => [
                            "value" => $global_tax_code_id,
                            "name" => ""
                        ],
                        "TotalTax" => $order->tax_amount
                    ],
                    "CustomerRef" => [
                        "value" => $customer_qb_id
                    ],
                ]);
                $resultingObj = $dataService->Add($invoiceObj);
                $error = $dataService->getLastError();
                if ($error) {
                    return '';
                }
                $order->invoice_id = $resultingObj->Id;
                $order->invoice_number = $resultingObj->DocNumber;
                $order->save();
                return $resultingObj->Id;
            }else{
                $invoice_id = $order->invoice_id;
                $targetInvoiceArr = $dataService->Query("select * from Invoice where Id = '$invoice_id'");
                if(!empty($targetInvoiceArr) && sizeof($targetInvoiceArr) == 1){
                    $invoiceObj = current($targetInvoiceArr);
                    $updatedInvoiceObj = Invoice::update($invoiceObj, [
                                "sparse" => true,
                                "Line" => $itemsLineArr,
                                "TxnTaxDetail" => [
                                    "TxnTaxCodeRef" => [
                                        "value" => $global_tax_code_id,
                                        "name" => ""
                                    ],
                                    "TotalTax" => $order->tax_amount
                                ]
                    ]);
                    $updatedResultObj = $dataService->Update($updatedInvoiceObj);
                    $error = $dataService->getLastError();
                    if ($error) {
                        return '';
                    }
                    $order->invoice_id = $updatedResultObj->Id;
                    $order->invoice_number = $updatedResultObj->DocNumber;
                    $order->save();
                    return $updatedResultObj->Id;
                }
                return '';
            }
        } catch (SdkException $e){ 
            logError($e->getMessage().'Function Name: QuickBookOnlineService->updateOrCreateInvoice()');
        } catch (ServiceException $e){ 
            logError($e->getMessage().'Function Name: QuickBookOnlineService->updateOrCreateInvoice()');
        } catch (Exception $e){ 
            logError($e->getMessage().'Function Name: QuickBookOnlineService->updateOrCreateInvoice()');
        }
    }
    
    function updateOrCreatePayment($dataService, $customer_qb_id, $invoice_id, $order){
        try{
            $total_amount = $order->order_amount + $order->tax_amount + $order->order_shipping_detail->shipping_amount;
            if($order->invoice_payment_id == 0){
                $theResourceObj = Payment::create([
                    "CustomerRef" =>
                    [
                        "value" => $customer_qb_id
                    ],
                    "TotalAmt" => $total_amount,
                    "Line" => [
                    [
                        "Amount" => $total_amount,
                        "LinkedTxn" => [
                        [
                            "TxnId" => $invoice_id,
                            "TxnType" => "Invoice"
                        ]]
                    ]]
                ]);
                $resultingObj = $dataService->Add($theResourceObj);
                $error = $dataService->getLastError();
                if ($error) {
                    return false;
                }
                $order->invoice_payment_id = $resultingObj->Id;
                $order->save();
                return true;
            }else{
                $payment_id = $order->invoice_payment_id;
                $invoicePaymentArr = $dataService->Query("select * from Payment where Id = '$payment_id'");
                if(!empty($invoicePaymentArr) && sizeof($invoicePaymentArr) == 1){
                    $paymentObj = current($invoicePaymentArr);
                }
                $theResourceObj = Payment::update($paymentObj, [
                    "sparse" => true,
                    "TotalAmt" => $total_amount,
                    "Line" => [
                    [
                        "Amount" => $total_amount,
                        "LinkedTxn" => [
                        [
                            "TxnId" => $invoice_id,
                            "TxnType" => "Invoice"
                        ]]
                    ]]
                ]);
                $resultingObj = $dataService->Update($theResourceObj);
                $error = $dataService->getLastError();
                if ($error) {
                    return false;
                }
                $order->invoice_payment_id = $resultingObj->Id;
                $order->save();
                return true;
            }
            
        } catch (SdkException $e){ 
            logError($e->getMessage().'Function Name: QuickBookOnlineService->createPayment()');
        } catch (ServiceException $e){ 
            logError($e->getMessage().'Function Name: QuickBookOnlineService->createPayment()');
        } catch (Exception $e){ 
            logError($e->getMessage().'Function Name: QuickBookOnlineService->createPayment()');
        }
        
    }
    
    public function createItem($dataService, $product_name, $product_price){
        try{
            $item_id = 0;
            $Item = Item::create([
                "Name" => $product_name,
                "FullyQualifiedName" => $product_name,
                "UnitPrice" => $product_price, // Price of item
                "Type" => "NonInventory",
                "IncomeAccountRef"=> [
                  "value"=> 79,
                  "name" => "Sales of Product Income"
                ],
                "TrackQtyOnHand" => false,
            ]);

            $resultingObj = $dataService->Add($Item);
            $item_id = $resultingObj->Id;
            return $item_id;
        }catch (SdkException $e){ 
            logError($e->getMessage().'Function Name: QuickBookOnlineService->createItem()');
        }catch (ServiceException $e){ 
            logError($e->getMessage().'Function Name: QuickBookOnlineService->createItem()');
        }catch (Exception $e){ 
            logError($e->getMessage().'Function Name: QuickBookOnlineService->createItem()');
        }
        
    }
    
    public function createItemsLineArr($dataService, $order){
        #Here $resultArr have keys like propsArr, propAddonsArr, valuePackageArr, vpPropAddonsArr and vpComboPropAddonsArr (which exists)
        try{
            $resultArr = $this->getItemsToCreateLines($order); 
            $tax_code_id = 'TAX';
            $itemsLineArr = [];

            if(!empty($resultArr['valuePackageArr'])){
                $value_package_title = $resultArr['valuePackageArr']['value_package_title'];
                $price = $resultArr['valuePackageArr']['price'];
                $quantity = $resultArr['valuePackageArr']['quantity'];
                try{
                    $itemsLineArr[] = $this->getLineArray($dataService, $value_package_title, $price, $price, '', $quantity, $tax_code_id);
                }catch (SdkException $e){ 
                    logError($e->getMessage().'Function Name: QuickBookOnlineService->createItemsLineArr()->valuePackage');
                }catch (ServiceException $e){
                    logError($e->getMessage().'Function Name: QuickBookOnlineService->createItemsLineArr()->valuePackage');
                }catch (Exception $e){
                    logError($e->getMessage().'Function Name: QuickBookOnlineService->createItemsLineArr()->valuePackage');
                }
                
                $valuePackageProductsArr = $resultArr['valuePackageArr']['value_package_products'];
                if(!empty($valuePackageProductsArr)){
                    foreach($valuePackageProductsArr as $vpPropArr){
                        try{
                            $itemsLineArr[] = $this->getLineArray($dataService, $vpPropArr['product_title'], $vpPropArr['unit_price'], $vpPropArr['price'], '', $vpPropArr['quantity'], $tax_code_id);
                        }catch (SdkException $e){ 
                            logError($e->getMessage().'Function Name: QuickBookOnlineService->createItemsLineArr()->valuePackageProducts');
                        }catch (ServiceException $e){
                            logError($e->getMessage().'Function Name: QuickBookOnlineService->createItemsLineArr()->valuePackageProducts');
                        }catch (Exception $e){
                            logError($e->getMessage().'Function Name: QuickBookOnlineService->createItemsLineArr()->valuePackageProducts');
                        }                                

                        
                        if($vpPropArr['is_combo'] == 1 && !empty($vpPropArr['combo_products'])){
                            foreach($vpPropArr['combo_products'] as $vpComboPropArr){
                                try{
                                    $itemsLineArr[] = $this->getLineArray($dataService, $vpComboPropArr['product_title'], $vpComboPropArr['unit_price'], $vpComboPropArr['price'], '', $vpComboPropArr['quantity'], $tax_code_id);
                                }catch (SdkException $e){ 
                                    logError($e->getMessage().'Function Name: QuickBookOnlineService->createItemsLineArr()->valuePackageProducts->comboProducts');
                                }catch (ServiceException $e){
                                    logError($e->getMessage().'Function Name: QuickBookOnlineService->createItemsLineArr()->valuePackageProducts->comboProducts');
                                }catch (Exception $e){
                                    logError($e->getMessage().'Function Name: QuickBookOnlineService->createItemsLineArr()->valuePackageProducts->comboProducts');
                                }
                            }
                        }
                    }
                }
            }
           
            if(!empty($resultArr['vpPropAddonsArr'])){
                foreach($resultArr['vpPropAddonsArr'] as $vpPropAddonArr){
                    foreach($vpPropAddonArr as $addonArr){
                        try{
                            $itemsLineArr[] = $this->getLineArray($dataService, $addonArr['addon_title'], $addonArr['unit_price'], $addonArr['price'], $addonArr['product_addon_title'], $addonArr['quantity'], $tax_code_id);
                        }catch (SdkException $e){ 
                            logError($e->getMessage().'Function Name: QuickBookOnlineService->createItemsLineArr()->valuePackageProductAddons');
                        }catch (ServiceException $e){
                            logError($e->getMessage().'Function Name: QuickBookOnlineService->createItemsLineArr()->valuePackageProductAddons');
                        }catch (Exception $e){
                            logError($e->getMessage().'Function Name: QuickBookOnlineService->createItemsLineArr()->valuePackageProductAddons');
                        }
                    }
                }
            }

            if(!empty($resultArr['vpComboPropAddonsArr'])){
                foreach($resultArr['vpComboPropAddonsArr'] as $vpComboPropArr){
                    foreach($vpComboPropArr as $propAddonArr){
                        foreach($propAddonArr as $addonArr){
                            try{
                                $itemsLineArr[] = $this->getLineArray($dataService, $addonArr['addon_title'], $addonArr['unit_price'], $addonArr['price'], $addonArr['product_addon_title'], $addonArr['quantity'], $tax_code_id);
                            }catch (SdkException $e){ 
                                logError($e->getMessage().'Function Name: QuickBookOnlineService->createItemsLineArr()->valuePackageComboProductAddons');
                            }catch (ServiceException $e){
                                logError($e->getMessage().'Function Name: QuickBookOnlineService->createItemsLineArr()->valuePackageComboProductAddons');
                            }catch (Exception $e){
                                logError($e->getMessage().'Function Name: QuickBookOnlineService->createItemsLineArr()->valuePackageComboProductAddons');
                            }
                        }
                    }
                }
            }

            if(!empty($resultArr['propsArr'])){
                foreach($resultArr['propsArr'] as $propArr){
                    try{
                        $itemsLineArr[] = $this->getLineArray($dataService, $propArr['product_title'], $propArr['unit_price'], $propArr['price'], '', $propArr['quantity'], $tax_code_id);
                    }catch (SdkException $e){ 
                        logError($e->getMessage().'Function Name: QuickBookOnlineService->createItemsLineArr()->Products');
                    }catch (ServiceException $e){
                        logError($e->getMessage().'Function Name: QuickBookOnlineService->createItemsLineArr()->Products');
                    }catch (Exception $e){
                        logError($e->getMessage().'Function Name: QuickBookOnlineService->createItemsLineArr()->Products');
                    }
                }
            }

            if(!empty($resultArr['propAddonsArr'])){
                foreach($resultArr['propAddonsArr'] as $propAddonArr){
                    foreach($propAddonArr as $addonArr){
                        try{
                            $itemsLineArr[] = $this->getLineArray($dataService, $addonArr['addon_title'], $addonArr['unit_price'], $addonArr['price'], $addonArr['product_addon_title'], $addonArr['quantity'], $tax_code_id);
                        }catch (SdkException $e){ 
                            logError($e->getMessage().'Function Name: QuickBookOnlineService->createItemsLineArr()->ProductAddons');
                        }catch (ServiceException $e){
                            logError($e->getMessage().'Function Name: QuickBookOnlineService->createItemsLineArr()->ProductAddons');
                        }catch (Exception $e){
                            logError($e->getMessage().'Function Name: QuickBookOnlineService->createItemsLineArr()->ProductAddons');
                        }
                    }
                }
            }
            
            if(!empty($resultArr['other_charges'])){
                $amount = 0;
                $description = '';
                $comma_flag = 0;
                if(!empty($resultArr['other_charges']['upgrade_items_price'])){
                    $amount += $resultArr['other_charges']['upgrade_items_price'];
                    $description .= 'Prop Upgrades: $'.$resultArr['other_charges']['upgrade_items_price'];
                    $comma_flag = 1;
                }
                if(!empty($resultArr['other_charges']['extra_upload_charges'])){
                    $amount += $resultArr['other_charges']['extra_upload_charges'];
                    $comma_flag == 1 ? $description .= ', ': $comma_flag = 1;
                    $description .= 'Extra Upload Charges: $'.$resultArr['other_charges']['extra_upload_charges'];
                }
                if(!empty($resultArr['other_charges']['additional_photobook_pages_charges'])){
                    $amount += $resultArr['other_charges']['additional_photobook_pages_charges'];
                    $comma_flag == 1 ? $description .= ', ': $comma_flag = 1;
                    $description .= 'Additional Photobook Pages Charges: $'.$resultArr['other_charges']['additional_photobook_pages_charges'];
                }
                if(!empty($resultArr['other_charges']['shipping_charges'])){
                    $amount += $resultArr['other_charges']['shipping_charges'];
                    $comma_flag == 1 ? $description .= ', ': $comma_flag = 1;
                    $description .= 'Shipping Charges: $'.$resultArr['other_charges']['shipping_charges'];
                }
                if(!empty($resultArr['other_charges']['edit_order_other_charges'])){
                    $amount += $resultArr['other_charges']['edit_order_other_charges'];
                    $comma_flag == 1 ? $description .= ', ': $comma_flag = 1;
                    $description .= 'Edit Order Other Amount: $'.$resultArr['other_charges']['edit_order_other_charges'];
                }
                if($amount > 0){
                    try{
                        $itemsLineArr[] = $this->getLineArray($dataService, 'Other Charges', 0.0, $amount, $description, '', $tax_code_id);
                    }catch (SdkException $e){ 
                        logError($e->getMessage().'Function Name: QuickBookOnlineService->createItemsLineArr()->OtherCharges');
                    }catch (ServiceException $e){
                        logError($e->getMessage().'Function Name: QuickBookOnlineService->createItemsLineArr()->OtherCharges');
                    }catch (Exception $e){
                        logError($e->getMessage().'Function Name: QuickBookOnlineService->createItemsLineArr()->OtherCharges');
                    }
                }
            }
            
            return $itemsLineArr;
            
        }catch (SdkException $e){ 
            logError($e->getMessage().'Function Name: QuickBookOnlineService->createItemsLineArr()');
        }
        
    }
    
    public function getItemsToCreateLines($order){
        try{
            $resultArr = [];
            $orderProductsCollection = $order->order_product->where('order_package_id', 0);
            if($orderProductsCollection->isNotEmpty()){
                #Individual Props Array
                $propsArr = [];
                foreach($orderProductsCollection as $productObj){
                    if(!empty($propsArr[$productObj->product_id])){
                        $propsArr[$productObj->product_id]['quantity'] += $productObj->product_quantity;
                        $propsArr[$productObj->product_id]['price'] += ($productObj->product_quantity/$productObj->product_unit) * $productObj->order_product_price;
                    }else{
                        $propsArr[$productObj->product_id]['quantity'] = $productObj->product_quantity;
                        $propsArr[$productObj->product_id]['price'] = ($productObj->product_quantity/$productObj->product_unit) * $productObj->order_product_price;
                    }
                    $propsArr[$productObj->product_id]['product_title'] = !empty($productObj->product->product_qb_title) ? $productObj->product->product_qb_title : $productObj->product->product_title;
                    if($productObj->is_additional == 0){
                        $propsArr[$productObj->product_id]['unit_price'] = $productObj->order_product_price;
                    }
                }

                #Individual Prop Addons Array
                $propAddonsArr = [];
                $orderProductAddonsCollection = $order->order_product_addon;
                if($orderProductAddonsCollection->isNotEmpty()){
                    foreach($orderProductAddonsCollection as $addonObj){
                        $propAddonsArr[$addonObj->product_id][$addonObj->product_addon_id]['addon_title'] = $addonObj->product_addon->addon->title;
                        $propAddonsArr[$addonObj->product_id][$addonObj->product_addon_id]['product_addon_title'] = $addonObj->product->product_qb_title . ' - ' . $addonObj->product_addon->addon->title;
                        $propAddonsArr[$addonObj->product_id][$addonObj->product_addon_id]['quantity'] = $addonObj->default_qty + ($addonObj->additional_set_qty * $addonObj->set_qty);
                        $propAddonsArr[$addonObj->product_id][$addonObj->product_addon_id]['price'] = $addonObj->price + ($addonObj->additional_set_qty * $addonObj->additional_set_charge);
                        $propAddonsArr[$addonObj->product_id][$addonObj->product_addon_id]['unit_price'] = $addonObj->product_addon->addon->price;
                    }
                }

                $resultArr['propsArr'] = $propsArr;
                $resultArr['propAddonsArr'] = $propAddonsArr;
            }

            #Value Package Props Array
            $orderPackageCollection = $order->order_package;
            if($orderPackageCollection->isNotEmpty()){
                $valuePackageArr = [];
                $value_package_title = !empty($orderPackageCollection[0]->customer_package->value_package->package_qb_title) ? $orderPackageCollection[0]->customer_package->value_package->package_qb_title : $orderPackageCollection[0]->customer_package->value_package->value_package_title;
                $valuePackageArr = [
                    'value_package_title' => $value_package_title,
                    'price' => $orderPackageCollection[0]->package_price,
                    'quantity' => 1
                ];

                $order_package_id = $orderPackageCollection[0]->id;
                $orderPackageProductsCollection  = $orderPackageCollection[0]->order_package_product;
                #Value Package Products Array
                if($orderPackageProductsCollection->isNotEmpty()){
                    foreach($orderPackageProductsCollection as $valuePackageProductObj){
                        if(!empty($valuePackageArr['value_package_products'][$valuePackageProductObj->product_id])){
                            $valuePackageArr['value_package_products'][$valuePackageProductObj->product_id]['quantity'] +=  $valuePackageProductObj->order_quantity;
                        }else{
                            $valuePackageArr['value_package_products'][$valuePackageProductObj->product_id]['quantity'] =  $valuePackageProductObj->order_quantity;
                            $valuePackageArr['value_package_products'][$valuePackageProductObj->product_id]['price'] =  $valuePackageProductObj->special_price;
                        }
                        $customerProductObj = $valuePackageProductObj->product->customer_product->where('customer_id', $order->customer_id)->first();
                        if(!empty($customerProductObj)){
                            $valuePackageArr['value_package_products'][$valuePackageProductObj->product_id]['unit_price'] =  $customerProductObj->product_price;
                        }else{
                            $common_unit_price = $valuePackageProductObj->product->product_price()->where('customer_type', $order->customer->customer_type_id)->first()->product_price;
                            $valuePackageArr['value_package_products'][$valuePackageProductObj->product_id]['unit_price'] =  $common_unit_price;
                        }
                        $valuePackageArr['value_package_products'][$valuePackageProductObj->product_id]['product_title'] =  !empty($valuePackageProductObj->product->product_qb_title) ? $valuePackageProductObj->product->product_qb_title : $valuePackageProductObj->product->product_title;
                        $valuePackageArr['value_package_products'][$valuePackageProductObj->product_id]['is_combo'] =  $valuePackageProductObj->is_combo;
                        if($valuePackageProductObj->is_combo == 1){
                            $valuePackageArr['value_package_products'][$valuePackageProductObj->product_id]['unit_price'] =  $valuePackageProductObj->special_price;
                            $orderComboItemsCollection = $valuePackageProductObj->order_combo_item;
                            foreach($orderComboItemsCollection as $comboProductObj){
                                if(!empty($valuePackageArr['value_package_products'][$valuePackageProductObj->product_id]['combo_products'][$comboProductObj->product_id])){
                                    $valuePackageArr['value_package_products'][$valuePackageProductObj->product_id]['combo_products'][$comboProductObj->product_id]['quantity'] += $comboProductObj->product_qty;
                                }else{
                                    $valuePackageArr['value_package_products'][$valuePackageProductObj->product_id]['combo_products'][$comboProductObj->product_id]['quantity'] = $comboProductObj->product_qty;
                                }
                                $customerProductObj = $comboProductObj->product->customer_product->where('customer_id', $order->customer_id)->first();
                                if(!empty($customerProductObj)){
                                    $valuePackageArr['value_package_products'][$valuePackageProductObj->product_id]['combo_products'][$comboProductObj->product_id]['unit_price'] =  $customerProductObj->product_price;
                                }else{
                                    $common_unit_price = $comboProductObj->product->product_price()->where('customer_type', $order->customer->customer_type_id)->first()->product_price;
                                    $valuePackageArr['value_package_products'][$valuePackageProductObj->product_id]['combo_products'][$comboProductObj->product_id]['unit_price'] =  $common_unit_price;
                                }
                                $valuePackageArr['value_package_products'][$valuePackageProductObj->product_id]['combo_products'][$comboProductObj->product_id]['price'] = 0.00;
                                $valuePackageArr['value_package_products'][$valuePackageProductObj->product_id]['combo_products'][$comboProductObj->product_id]['product_title'] = !empty($comboProductObj->product->product_qb_title) ? $comboProductObj->product->product_qb_title : $comboProductObj->product->product_title;

                            }
                        }
                    }
                }

                #Value Package Additional Products Array
                $vpAdditionalProductsCollection = $order->order_product->where('order_package_id', $order_package_id)->where('combo_pack_id', 0);
                if($vpAdditionalProductsCollection->isNotEmpty()){
                    foreach($vpAdditionalProductsCollection as $productObj){
                        if(!empty($valuePackageArr['value_package_products'][$productObj->product_id])){
                            $valuePackageArr['value_package_products'][$productObj->product_id]['quantity'] += $productObj->product_quantity;
                            $valuePackageArr['value_package_products'][$productObj->product_id]['price'] += ($productObj->product_quantity/$productObj->product_unit) * $productObj->order_product_price;
                        }
                    }
                }

                #Value Package Combo Pack Additional Products Array
                $vpAdditionalComboProductsCollection = $order->order_product->where('order_package_id', $order_package_id)->where('combo_pack_id', '!=', 0);
                if($vpAdditionalComboProductsCollection->isNotEmpty()){
                    foreach($vpAdditionalComboProductsCollection as $productObj){
                        if(!empty($valuePackageArr['value_package_products'][$productObj->combo_pack_id]['combo_products'][$productObj->product_id])){
                            $valuePackageArr['value_package_products'][$productObj->combo_pack_id]['combo_products'][$productObj->product_id]['quantity'] += $productObj->product_quantity;
                            $valuePackageArr['value_package_products'][$productObj->combo_pack_id]['combo_products'][$productObj->product_id]['price'] += ($productObj->product_quantity/$productObj->product_unit) * $productObj->order_product_price;
                        }
                    }
                }

                #Value Package Product Addons Array
                $vpPropAddonsArr = [];
                $orderPackageProductAddonsCollection = $order->order_package_product_addon;
                if($orderPackageProductAddonsCollection->isNotEmpty()){
                    foreach($orderPackageProductAddonsCollection as $addonObj){
                        $vpPropAddonsArr[$addonObj->product_id][$addonObj->product_addon_id]['addon_title'] = $addonObj->product_addon->addon->title;
                        $vpPropAddonsArr[$addonObj->product_id][$addonObj->product_addon_id]['product_addon_title'] = $value_package_title . ' - ' . $addonObj->product->product_qb_title . ' - ' . $addonObj->product_addon->addon->title;
                        $vpPropAddonsArr[$addonObj->product_id][$addonObj->product_addon_id]['quantity'] = $addonObj->default_qty + ($addonObj->additional_set_qty * $addonObj->set_qty);
                        $vpPropAddonsArr[$addonObj->product_id][$addonObj->product_addon_id]['price'] = ($addonObj->price * ($addonObj->default_qty / $addonObj->quantity)) + ($addonObj->additional_set_qty * $addonObj->additional_set_charge);
                        $vpPropAddonsArr[$addonObj->product_id][$addonObj->product_addon_id]['unit_price'] = $addonObj->product_addon->addon->value_package_price;
                    }
                }

                #Value Package Combo Product Addons Array
                $vpComboPropAddonsArr = [];
                $orderComboPackProductAddonsCollection = $order->order_combo_pack_product_addon;
                if($orderComboPackProductAddonsCollection->isNotEmpty()){
                    foreach($orderComboPackProductAddonsCollection as $addonObj){
                        $combo_pack_id = $addonObj->value_package_product->product_id;
                        $combo_title = $addonObj->value_package_product->product->product_qb_title;
                        $vpComboPropAddonsArr[$combo_pack_id][$addonObj->product_id][$addonObj->product_addon_id]['addon_title'] = $addonObj->product_addon->addon->title;
                        $vpComboPropAddonsArr[$combo_pack_id][$addonObj->product_id][$addonObj->product_addon_id]['product_addon_title'] = $combo_title . ' - ' . $addonObj->product->product_qb_title . ' - ' . $addonObj->product_addon->addon->title;
                        $vpComboPropAddonsArr[$combo_pack_id][$addonObj->product_id][$addonObj->product_addon_id]['quantity'] = $addonObj->default_qty + ($addonObj->additional_set_qty * $addonObj->set_qty);
                        $vpComboPropAddonsArr[$combo_pack_id][$addonObj->product_id][$addonObj->product_addon_id]['price'] = ($addonObj->price * ($addonObj->default_qty / $addonObj->quantity)) + ($addonObj->additional_set_qty * $addonObj->additional_set_charge);
                        $vpComboPropAddonsArr[$combo_pack_id][$addonObj->product_id][$addonObj->product_addon_id]['unit_price'] = $addonObj->product_addon->addon->value_package_price;
                    }
                }

                $resultArr['valuePackageArr'] = $valuePackageArr;
                $resultArr['vpPropAddonsArr'] = $vpPropAddonsArr;
                $resultArr['vpComboPropAddonsArr'] = $vpComboPropAddonsArr;
            }
            
            $orderProductUpgradeItemsCollection = $order->order_product_upgrade_items;
            if(!empty($orderProductUpgradeItemsCollection)){
                $resultArr['other_charges']['upgrade_items_price'] = 0;
                foreach($orderProductUpgradeItemsCollection as $upgradeItemObj){
                    $resultArr['other_charges']['upgrade_items_price'] += $upgradeItemObj->prop_order_quantity * $upgradeItemObj->price;
                }
            }
            $resultArr['other_charges']['extra_upload_charges'] = floatVal($order->extra_uploaded_attachment_count * $order->extra_uploading_charges);
            $resultArr['other_charges']['additional_photobook_pages_charges'] = floatVal($order->additional_photobook_pages_charges);
            $resultArr['other_charges']['shipping_charges'] = floatVal($order->order_shipping_detail->shipping_amount);
            $resultArr['other_charges']['edit_order_other_charges'] = floatVal($order->other_amount);

            return $resultArr;
        }catch (Exception $e){ 
            logError($e->getMessage().'Function Name: QuickBookOnlineService->getItemsToCreateLines()');
        }
        
    }
    
    public function getLineArray($dataService, $item_title, $unit_price, $amount, $item_line_name, $quantity, $tax_code_id){
        #Here, $item_title is required to check whether item exist or not, $unit_price need to create item with base price, $amount is the total amount of item that ordered, $item_line_name and $quantity is attributes printed in invoice and $tax_code_id is which tax is required to use in the invoice
        try{
            $itemsArr = $dataService->Query("SELECT * FROM Item where Name = '".$item_title."'");
            if(empty($itemsArr)){
                $item_id = $this->createItem($dataService, $item_title, $unit_price);
            }else{
                $item_id = $itemsArr[0]->Id;
            }
            $itemLineArr = [
                "Description" => $item_line_name,
                "Amount" => $amount,
                "DetailType" => "SalesItemLineDetail",
                "SalesItemLineDetail" => [
                    "ItemRef" => [
                        "value" => $item_id,
                        "name" => ''
                    ],
                    "TaxCodeRef" => [
                        "value" => $tax_code_id
                    ],
                    "Qty" => $quantity
                ]
            ];
            return $itemLineArr;
        } catch (SdkException $e){ 
            logError($e->getMessage().'Function Name: QuickBookOnlineService->getLineArray()');
        } catch (ServiceException $e){ 
            logError($e->getMessage().'Function Name: QuickBookOnlineService->getLineArray()');
        } catch (Exception $e){ 
            logError($e->getMessage().'Function Name: QuickBookOnlineService->getLineArray() - Exception');
        }
        
    }
    
    public function getTaxCodeId($dataService, $order){
        $tax_percentage = $order->tax_type_id == 1 ? $order->tax_percentage : $order->tax_type->tax_percentage;
        $tax_name = $order->tax_type->quickbook_title.' @'.$tax_percentage.'%';
        #$qb_tax_code_id = $order->tax_type->qb_tax_code_id;
        $tax_code_id = 0;
        
        try{
            $taxCodeArr = $dataService->Query("Select * From TaxCode where Name = '$tax_name'");
            if(!empty($taxCodeArr[0]) && $taxCodeArr[0]->Active == 'true'){
                $tax_code_id = $taxCodeArr[0]->Id;
            }
            if($tax_code_id == 0){
                return $this->createTaxCode($dataService, $order);
            }
            return $tax_code_id;
        } catch (SdkException $e){ 
            logError($e->getMessage().'Function Name: QuickBookOnlineService->getTaxCodeId()');
        } catch (ServiceException $e){ 
            logError($e->getMessage().'Function Name: QuickBookOnlineService->getTaxCodeId()');
        } catch (Exception $e){ 
            logError($e->getMessage().'Function Name: QuickBookOnlineService->getTaxCodeId()');
        }
        
    }
    
    public function createTaxCode($dataService, $order){
        
        $tax_percentage = $order->tax_type_id == 1 ? $order->tax_percentage : $order->tax_type->tax_percentage;
        $tax_name = $order->tax_type->quickbook_title.' @'.$tax_percentage.'%';
        try{
            $taxRateDetailsArr = array();
            $currentTaxDetailArr = TaxRate::create([
                "TaxRateName" => $tax_name,   //TaxRateName should be unique in whole system
                "RateValue" => $tax_percentage,
                "TaxAgencyId" => "3",
                "TaxApplicableOn" => "Sales"
            ]);
            $taxRateDetailsArr[] = $currentTaxDetailArr;

            $taxService = TaxService::create([
              "TaxCode" => $tax_name, //TaxCodeName should be unique in whole system only in case of US-Based company
              "TaxRateDetails" => $taxRateDetailsArr
            ]);

            $taxServiceObj = $dataService->Add($taxService);
            foreach($taxServiceObj as $taxService){
                $tax_code_id = $taxService->TaxCodeId;
            }

            #Update taxType table
            $taxTypeObj = TaxType::find($order->tax_type_id);
            $taxTypeObj->timestamps = false;
            $taxTypeObj->qb_tax_code_id = $tax_code_id;
            $taxTypeObj->save();
            return $tax_code_id;
        } catch (SdkException $e){ 
            logError($e->getMessage().'Function Name: QuickBookOnlineService->createTaxCode()');
        } catch (ServiceException $e){ 
            logError($e->getMessage().'Function Name: QuickBookOnlineService->createTaxCode()');
        } catch (Exception $e){ 
            logError($e->getMessage().'Function Name: QuickBookOnlineService->createTaxCode()');
        }
        
    }
    
}
