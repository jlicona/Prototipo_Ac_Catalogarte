/*
* == TIPOS DE ORDENAMIENTO ==
* - disciplina
* - condicion
* - sede
* - año
*/

var snap = Snap("#svgs");

// Obtenemos listas de orden a partir del grupo de datos
var listaDisciplinas = [];
var listaCondiciones = [];
var listaSedes = [];
var listaAnios = [];

var contadorHexDisciplinas = [];
var contadorHexCondiciones = [];
var contadorHexSedes = [];
var contadorHexAnios = [];

var datosOrdenados = [];
var matrizHexagonos = [];
var matrizObjHexagonos = [];
var matrizTexto = [];

var limiteColHexagonos = 0;
var limiteColHexagonosNones = 9;	
var limiteColHexagonosPares = limiteColHexagonosNones - 1;
var lado = 51;
var distanciaEntreHexagonosX = 95;
var distanciaEntreHexagonosY = 85;
var origenEjeXNones = 260;
var origenEjeXPares = origenEjeXNones + 48; // diferencia de 48 pixels
var origenEjeY 		= 175;


function cargarListas(){
	for(i in datos){
		listaDisciplinas[datos[i].idDisciplina] = datos[i].disciplina;
		listaCondiciones[datos[i].idCondicion] = datos[i].condicion;
		listaSedes[datos[i].idSede] = datos[i].sede;
		// Por alguna razón se decidió usar la palabra: "ano", en lugar de: "anio", para el valor del año O_Ou
		listaAnios[datos[i].ano] = datos[i].ano;

		//Seteamos contadores para saber el numero de filas por tipo de orden
		if(typeof contadorHexDisciplinas[datos[i].idDisciplina] == "undefined"){
			contadorHexDisciplinas[datos[i].idDisciplina] = 1;
		}else{
			contadorHexDisciplinas[datos[i].idDisciplina]++;
		} 
		if(typeof contadorHexCondiciones[datos[i].idCondicion] == "undefined"){
			contadorHexCondiciones[datos[i].idCondicion] = 1;
		}else{
			contadorHexCondiciones[datos[i].idCondicion]++;
		} 
		if(typeof contadorHexSedes[datos[i].idSede] == "undefined"){
			contadorHexSedes[datos[i].idSede] = 1;
		}else{
			contadorHexSedes[datos[i].idSede]++;
		} 
		if(typeof contadorHexAnios[datos[i].ano] == "undefined"){
			contadorHexAnios[datos[i].ano] = 1;
		}else{
			contadorHexAnios[datos[i].ano]++;
		} 
	}
}

