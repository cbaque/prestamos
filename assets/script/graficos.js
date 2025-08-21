/*Author: Ing. Ruben D. Chirinos R. Tlf: +58 0416-3422924, email: elsaiya@gmail.com*/

/*tipos de graficos
    bar
    horizontalBar
    line
    radar
    polarArea
    pie
    doughnut
    bubble
 Con pointRadius podrás establecer el radio del punto.

fill: false, –> no aparecerá relleno por debajo de la línea.

showLine: false, –> no aparecerá la línea.

Es decir, si ponemos fill y showLine a false, tendremos un gráfico de puntos, en lugar de un gráfico
de líneas. pointStyle: ‘circle’, ‘triangle’, ‘rect’, ‘rectRounded’, ‘rectRot’, ‘cross’, ‘crossRot’, ‘star’,
‘line’, and ‘dash’ Podría ser incluso una imagen.

spanGaps está por defecto a false. Si lo ponemos a true, cuando te falte un valor en la línea, no se 
romperá la línea.*/

function cambiarNombre(nombre){
let regex = /^[a-zA-ZÀ-ÿ\u00f1\u00d1]+(\s*[a-zA-ZÀ-ÿ\u00f1\u00d1]*)*[a-zA-ZÀ-ÿ\u00f1\u00d1]+$/g;
return regex.exec(nombre)[0];

}

function decodificarEntidadesHTMLNumericas(texto) {
    return texto.replace(/&#(\d{1,8});/g,function (m, ascii) {
        return String.fromCharCode(ascii);
    });
}


/*############ GRAFICO DE VENTAS POR USUARIOS #############*/
function showGraphDoughnut_2()
{
    {
        $.post("data.php?PrestamosxUsuarios=si",
        function (data)
        {
            console.log(data);
            var id = [];
            var name = [];
            var marks = [];
            var myColors=[];

            for (var i in data) {
                id.push(data[i].codigo);
                name.push(data[i].nombres);
                marks.push(data[i].total);
            }

            $.each(id, function(index,num) {
                if (num == 0)
                    myColors[index]= "#f0ad4e";
                if (num == 2)
                    myColors[index]= "#ff7676";
                if (num == 3)
                    myColors[index]= "#E0E4CC";
                if (num == 4)
                    myColors[index]= "#3e95cd";
                if (num == 5)
                    myColors[index]= "#969788";
                if (num == 6)
                    myColors[index]= "#987DDB";
                if (num == 7)
                    myColors[index]= "#169696"; 
                if (num == 8)
                    myColors[index]= "#69D2E7";   
                if (num == 9)
                    myColors[index]= "#F38630";   
                if (num == 10)
                    myColors[index]= "#F82330";  
                if (num == 11)
                    myColors[index]= "#D3E37D";  
                if (num == 12)
                    myColors[index]= "#00FFFF";  
                if (num == 13)
                    myColors[index]= "#fff933";  
                if (num == 14)
                    myColors[index]= "#90ff33";  
                if (num == 15)
                    myColors[index]= "#E8AC9E";
            });

            var chartdata = {
                labels: name,
                datasets: [
                    {
                        label: 'Total en Prestamos',
                        data: marks,  
                        backgroundColor: ['#f0ad4e','#1b78a0','#dba236','#8EE1BC','#F38630','#fff933','#90ff33','#E8AC9E','#69D2E7','#169696','#987DDB','#E0E4CC','#25AECD'],
                        borderWidth: 1
                    }
                ]
            };

            var graphTarget = $("#DoughnutChart_2");
            //var steps = 3;

            var barGraph = new Chart(graphTarget, {
                type: 'doughnut',
                data: chartdata,
                responsive : true,
                animation: true,
                barValueSpacing : 5,
                barDatasetSpacing : 1,
                tooltipFillColor: "rgba(0,0,0,0.8)",
                multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>" 
            });
        });
    }
}
/*############ GRAFICO DE VENTAS POR USUARIOS #############*/


/*############ GRAFICO DE VENTAS POR USUARIOS #############*/
function showGraphDoughnut_3()
{
    {
        $.post("data.php?DisponiblexAperturas=si",
        function (data)
        {
            console.log(data);
            var id = [];
            var name = [];
            var efectivo = [];
            var marks = [];
            var myColors=[];

            for (var i in data) {
                id.push(data[i].nrocaja);
                name.push(data[i].nomcaja);
                efectivo.push(data[i].total_efectivo);
                marks.push(data[i].total_general);
            }

            $.each(id, function(index,num) {
                if (num == 0)
                    myColors[index]= "#f0ad4e";
                if (num == 2)
                    myColors[index]= "#ff7676";
                if (num == 3)
                    myColors[index]= "#E0E4CC";
                if (num == 4)
                    myColors[index]= "#3e95cd";
                if (num == 5)
                    myColors[index]= "#969788";
                if (num == 6)
                    myColors[index]= "#987DDB";
                if (num == 7)
                    myColors[index]= "#169696"; 
                if (num == 8)
                    myColors[index]= "#69D2E7";   
                if (num == 9)
                    myColors[index]= "#F38630";   
                if (num == 10)
                    myColors[index]= "#F82330";  
                if (num == 11)
                    myColors[index]= "#D3E37D";  
                if (num == 12)
                    myColors[index]= "#00FFFF";  
                if (num == 13)
                    myColors[index]= "#fff933";  
                if (num == 14)
                    myColors[index]= "#90ff33";  
                if (num == 15)
                    myColors[index]= "#E8AC9E";
            });

            var chartdata = {
                labels: name,
                datasets: [
                    {
                        label: 'Total en Prestamos',
                        data: marks,  
                        backgroundColor: ['#f0ad4e','#1b78a0','#dba236','#8EE1BC','#F38630','#fff933','#90ff33','#E8AC9E','#69D2E7','#169696','#987DDB','#E0E4CC','#25AECD'],
                        borderWidth: 1
                    }
                ]
            };

            var graphTarget = $("#DoughnutChart_3");
            //var steps = 3;

            var barGraph = new Chart(graphTarget, {
                type: 'doughnut',
                data: chartdata,
                responsive : true,
                animation: true,
                barValueSpacing : 5,
                barDatasetSpacing : 1,
                tooltipFillColor: "rgba(0,0,0,0.8)",
                multiTooltipTemplate: "<%= datasetLabel %> - <%= value %>" 
            });
        });
    }
}
/*############ GRAFICO DE VENTAS POR USUARIOS #############*/