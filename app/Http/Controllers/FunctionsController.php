<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class FunctionsController extends Controller
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

    public function heapSortPage()
    {
        $description = 'El ordenamiento por montículos (heapsort en inglés) es un algoritmo de ordenamiento no recursivo, no estable, con complejidad computacional (n log n).

        Este algoritmo consiste en almacenar todos los elementos del vector a ordenar en un montículo (heap), y luego extraer el nodo que queda como nodo raíz del montículo (cima) en sucesivas iteraciones obteniendo el conjunto ordenado. Basa su funcionamiento en una propiedad de los montículos, por la cual, la cima contiene siempre el menor elemento (o el mayor, según se haya definido el montículo) de todos los almacenados en él. El algoritmo, después de cada extracción, recoloca en el nodo raíz o cima, la última hoja por la derecha del último nivel. Lo cual destruye la propiedad heap del árbol. Pero, a continuación realiza un proceso de "descenso" del número insertado de forma que se elige a cada movimiento el mayor de sus dos hijos, con el que se intercambia. Este intercambio, realizado sucesivamente "hunde" el nodo en el árbol restaurando la propiedad montículo del árbol y dejando paso a la siguiente extracción del nodo raíz.
        
        El algoritmo, en su implementación habitual, tiene dos fases. Primero una fase de construcción de un montículo a partir del conjunto de elementos de entrada, y después, una fase de extracción sucesiva de la cima del montículo. La implementación del almacén de datos en el heap, pese a ser conceptualmente un árbol, puede realizarse en un vector de forma fácil. Cada nodo tiene dos hijos y por tanto, un nodo situado en la posición i del vector, tendrá a sus hijos en las posiciones 2 x i, y 2 x i +1 suponiendo que el primer elemento del vector tiene un índice = 1. Es decir, la cima ocupa la posición inicial del vector y sus dos hijos la posición segunda y tercera, y así, sucesivamente. Por tanto, en la fase de ordenación, el intercambio ocurre entre el primer elemento del vector (la raíz o cima del árbol, que es el mayor elemento del mismo) y el último elemento del vector que es la hoja más a la derecha en el último nivel. El árbol pierde una hoja y por tanto reduce su tamaño en un elemento. El vector definitivo y ordenado, empieza a construirse por el final y termina por el principio.';
        
        $pseudoCode = '
        function heapsort(array A[0..n]):
            montículo M
            integer i; // declaro variable i
            for i = 0..n:
                insertar_en_monticulo(M, A[i])
            for i = 0..n:
                A[i] = extraer_cima_del_monticulo(M)
            return A';
        $formAction = '/heapsortpost';
        //$title = Info::where('attribute_name', '=', 'Site_title')->first()->attribute_value;
        return view('functions')->withTitle('Heap sort algorithm')
                                ->withDescription($description)->withPseudoCode($pseudoCode)->withFormAction($formAction);
    }

    private function heapSort($data)
    {
        // function for heap sort which calls heapify function 
        // to build the max heap and then swap last element of 
        // the max-heap with the first element
        // exclude the last element from the heap and rebuild the heap
        function heap_sort(&$Array) {
            $n = sizeof($Array);
            for($i = (int)($n/2); $i >= 0; $i--) {
            heapify($Array, $n-1, $i);
            }
            
            for($i = $n - 1; $i >= 0; $i--) {
            //swap last element of the max-heap with the first element
            $temp = $Array[$i];
            $Array[$i] = $Array[0];
            $Array[0] = $temp;
        
            //exclude the last element from the heap and rebuild the heap 
            heapify($Array, $i-1, 0);
            }
        }
        
        // heapify function is used to build the max heap
        // max heap has maximum element at the root which means
        // first element of the array will be maximum in max heap
        function heapify(&$Array, $n, $i) {
            $max = $i;
            $left = 2*$i + 1;
            $right = 2*$i + 2;
        
            //if the left element is greater than root
            if($left <= $n && $Array[$left] > $Array[$max]) {
            $max = $left;
            }
        
            //if the right element is greater than root
            if($right <= $n && $Array[$right] > $Array[$max]) {
            $max = $right;
            }
        
            //if the max is not i
            if($max != $i) {
            $temp = $Array[$i];
            $Array[$i] = $Array[$max];
            $Array[$max] = $temp;
            //Recursively heapify the affected sub-tree
            heapify($Array, $n, $max); 
            }
        }

        heap_sort($data,0,count($data)-1);

        return $data;
    }

    public function heapSortPost(Request $request)
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
        foreach($this->heapSort($testValues) as $item){ $result[1] .= $item.', '; };
        $result[1] = substr($result[1], 0, -2);
        $result[1] .= "]";

        $time_end = microtime(true);

        $result[2] = $execution_time = ($time_end - $time_start);
        return json_encode($result);
    }

    public function countingSortPage()
    {
        $description = 'In computer science, counting sort is an algorithm for sorting a collection of objects according to keys that are small positive integers; that is, it is an integer sorting algorithm. It operates by counting the number of objects that possess distinct key values, and applying prefix sum on those counts to determine the positions of each key value in the output sequence. Its running time is linear in the number of items and the difference between the maximum key value and the minimum key value, so it is only suitable for direct use in situations where the variation in keys is not significantly greater than the number of items. It is often used as a subroutine in radix sort, another sorting algorithm, which can handle larger keys more efficiently.[1][2][3]

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
