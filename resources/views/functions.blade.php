@extends('layouts.main')
@section('title', $title)
@section('content')
    <div class="container">
        <h3>{{ $title }}</h3>
        <p>{!! nl2br(e($description)) !!}</p>
        <p class="p-2 border border-secondary font-monospace bg-dark text-light">{!! nl2br(str_replace("    ", '&emsp;', e($pseudoCode))) !!}</p>
        <form id="varForm" onchange="handleChange()" class="border rounded p-4 mb-3" onsubmit="submitForm()">
            <div id="inputsContainer" class="mb-3">
                <label for="values_input" class="form-label">Valores</label>
                <input id="values_input" class="form-control" name="var[]" type="text" autocomplete="off" />
                <div class="form-text">Separados por coma</div>
            </div>
            <div class="mb-3">
                <label for="search_value" class="form-label">Valor de busqueda</label>
                <input class="form-control" id="search_value" name="search_value" type="text" />
            </div>
            <div class="mb-3">
                <label for="test_type" class="form-label">Tipo de valores a ensayar</label>
                <select class="form-control" id="test_type" name="test_type">
                    <option value="text">Texto</option>
                    <option value="numeric" selected>Números</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="test_qty" class="form-label">Cantidad de valores para test automático</label>
                <input class="form-control" id="test_qty" name="test_qty" type="number" step="1" value="100" />
            </div>
            <button type="button" id="test_btn" class="btn btn-secondary me-2 collapse" onclick="submitForm(1)">Test</button>
            <button type="submit" id="calc_btn" class="btn btn-primary collapse">Calcular</button>
        </form>
        <div class="border rounded p-4 collapse" id="result">
            <div class="w-100 text-center opacity-75" id="resultLoading">
                <div class="lds-ring">
                    <div></div>
                    <div></div>
                    <div></div>
                    <div></div>
                </div>
            </div>
            <button class="btn btn-sm btn-link text-decoration-none py-2 px-0" onclick="$('#inputDataContainer').show();$(this).hide();">Ver valores de entrada</button>
            <div class="collapse" id="inputDataContainer">
                <h4>Valores de entrada:</h4>
                <div class="mb-4" id="inputData"></div>
            </div>
            <h3>Resultado:</h3>
            <div class="mb-4" id="resultData"></div>
            <div id="resultExecutionTime"></div>
        </div>
    </div>
@endsection
@section('scripts')
    <script type="text/javascript">

        let varList = [];
        let testType = 'numeric';
        let testQty = 0;
        let searchValue = 0;
        $(document).ready(() => {
            $('#resultLoading').hide();
            $('#test_btn').show();
            $('#calc_btn').show();
            handleChange();
        });

        const handleChange = () => {

            let values = document.getElementById("values_input").value.replace(/\s/g, '').split(",");
            values.forEach((e, index) => {if (e === '') values.splice(index, 1);});
            console.log(values);
            varList = values;
            testType = $("#test_type")[0].value;
            testQty = $("#test_qty")[0].value;
            searchValue = $("#search_value")[0].value;
        }

        const submitForm = (test = 0) => {
            event.preventDefault();

            const showLoader = () => {$('#resultLoading').show()};
            const hideLoader = () => {$('#resultLoading').hide()};
            const showResultContainer = () => {$('#result').show()};
            const scrollToResult = () => {
                $('html, body').animate({
                    scrollTop: $("#result").offset().top
                }, 100);
            }

            $('#resultData').html('');
            showResultContainer();
            showLoader();
            scrollToResult();
            
            $.post( "{{ $formAction }}", {_token: $("meta[name='csrf-token']").attr("content"), varList: varList, test: test, testType: testType, testQty: testQty, searchValue: searchValue }, function(data) {  

                let result = JSON.parse(data);
                let executionTime = Math.round(parseFloat(result[2])*1000)/1000
                $('#inputData').html( '<span class="bezier-animated">' + result[0] + '</span>' );
                $('#resultData').html( '<span class="bezier-animated">' + result[1] + '</span>' );
                $('#resultExecutionTime').html( "<b>Tiempo de ejecución:</b> " + executionTime + 'sg');
                scrollToResult();
                hideLoader();

            })
            .fail(function() {
                alert( "Error al ejecutar la función" );
            })
        
        }

    </script>
@endsection