function ordenar(ordenarPor){
	datosOrdenados = [];
	matrizHexagonos = [];
	matrizTexto = [];

	for(i in datos){			

		// Aqui deberia haber un switch dependiendo el tipo de ordenamiento, pero vamos a suponer el default
		switch(ordenarPor){
			case "disciplina":  
							if(typeof datosOrdenados[datos[i].idDisciplina] == "undefined"){
								datosOrdenados[datos[i].idDisciplina] = [];
							}
							datosOrdenados[datos[i].idDisciplina].push(datos[i]);
							
							break;
			case "condicion":  
							if(typeof datosOrdenados[datos[i].idCondicion] == "undefined"){
								datosOrdenados[datos[i].idCondicion] = [];
							}
							datosOrdenados[datos[i].idCondicion].push(datos[i]);
							break;
			case "sede":  
							if(typeof datosOrdenados[datos[i].idSede] == "undefined"){
								datosOrdenados[datos[i].idSede] = [];
							}
							datosOrdenados[datos[i].idSede].push(datos[i]);
							break;
			case "año":  
							if(typeof datosOrdenados[datos[i].ano] == "undefined"){
								datosOrdenados[datos[i].ano] = [];
							}
							datosOrdenados[datos[i].ano].push(datos[i]);
							break;
		}
		
	}
	var indiceRow 		= 1;
	var indiceCol 		= 1;
	var indiceTitulosOrden = 0;

	var tmpRowsPares = 0;
	var tmpRowsNones = 0;
	var tmpDiffRows = 0;
	for(i in datosOrdenados){		
		// Verificamos el tipo de orden para obtener el numero de filas
		var rows = 0;
		var tmpDatos = datosOrdenados[i];
		var tmpIndiceRow = 0;
		var tmpTexto = [];
		indiceCol = 0;

		var contadorHex;

		switch(ordenarPor){
			case "disciplina":  
							contadorHex = contadorHexDisciplinas[i];
							tmpTexto = listaDisciplinas[i];
							
							break;
			case "condicion":  
							contadorHex = contadorHexCondiciones[i];
							tmpTexto = listaCondiciones[i];
							break;
			case "sede":  
							contadorHex = contadorHexSedes[i];
							tmpTexto = listaSedes[i];
							break;
			case "año":  
							contadorHex = contadorHexAnios[i];
							tmpTexto = listaAnios[i];
							break;
		}

		if(limiteColHexagonosNones <= 1){
			rows = contadorHex;
		}else{
			var tmpLimiteA = 0;
			var tmpLimiteB = 0;

			if(indiceRow%2 == 0){
				//sacamos rows en base a un calculo por pares			
				tmpLimiteA = limiteColHexagonosPares;
				tmpLimiteB = limiteColHexagonosNones;		

			}else{
				//sacamos rows en base a un calculo por nones
				tmpLimiteA = limiteColHexagonosNones;
				tmpLimiteB = limiteColHexagonosPares;	
			}
			var odd = false;
			var indiceOdd = 0;
			for(n = 0; n< contadorHex; n++){					
				if(odd == false){
					if(indiceOdd==tmpLimiteA-1){
						odd = true;
						indiceOdd = 0;
						rows++;
					}else{
						if(n + 1 == contadorHex){
							rows++;
						}
						indiceOdd++;
					}
				}else{
					if(indiceOdd==tmpLimiteB-1){
						odd = false;
						indiceOdd = 0;
						rows++;
					}else{
						if(n + 1 == contadorHex){		
							rows++;
						}
						indiceOdd++;
					}						
				}								
			}	
		}

		var textLineY = origenEjeY + ((indiceRow - 1) * distanciaEntreHexagonosY) + (distanciaEntreHexagonosY * (rows - 1 ) /2) ;
		var textLineX = 0;
	
		if( rows % 2 == 0){
			textLineX = origenEjeXNones;
		}
		else{			
			if((Math.ceil( indiceRow + rows /2 ) - 1 ) % 2 == 0){
				textLineX = origenEjeXPares;
			}else{
				textLineX = origenEjeXNones;
			}
		}
		
		textLineX -= lado + 8 ;
		
		//Dibujamos clasificacion
		matrizTexto.push({texto: cadenaAMultilinea(tmpTexto, 15) , x: textLineX, y: textLineY});

		var tmpContador = 0;
		for(j in tmpDatos){
			var _x = origenEjeXNones;
			if(indiceRow % 2 == 0){
				_x = origenEjeXPares;
			}

			matrizHexagonos.push( 
				{	
					id: tmpDatos[j].id, 
					snap: snap, 
					selector: "hex" + tmpDatos[j].id,
					lado: lado, 
					coordenadas: { x: _x + ( indiceCol * distanciaEntreHexagonosX ), y: origenEjeY + ((indiceRow - 1) * distanciaEntreHexagonosY) },
					colorFondo: tmpDatos[j].color,
					colorBorde: tmpDatos[j].borde ,
					icono: tmpDatos[j].icono,
					alfa: tmpDatos[j].alfa,
					data: {
						thumbnail: "../../"+tmpDatos[j].carpeta_multimedios+tmpDatos[j].archivo_portada.nombre,						
						portada: "../../"+tmpDatos[j].carpeta_multimedios+tmpDatos[j].archivo_portada.nombre,
						sede: tmpDatos[j].sede,
						disciplina: tmpDatos[j].disciplina,
						nombre: tmpDatos[j].nombre,
						descripcion: tmpDatos[j].descripcion,
						inicio: tmpDatos[j].inicio,
						fin: tmpDatos[j].fin,
						estado: tmpDatos[j].idCondicion
					},
					obj: tmpDatos[j]

				});

			if(indiceRow%2 == 0 && limiteColHexagonosNones > 1){
				limiteColHexagonos = limiteColHexagonosPares;
			}else{
				limiteColHexagonos = limiteColHexagonosNones;
			}

			if(indiceCol + 1 == limiteColHexagonos )
			{
				indiceCol = 0;
				indiceRow++;
				tmpIndiceRow++;
				
			}else{			
				if(tmpContador == contadorHex-1){
					indiceCol = 0;
					indiceRow++;
					tmpIndiceRow++;
				}else{
					indiceCol++;	
				}					
			}
			tmpContador++;
				
		}
		if(tmpIndiceRow < rows){
			indiceRow++;
		}
		indiceTitulosOrden++;
	}
}

