<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Models\Transaction;
use App\Models\Balance;
use App\Models\TCoinPrice;

class TransactionController extends Controller
{
    public function transaction(Request $request)
    {
        $this->validate($request, [
            'trx_id'    => 'required|string|unique:transaction', // If trx_id exists, decline & rollback transaction
            'amount'    => 'required',
            'user_id'   => 'required'
        ]);   

        if($request->amount <=  0.00000001){
            return response()->json(['status'=>'failed','message'=>'Amount is same or less than 0.00000001 !','code'=>400]);
            die();
        }
        // find balance by user_id from table balance,
        $checkBalance = Balance::where("id", $request->user_id)->first();
        // if($checkBalance){
            // if balance.amount_available < input.amount, decline (insufficient)
        if($checkBalance->amount_available < $request->amount){
            return response()->json(['status'=>'failed','message'=>'Amount available is less than amount!','code'=>400]);
            die();
        }
        // Insert table transaction
        $result = Transaction::create($request->all());
        // } 
        if($result){
            // update balance.amount_available, (balance.amount_available - input.amount)
            $dataUpdate = array(
                "amount_available" => $checkBalance->amount_available - $request->amount
            );
            $dataWillUpdate = Balance::where("user_id", $request->user_id);
            $dataWillUpdate->update($dataUpdate);
            return response()->json(['status'=>'success', 
                                        'data' => ['trx_id' => $request->trx_id, 'amount' => $request->amount],
                                        'message'=>'Transaction successfull created','code'=>200]);
        } 
    }

    public function importCsv(Request $request)
    {
        $file = $request->file('uploaded_file');
        if ($file) {
            $filename = $file->getClientOriginalName();
            $extension = $file->getClientOriginalExtension(); //Get extension of uploaded file
            $tempPath = $file->getRealPath();
            $fileSize = $file->getSize(); //Get size of uploaded file in bytes
            //Check for file extension and size
            $this->checkUploadedFileProperties($extension, $fileSize);
            // Upload file
            $file->move($this->pathDocument, $filename); 
            $file = fopen($this->pathDocument . "/" . $filename, "r");
            $importData_arr = array(); // Read through the file and store the contents as an array
            $i = 0;
            //Read the contents of the uploaded file 
            while (($filedata = fgetcsv($file, 1000, ",")) !== FALSE) {
                $num = count($filedata);
                // Skip first row (Remove below comment if you want to skip the first row)
                if ($i == 0) {
                    $i++;
                    continue;
                }
                for ($c = 0; $c < $num; $c++) {
                    $importData_arr[$i][] = $filedata[$c];
                }
                $i++;
            }
            fclose($file); //Close after reading
            $j = 0;
                foreach ($importData_arr as $importData) 
                { 
                    $j++;
                    try {                  
                        $data['name'] = $importData[1];
                        $data['ticker'] = $importData[2];
                        $data['coin_id'] = (int)$importData[3];
                        $data['code'] = $importData[4];
                        $data['exchange'] = $importData[5];
                        $data['invalid'] = (int)$importData[6];
                        $data['record_time'] = $importData[7];
                        $data['usd'] = (float)$importData[8];
                        $data['idr'] = (float)$importData[9];
                        $data['hnst'] = (int)$importData[10];
                        $data['eth'] = (float)$importData[11];
                        $data['btc'] = (int)$importData[12];
                        $data['created_at'] = $importData[13];
                        $data['updated_at'] = $importData[14];
                        

                        $result = TCoinPrice::create($data); 
                    } catch (\Exception $e) { 
                        return response()->json(['status'=>'failed','message'=>'Import data failed !','code'=>400]);
                    }
                }
                return response()->json(['status'=>'success','message'=>'Import data successfull ','code'=>200]);
        } else {
        //no file was uploaded
            throw new \Exception('No file was uploaded', Response::HTTP_BAD_REQUEST);
        }
    }

    public function checkUploadedFileProperties($extension, $fileSize)
    {
        $valid_extension = array("csv", "xlsx"); //Only want csv and excel files
        $maxFileSize = 2097152000; // Uploaded file size limit is 2000mb
        if (in_array(strtolower($extension), $valid_extension)) {
            if ($fileSize <= $maxFileSize) {
            } else {
                throw new \Exception('No file was uploaded', Response::HTTP_REQUEST_ENTITY_TOO_LARGE); //413 error
            }
        } else {
            throw new \Exception('Invalid file extension', Response::HTTP_UNSUPPORTED_MEDIA_TYPE); //415 error
        }
    }
    public function lowHigh(Request $request){
        $this->validate($request, [
            'month'      => 'required',
            'year'      => 'required',
            'ticker'    => 'required',
            'currency'  => 'required'
        ]);
        if($request->currency == 'idr'){
            $dataMax = TCoinPrice::select('id', 'name', 'ticker', 'coin_id', 'code', 'exchange', 'invalid', 'record_time', 'idr', 'hnst', 'eth', 'btc', 'created_at', 'updated_at')
                            ->where('ticker', $request->ticker)
                            ->whereYear('created_at', $request->year)
                            ->whereMonth('created_at', $request->month)
                            ->orderByRaw('idr DESC')
                            // ->get();
                            ->first();
            $dataMin = TCoinPrice::select('id', 'name', 'ticker', 'coin_id', 'code', 'exchange', 'invalid', 'record_time', 'idr', 'hnst', 'eth', 'btc', 'created_at', 'updated_at')
                            ->where('ticker', $request->ticker)
                            ->whereYear('created_at', $request->year)
                            ->whereMonth('created_at', $request->month)
                            ->orderByRaw('idr ASC')
                            // ->get();
                            ->first();
        }else{
            $dataMax = TCoinPrice::select('id', 'name', 'ticker', 'coin_id', 'code', 'exchange', 'invalid', 'record_time', 'usd', 'hnst', 'eth', 'btc', 'created_at', 'updated_at')
                            ->where('ticker', $request->ticker)
                            ->whereYear('created_at', $request->year)
                            ->whereMonth('created_at', $request->month)
                            ->orderByRaw('idr DESC')
                            // ->get();
                            ->first();

            $dataMin = TCoinPrice::select('id', 'name', 'ticker', 'coin_id', 'code', 'exchange', 'invalid', 'record_time', 'usd', 'hnst', 'eth', 'btc', 'created_at', 'updated_at')
                            ->where('ticker', $request->ticker)
                            ->whereYear('created_at', $request->year)
                            ->whereMonth('created_at', $request->month)
                            ->orderByRaw('idr DESC')
                            // ->get();
                            ->first();
        }
        return response()->json(['status'=>'success','data'=> ['min'=>$dataMin, 'max'=> $dataMax, 'month'=> $request->month, 'year' =>$request->year],'code'=>200]);
        
    }

} 