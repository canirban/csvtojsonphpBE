<?php
 
class ConverterService
{
    var $error;

    public function converter($content){
        global $error;
        try{
            return $this->getJson($content);
        }catch(Error $e){
            $message = "Error occured while converting. Please try again later";
            return $this->getError($message);
        }
        
    }

    private function getJson($content)
    {   global $error;
        //get csv as stream
        $csvResource=$this->getCsvResource($content);
        //convert stream to array
        $parsedArray=$this->parseCsvToArray($csvResource);
        //if parsed csv to array is not empty
        if($parsedArray!=null){
            //filter 
            $filteredArray=$this->filterArray($parsedArray);
            //merge
            $mergedArray=$this->mergeItems($filteredArray);
            $mergedArr = array("json"=>array_merge(array(), $mergedArray),"error"=>$error);
            return (json_encode($mergedArr,  JSON_NUMERIC_CHECK | JSON_UNESCAPED_SLASHES));
        }

        $message='Error at Header Please Enter a valid header, header must be similar to this ["Row ID","Order ID","Order Date","Ship Date","Ship Mode","Customer ID","Customer Name","Segment","Country","City","State","Postal Code","Region","Product ID","Category","Sub-Category","Product Name","Sales","Quantity","Discount","Profit"]';
        return $this->getError($message);

            
    }



    private function getCsvHeader(){
        return Array("Row ID","Order ID","Order Date","Ship Date"
        ,"Ship Mode","Customer ID","Customer Name","Segment","Country","City",
        "State","Postal Code","Region","Product ID","Category","Sub-Category","Product Name",
        "Sales", "Quantity","Discount","Profit");
    }

    private function getIsoDateCompareValue(){
        $datetime = new DateTime("7/31/2016");
        return $datetime->format(DateTime::ATOM);
    }

    private function getCsvResource($text){

        $stream = fopen('php://memory', 'r+');
        fwrite($stream, $text);
        rewind($stream);

        return $stream;

    }

    private function parseCsvToArray($fp){
        global $error;
        $key = fgetcsv($fp,"1024","\t");

        $csvHeaderKey=$this->getCsvHeader();
        if(count( $key ) == count( $csvHeaderKey ) && !array_diff( $key, $csvHeaderKey ) ){
        $json = array();
        while ($row = fgetcsv($fp,"1024","\t")) {
            $diff=count($key)-count($row);
            if($diff==0)
             $json[] = array_combine($key, $row);
             else{
                $error=$error."\nIncorrect CSV format encountered. Header doesn't match with values";
             }
        }
            // release file handle
            fclose($fp);
            $jsonArray=json_encode($json, JSON_UNESCAPED_SLASHES);
            return json_decode($jsonArray);
        }
        return null;
    }

    private function filterArray($csvArray){
        return $this->createLineItems($csvArray);
    }

    private function createLineItems($csvArray){
        global $error;

        $baseUrl="https://www.foo.com";
        $lineItemsArray=array();
        foreach($csvArray as $element){
            
            $dateComparator= $this->getIsoDateCompareValue();
            
            $date=$element->{"Order Date"};
            $datetime = new DateTime($date);
            $date=$datetime->format(DateTime::ATOM);
            
            if($date>$dateComparator){
            $element->{"Order Date"}=$date;
            $orderId=$element->{"Order ID"};
            $orderDate=$element->{"Order Date"};
            $sales=$element->{"Sales"};
            $category=$element->{"Category"};
            $subCategory=$element->{"Sub-Category"};
            $productId=$element->{"Product ID"};
            $url=$baseUrl."/".$category."/".$subCategory."/".$productId;

             if(!is_numeric($sales)){
                $error=$error.
                "\nError at Row Id ".$element->{"Row ID"}.
                ". Please enter a valid number for sales price, entered price is : "
                .$sales.". Hence setting it to null";
                $sales=null;
             }

            

            $lineItems=array();
                array_push($lineItems,array(
                    "product_url"=>$url,
                    "revenue"=>$sales
            ));
                
            $orders=array();

            array_push($orders,array( 
                    "order_id"=>$orderId,
                    "order_date"=>$orderDate,"line_items"=>$lineItems));

            $element = (object) array_merge(
                 (array)$element, array( 'orders' => $orders ) );
            
            array_push($lineItemsArray,$element);

            }else{

                $error=$error."\nError at Row Id ".$element->{"Row ID"}
                ." Invalid date, Input date:".$element->{"Order Date"}.". Hence not parsed";
            
            }
        }
        return $lineItemsArray;
    }
    private function mergeItems($arr){

        $len=count($arr);
        
        for($i=0;$i<$len;$i++){
            if (isset($arr[$i])) {

                    $icustomerName=$arr[$i]->{"Customer Name"};
                    $iOrderId=$arr[$i]->{"Order ID"};
                    $iOrders=$arr[$i]->{"orders"};
                    $iLineItems=$iOrders[0]["line_items"];

                for($j=$i+1;$j<$len;$j++){
                    
                    if (isset($arr[$j])) {

                        $jcustomerName=$arr[$j]->{"Customer Name"};
                        $jOrderId=$arr[$i]->{"Order ID"};
                        $jOrders=$arr[$j]->{"orders"};
                        $jLineItems=$jOrders[0]["line_items"];

                        if($icustomerName==$jcustomerName){

                            if($iOrderId==$jOrderId){
                                $merge=array_merge($iLineItems, $jLineItems);
                                $arr[$i]->{"orders"}[0]["line_items"]=$merge;
                            }else{
                                $arr[$i]->{"orders"}=array_merge($iOrders, $jOrders);
                            }
                            unset($arr[$j]);
                        }
                    }
                }
                $this->unSetUnUsedValues($arr[$i]);
                

                $arr[$i]=array($arr[$i]->{"Customer Name"}=>$arr[$i]->{"orders"});
             }
        }
        return $arr;
    }
    private function unSetUnUsedValues($element){
        $removeKeys=$this->getRemoveKeys();
        foreach($removeKeys as $key) {
            unset($element->$key);
         }
    }
    private function getRemoveKeys(){
        return array("Row ID","Order ID","Order Date","Ship Date"
        ,"Ship Mode","Customer ID","Segment","Country","City",
        "State","Postal Code","Region","Product ID","Category","Sub-Category","Product Name",
        "Sales", "Quantity","Discount","Profit");
    }

    private function getError($message){
        global $error;
        $error=$error.$message;
        return trim(json_encode(array("json"=>[],"error"=>$error), JSON_UNESCAPED_SLASHES), '[]');
    }

}
?>