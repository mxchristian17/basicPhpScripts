<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MergeSortController extends Controller
{
    public function mergeSortPage()
    {
        $description = 'El algoritmo de ordenamiento por mezcla es un algoritmo de ordenamiento externo estable basado en la técnica divide y vencerás. Es de complejidad O(n log n).Conceptualmente, el ordenamiento por mezcla funciona de la siguiente manera:

            Si la longitud de la lista es 0 o 1, entonces ya está ordenada. En otro caso:
            Dividir la lista desordenada en dos sublistas de aproximadamente la mitad del tamaño.
            Ordenar cada sublista recursivamente aplicando el ordenamiento por mezcla.
            Mezclar las dos sublistas en una sola lista ordenada.
            El ordenamiento por mezcla incorpora dos ideas principales para mejorar su tiempo de ejecución:
            
            Una lista pequeña necesitará menos pasos para ordenarse que una lista grande.
            Se necesitan menos pasos para construir una lista ordenada a partir de dos listas también ordenadas, que a partir de dos listas desordenadas. Por ejemplo, sólo será necesario entrelazar cada lista una vez que están ordenadas.';
        
        $pseudoCode = 'function mergesort(m)
        var list left, right, result
        if length(m) ≤ 1
            return m
        else
            var middle = length(m) / 2
            for each x in m up to middle - 1
                add x to left
            for each x in m at and after middle
                add x to right
            left = mergesort(left)
            right = mergesort(right)
            if last(left) ≤ first(right) 
               append right to left
               return left
            result = merge(left, right)
            return result';
        $formAction = '/mergesortpost';
        //$title = Info::where('attribute_name', '=', 'Site_title')->first()->attribute_value;
        return view('functions')->withTitle('Merge sort algorithm')
                                ->withDescription($description)->withPseudoCode($pseudoCode)->withFormAction($formAction);
    }

    private function mergeSort($data)
    {
        function merge_sort($my_array){
            if(count($my_array) == 1 ) return $my_array;
            $mid = count($my_array) / 2;
            $left = array_slice($my_array, 0, $mid);
            $right = array_slice($my_array, $mid);
            $left = merge_sort($left);
            $right = merge_sort($right);
            return merge($left, $right);
        }
        function merge($left, $right){
            $res = array();
            while (count($left) > 0 && count($right) > 0){
                if($left[0] > $right[0]){
                    $res[] = $right[0];
                    $right = array_slice($right , 1);
                }else{
                    $res[] = $left[0];
                    $left = array_slice($left, 1);
                }
            }
            while (count($left) > 0){
                $res[] = $left[0];
                $left = array_slice($left, 1);
            }
            while (count($right) > 0){
                $res[] = $right[0];
                $right = array_slice($right, 1);
            }
            return $res;
        }

        return merge_sort($data);
    }

    public function mergeSortPost(Request $request)
    {
        
        function randomString() {
            $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $randomString = '';
        
            for ($i = 0; $i < 7; $i++) {
                $index = rand(0, strlen($characters) - 1);
                $randomString .= $characters[$index];
            }
        
            return $randomString;
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
                for($i=0;$i<$request->testQty;$i++) array_push($testValues, rand(-1000000,1000000));
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
        foreach($this->mergeSort($testValues) as $item){ $result[1] .= $item.', '; };
        $result[1] = substr($result[1], 0, -2);
        $result[1] .= "]";

        $time_end = microtime(true);

        $result[2] = $execution_time = ($time_end - $time_start);
        return json_encode($result);
    }
}
