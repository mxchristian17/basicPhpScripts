<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CountingSortController extends Controller
{
    public function countingSortPage()
    {
        $description = '- Good for: Positive integer numbers.
        - Running time linear.
        
        In computer science, counting sort is an algorithm for sorting a collection of objects according to keys that are small positive integers; that is, it is an integer sorting algorithm. It operates by counting the number of objects that possess distinct key values, and applying prefix sum on those counts to determine the positions of each key value in the output sequence. Its running time is linear in the number of items and the difference between the maximum key value and the minimum key value, so it is only suitable for direct use in situations where the variation in keys is not significantly greater than the number of items. It is often used as a subroutine in radix sort, another sorting algorithm, which can handle larger keys more efficiently.[1][2][3]

        Counting sort is not a comparison sort; it uses key values as indexes into an array and the Ω(n log n) lower bound for comparison sorting will not apply.[1] Bucket sort may be used in lieu of counting sort, and entails a similar time analysis. However, compared to counting sort, bucket sort requires linked lists, dynamic arrays, or a large amount of pre-allocated memory to hold the sets of items within each bucket, whereas counting sort stores a single number (the count of items) per bucket.';
        
        $pseudoCode = '
        function CountingSort(input, k)
    
            count ← array of k + 1 zeros
            output ← array of same length as input
            
            for i = 0 to length(input) - 1 do
                j = key(input[i])
                count[j] += 1

            for i = 1 to k do
                count[i] += count[i - 1]

            for i = length(input) - 1 downto 0 do
                j = key(input[i])
                count[j] -= 1
                output[count[j]] = input[i]

            return output';
        $formAction = '/countingsortpost';
        //$title = Info::where('attribute_name', '=', 'Site_title')->first()->attribute_value;
        return view('functions')->withTitle('Counting sort algorithm')
                                ->withDescription($description)->withPseudoCode($pseudoCode)->withFormAction($formAction);
    }

    private function countingSort($data)
    {
        // function for counting sort
        function counting_sort(&$Array) {
            $n = sizeof($Array);
            $max = 0;
            
            //find largest element in the Array
            for ($i=0; $i<$n; $i++) {  
                if($max < $Array[$i]) {
                    $max = $Array[$i];
                } 
            }
        
            //Create a freq array to store number of occurrences of 
            //each unique elements in the given array 
            for ($i=0; $i<$max+1; $i++) {  
                $freq[$i] = 0;
            } 
        
            for ($i=0; $i<$n; $i++) {  
                $freq[$Array[$i]]++;
            } 
        
            //sort the given array using freq array
            for ($i=0, $j=0; $i<=$max; $i++) {  
                while($freq[$i]>0) {
                    $Array[$j] = $i;
                    $j++;
                    $freq[$i]--;
                }
            } 
        }

        counting_sort($data,0,count($data)-1);

        return $data;
    }

    public function countingSortPost(Request $request)
    {
        function randomString() {
            $characters = '0123456789';
            $randomString = '';
        
            for ($i = 0; $i < 7; $i++) {
                $index = rand(0, strlen($characters) - 1);
                $randomString .= $characters[$index];
            }
        
            return intval($randomString);
        }

        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'varList' => 'array',
            'varList.*' => 'required|string',
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
                for($i=0;$i<$request->testQty;$i++) array_push($testValues, rand(0,1000000));
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
        foreach($this->countingSort($testValues) as $item){ $result[1] .= $item.', '; };
        $result[1] = substr($result[1], 0, -2);
        $result[1] .= "]";

        $time_end = microtime(true);

        $result[2] = $execution_time = ($time_end - $time_start);
        return json_encode($result);
    }
}
