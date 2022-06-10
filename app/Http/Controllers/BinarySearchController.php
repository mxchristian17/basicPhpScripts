<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BinarySearchController extends Controller
{
    public function binarySearchPage()
    {
        $description = 'In computer science, binary search, also known as half-interval search,[1] logarithmic search,[2] or binary chop,[3] is a search algorithm that finds the position of a target value within a sorted array.[4][5] Binary search compares the target value to the middle element of the array. If they are not equal, the half in which the target cannot lie is eliminated and the search continues on the remaining half, again taking the middle element to compare to the target value, and repeating this until the target value is found. If the search ends with the remaining half being empty, the target is not in the array.

        Binary search runs in logarithmic time in the worst case, making {\displaystyle O(\log n)}O(\log n) comparisons, where {\displaystyle n}n is the number of elements in the array.[a][6] Binary search is faster than linear search except for small arrays. However, the array must be sorted first to be able to apply binary search. There are specialized data structures designed for fast searching, such as hash tables, that can be searched more efficiently than binary search. However, binary search can be used to solve a wider range of problems, such as finding the next-smallest or next-largest element in the array relative to the target even if it is absent from the array.
        
        There are numerous variations of binary search. In particular, fractional cascading speeds up binary searches for the same value in multiple arrays. Fractional cascading efficiently solves a number of search problems in computational geometry and in numerous other fields. Exponential search extends binary search to unbounded lists. The binary search tree and B-tree data structures are based on binary search.
        
        Binary search works on sorted arrays. Binary search begins by comparing an element in the middle of the array with the target value. If the target value matches the element, its position in the array is returned. If the target value is less than the element, the search continues in the lower half of the array. If the target value is greater than the element, the search continues in the upper half of the array. By doing this, the algorithm eliminates the half in which the target value cannot lie in each iteration.';
        
        $pseudoCode = '
        function binary_search(A, n, T) is
            L := 0
            R := n − 1
            while L ≤ R do
                m := floor((L + R) / 2)
                if A[m] < T then
                    L := m + 1
                else if A[m] > T then
                    R := m − 1
                else:
                    return m
            return unsuccessful';
        $formAction = '/binarysearchpost';
        $formInputs = ''; 
        //$title = Info::where('attribute_name', '=', 'Site_title')->first()->attribute_value;
        return view('functions')->withTitle('Binary search algorithm')
                                ->withDescription($description)->withPseudoCode($pseudoCode)->withFormAction($formAction);
    }

    private function binarySearch(Array $arr, $start, $end, $x){
        if ($end < $start)
            return false;
       
        $mid = floor(($end + $start)/2);
        if ($arr[$mid] == $x) 
            return true;
      
        elseif ($arr[$mid] > $x) {
      
            // call binarySearch on [start, mid - 1]
            return $this->binarySearch($arr, $start, $mid - 1, $x);
        }
        else {
      
            // call binarySearch on [mid + 1, end]
            return $this->binarySearch($arr, $mid + 1, $end, $x);
        }
    }

    public function binarySearchPost(Request $request)
    {
        //return json_encode([0,0,0]);
        $validator = Validator::make($request->all(), [
            '_token' => 'required',
            'varList' => 'array',
            'varList.*' => 'required|string',
            'searchValue' => 'string',
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
                $startVal = rand(-1000000,1000000);
                for($i=0;$i<$request->testQty;$i++) array_push($testValues, $startVal = $startVal + rand(0,10));
            }elseif($request->testType == 'text'){
                $startVal = 'a';
                for($i=0;$i<$request->testQty;$i++) array_push($testValues, $startVal = $startVal++);
            }else{
                return json_encode(['El tipo de valor para el testeo es incorrecto...', 0]);
            }
        }

        $result[0] = "[";
        foreach($testValues as $item){ $result[0] .= $item.', '; };
        $result[0] = substr($result[0], 0, -2);
        $result[0] .= "]";

        $time_start = microtime(true);

        $result[1] = $this->binarySearch($testValues, $testValues[0], $testValues[count($testValues)-1], $request->searchValue);

        $time_end = microtime(true);

        $result[2] = $execution_time = ($time_end - $time_start);
        return json_encode($result);
    }
    
}
