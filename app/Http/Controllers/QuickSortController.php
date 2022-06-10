<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class QuickSortController extends Controller
{
    public function quickSortPage()
    {
        $description = 'El algoritmo trabaja de la siguiente forma:
            Elegir un elemento del conjunto de elementos a ordenar, al que llamaremos pivote.
            Resituar los demás elementos de la lista a cada lado del pivote, de manera que a un lado queden todos los menores que él, y al otro los mayores. Los elementos iguales al pivote pueden ser colocados tanto a su derecha como a su izquierda, dependiendo de la implementación deseada. En este momento, el pivote ocupa exactamente el lugar que le corresponderá en la lista ordenada.
            La lista queda separada en dos sublistas, una formada por los elementos a la izquierda del pivote, y otra por los elementos a su derecha.
            Repetir este proceso de forma recursiva para cada sublista mientras éstas contengan más de un elemento. Una vez terminado este proceso todos los elementos estarán ordenados.
            Como se puede suponer, la eficiencia del algoritmo depende de la posición en la que termine el pivote elegido.
            En el mejor caso, el pivote termina en el centro de la lista, dividiéndola en dos sublistas de igual tamaño. En este caso, el orden de complejidad del algoritmo es O(n·log n).
            En el peor caso, el pivote termina en un extremo de la lista. El orden de complejidad del algoritmo es entonces de O(n²). El peor caso dependerá de la implementación del algoritmo, aunque habitualmente ocurre en listas que se encuentran ordenadas, o casi ordenadas. Pero principalmente depende del pivote, si por ejemplo el algoritmo implementado toma como pivote siempre el primer elemento del array, y el array que le pasamos está ordenado, siempre va a generar a su izquierda un array vacío, lo que es ineficiente.
            En el caso promedio, el orden es O(n·log n).
            No es extraño, pues, que la mayoría de optimizaciones que se aplican al algoritmo se centren en la elección del pivote.';
        
        $pseudoCode = '
        /**
        * The main function that implements quick sort.
        * @Parameters: array, starting index and ending index
        */
        quickSort(arr[], low, high)
        {
            if (low < high)
            {
                // pivot_index is partitioning index, arr[pivot_index] is now at correct place in sorted array
                pivot_index = partition(arr, low, high);
        
                quickSort(arr, low, pivot_index - 1);  // Before pivot_index
                quickSort(arr, pivot_index + 1, high); // After pivot_index
            }
        }
        
        /**
        * The function selects the last element as pivot element, places that pivot element correctly in the array in such a way
        * that all the elements to the left of the pivot are lesser than the pivot and
        * all the elements to the right of pivot are greater than it.
        * @Parameters: array, starting index and ending index
        * @Returns: index of pivot element after placing it correctly in sorted array
        */
        partition (arr[], low, high)
        {
            // pivot - Element at right most position
            pivot = arr[high];  
            i = (low - 1);  // Index of smaller element
            for (j = low; j <= high-1; j++)
            {
                // If current element is smaller than the pivot, swap the element with pivot
                if (arr[j] < pivot)
                {
                    i++;    // increment index of smaller element
                    swap(arr[i], arr[j]);
                }
            }
            swap(arr[i + 1], arr[high]);
            return (i + 1);
        }';
        $formAction = '/quicksortpost';
        //$title = Info::where('attribute_name', '=', 'Site_title')->first()->attribute_value;
        return view('functions')->withTitle('Quick sort algorithm')
                                ->withDescription($description)->withPseudoCode($pseudoCode)->withFormAction($formAction);
    }

    private function quickSortPartitioned($data)
    {
        function partition(&$arr,$leftIndex,$rightIndex)
        {
            $pivot=$arr[($leftIndex+$rightIndex)/2];

            while ($leftIndex <= $rightIndex) 
            {        
                while ($arr[$leftIndex] < $pivot)             
                        $leftIndex++;
                while ($arr[$rightIndex] > $pivot)
                        $rightIndex--;
                if ($leftIndex <= $rightIndex) {  
                        $tmp = $arr[$leftIndex];
                        $arr[$leftIndex] = $arr[$rightIndex];
                        $arr[$rightIndex] = $tmp;
                        $leftIndex++;
                        $rightIndex--;
                }
            }
            return $leftIndex;
        }

        function quickSort(&$arr, $leftIndex, $rightIndex)
        {
            $index = partition($arr,$leftIndex,$rightIndex);
            if ($leftIndex < $index - 1)
                quickSort($arr, $leftIndex, $index - 1);
            if ($index < $rightIndex)
                quickSort($arr, $index, $rightIndex);
        }

        quickSort($data,0,count($data)-1);

        return $data;
    }

    public function quickSortPost(Request $request)
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
        foreach($this->quickSortPartitioned($testValues) as $item){ $result[1] .= $item.', '; };
        $result[1] = substr($result[1], 0, -2);
        $result[1] .= "]";

        $time_end = microtime(true);

        $result[2] = $execution_time = ($time_end - $time_start);
        return json_encode($result);
    }
}