function switchHoneyComb(ordenarPor){
	ordenar(ordenarPor);

	var contador_animate = 0;
	for(i in matrizObjHexagonos){

		var bandera = true;
		var j = 0;
		var x = 0;
		var y = 0;

		var tmpX = 0;
		var tmpY = 0;

		while(bandera && j < matrizHexagonos.length){
			if(matrizObjHexagonos[i].id == matrizHexagonos[j].id){
				bandera = false;
				x = Math.abs(matrizHexagonos[j].coordenadas.x - matrizObjHexagonos[i].coordenadas.x);
				y = Math.abs(matrizHexagonos[j].coordenadas.y - matrizObjHexagonos[i].coordenadas.y);

				tmpX = matrizHexagonos[j].coordenadas.x;
				tmpY = matrizHexagonos[j].coordenadas.y;
			}
			j++;
		}
		
		/*var x = Math.abs(matrizHexagonos[i].coordenadas.x - matrizObjHexagonos[i].coordenadas.x);
		var y = Math.abs(matrizHexagonos[i].coordenadas.y - matrizObjHexagonos[i].coordenadas.y);*/

		/*console.log(matrizHexagonos[i].id + ":" + matrizObjHexagonos[i].id)*/

		if( tmpX < matrizObjHexagonos[i].coordenadas.x ){
			x *= -1;
		}

		if( tmpY < matrizObjHexagonos[i].coordenadas.y ){
			y *=  -1;
		}

		var myMatrix = new Snap.Matrix();
		myMatrix.translate(x,y)
		matrizObjHexagonos[i].el.animate({ transform: myMatrix},300, mina.backout, function(){
			contador_animate++;
			if(contador_animate==matrizObjHexagonos.length){
				resetSVG();	
				render();
			}
		})
	}
}

function render(){
	for(i in matrizObjHexagonos){
		matrizObjHexagonos[i].reset();
	}
	matrizObjHexagonos = [];
	for (i in matrizHexagonos){
		matrizObjHexagonos.push(new Hexagono(matrizHexagonos[i]).init());
	}

	for(i in matrizTexto){
		var snapText  = snap.text(matrizTexto[i].x, matrizTexto[i].y, matrizTexto[i].texto)
								.attr({fill:"#000", fontSize:"20px",  "text-anchor": "end",   "font-weight": 300,  class: "uppercase"});	
		snapText.selectAll("tspan").forEach(function(tspan, j){											
									      tspan.attr({x:matrizTexto[i].x,  y: matrizTexto[i].y - 10 * (matrizTexto[i].texto.length) + 20 *(j+1)});
			});
	}
}

function resetSVG(){
	$("#svgs").empty();
	snap.image("../../img/temp/GridHoneyComb.png", 0, 0, 1100,995);
}