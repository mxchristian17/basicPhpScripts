<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HeapSortController extends Controller
{
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
}
