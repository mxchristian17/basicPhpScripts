<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BucketSortController extends Controller
{
    public function bucketSortPage()
    {
        $description = 'Bucket sort, or bin sort, is a sorting algorithm that works by distributing the elements of an array into a number of buckets. Each bucket is then sorted individually, either using a different sorting algorithm, or by recursively applying the bucket sorting algorithm. It is a distribution sort, a generalization of pigeonhole sort that allows multiple keys per bucket, and is a cousin of radix sort in the most-to-least significant digit flavor. Bucket sort can be implemented with comparisons and therefore can also be considered a comparison sort algorithm. The computational complexity depends on the algorithm used to sort each bucket, the number of buckets to use, and whether the input is uniformly distributed.

        It is only for positive numeric values. It works better with small numbers.
        Bucket sort works as follows:
        
        Set up an array of initially empty "buckets".
        Scatter: Go over the original array, putting each object in its bucket.
        Sort each non-empty bucket.
        Gather: Visit the buckets in order and put all elements back into the original array.';
        
        $pseudoCode = 'function bucketSort(array, k) is
        buckets ← new array of k empty lists
        M ← the maximum key value in the array
        for i = 0 to length(array) do
            insert array[i] into buckets[floor(k × array[i] / M)]
        for i = 0 to k do 
            nextSort(buckets[i])
        return the concatenation of buckets[0], ...., buckets[k]';
        $formAction = '/bucketsortpost';
        //$title = Info::where('attribute_name', '=', 'Site_title')->first()->attribute_value;
        return view('functions')->withTitle('Bucket sort algorithm')
                                ->withDescription($description)->withPseudoCode($pseudoCode)->withFormAction($formAction);
    }

    private function bucketSort($data)
    {
        function bucket_sort(&$data)
        {
            
            $bucketList = [];
            $maxValue = max($data);
            for($i=0;$i <= $maxValue;$i++){
                $bucketList[$i] = 0;
            }
            foreach($data as $n){
                $bucketList[$n]++;
            }
            $sortList = [];
            foreach($bucketList as $k => $v){
                if($v > 0){
                    for( ; $v > 0 ; $v--){
                        $sortList[] = $k;
                    }
                }
            }
            return $sortList;
        }

        return bucket_sort($data);
    }

    public function bucketSortPost(Request $request)
    {
        function randomString() {
            $characters = '0123456789';
            $randomString = '';
        
            for ($i = 0; $i < 4; $i++) {
                $index = rand(0, strlen($characters) - 1);
                $randomString .= $characters[$index];
            }
        
            return intval($randomString);
        }

        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'varList' => 'array',
            'varList.*' => 'required|numeric',
            'test' => 'boolean',
            'testType' => 'string',
            'testQty' => 'numeric'
        ],
        [
            'varList.required' => 'Es necesario que incluyas al menos un valor'
        ]);

        if ($validator->fails()) {
            return json_encode([$validator->errors()->first(), 0]);
        }

        if($request->test == 0) {
            if(!isset($request->varList)) return json_encode(['Debes definir al menos un valor...', 0]);
            $testValues = $request->varList;
        }else{
            $testValues = [];
            if($request->testType == 'numeric') {
                for($i=0;$i<$request->testQty;$i++) array_push($testValues, rand(0,1000));
            }elseif($request->testType == 'text'){
                for($i=0;$i<$request->testQty;$i++) array_push($testValues, randomString());
            }else{
                return json_encode(['El tipo de valor para el testeo es incorrecto...', 0]);
            }
        }

        $result[0] = "[";
        foreach($testValues as $item){ $result[0] .= $item.', '; };
        $result[0] = substr($result[0], 0, -2);
        $result[0] .= "]";

        $time_start = microtime(true);

        $result[1] = "[";
        foreach($this->bucketSort($testValues) as $item){ $result[1] .= $item.', '; };
        $result[1] = substr($result[1], 0, -2);
        $result[1] .= "]";

        $time_end = microtime(true);

        $result[2] = $execution_time = ($time_end - $time_start);
        return json_encode($result);
    }
}
