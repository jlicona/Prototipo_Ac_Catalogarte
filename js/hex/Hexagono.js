/*
	Dependencias: 
		- SnapSvg
		- Jquery
*/
function Hexagono (parametros){
	this.id 			= parametros.id || 1;
	this.el 			= null; // Se asigna en init()
	this.preview 		= null;
	this.thumbnail 		= null;
	this.pattern 		= null;
	this.verFicha 		= null;
	this.descargar 		= null;
	this.poligono 		= null; 
	

	//Textos
	this.inicio 		= null;
	this.fin 			= null;
	this.sede 			= null;
	this.disciplina 	= null;
	this.nombre 		= null;
	this.descripcion 	= null;

	this.snap 			= parametros.snap || Snap(800,600);
	this.selector 		= parametros.selector || "hex1";
	this.colorFondo 	= parametros.colorFondo ||"#BADA22";
	this.colorBorde 	= parametros.colorBorde || "#000";
	this.anchoBorde		= parametros.anchoBorde || "2";
	this.lado			= parametros.lado || 100; // Se calcula en init()
	this.apotema 		= 0.00; // Se calcula en init()
	this.coordenadas	= parametros.coordenadas || { x:0, y:0 };
	this.icono 			= parametros.icono || "ExTAA";
	this.alfa 			= parametros.alfa || 0.5;


	this.data 			= parametros.data || { thumbnail: "thumbnail.jpg", portada: "portada.jpg", sede: "MUSEO", disciplina: "PINTURA / ESCULTURA", nombre : "El futuro del Diseño: Viviendo un Feliz Diseño", descripcion:"Una colección de artístas del s.XIX no puede estar completa sin la perspectiva de lo(...)", inicio: "01/FEB/2014", fin: "11/MAY/2014" }


	this.open 			= false;  


	this.init = function(){
		//Calculamos el apotema
		this.apotema = Math.sqrt((this.lado * this.lado) - ((this.lado/2) * (this.lado/2)));	

		var coordenadas = this.coordenadas;
		var apotema = this.apotema;
		var lado = this.lado;
	
		
		// Centramos el origen en relacion con el apotema para el eje X
		var cx  = this.coordenadas.x - this.apotema;
		// Centramos el origen en relacion con el lado para el eje y;
		var cy = this.coordenadas.y - this.lado;		

		// Calculamos la ruta del poligono en forma de hexágono
		var points = "";
		points += (cx + this.apotema)+ " " + cy + ",";
		points += (cx + (this.apotema*2)) + " " +  (cy + (this.lado/2)) + ",";
		points += (cx + (this.apotema*2)) + " " + (cy + (this.lado * 1.5)) + ",";
		points += (cx + this.apotema) + " " + (cy + (this.lado*2)) + ",";
		points += cx + " " + (cy + (this.lado * 1.5)) +",";
		points += cx + " " + (cy + (this.lado/2));	

		var r = this.snap.rect(cx,cy,this.apotema * 2,this.lado * 2).attr({ fill:this.colorFondo, opacity: this.alfa });
		this.pattern = r.toPattern(cx,cy,this.apotema * 2,this.lado * 2).attr({ patternUnits:"userSpaceOnUse"});

		var icono = this.snap.text(this.coordenadas.x, this.coordenadas.y+6, this.icono);
		icono.attr({class: "hexagono-icono"});
		icono.appendTo(this.pattern);

		// Thumbnail
		this.thumbnail = this.snap.image(this.data.thumbnail, cx, cy, this.apotema * 2,this.lado * 2);	
		this.thumbnail.attr({ opacity: 0 });
		this.thumbnail.appendTo(this.pattern);


		var portada =   this.snap.image(this.data.portada, cx, cy, this.apotema * 2, this.lado);	
		var detalles = this.snap.rect(cx, this.coordenadas.y  ,this.apotema * 2,this.lado ).attr({ fill:"#000" });

		// Dibujamos triangulo izquierdo del preview
		var pointsTL = "";
		pointsTL +=  cx + " " + (cy + (this.lado/2)) + ",";
		pointsTL +=  cx + " " + (cy + (this.lado * 1.5)) + ",";
		pointsTL += this.coordenadas.x + " " + this.coordenadas.y;
		var trianguloL = this.snap.polygon(pointsTL).attr({fill: this.colorFondo });

		// Dibujamos triangulo derecho del preview
		var pointsTR = "";
		pointsTR +=  (cx + (this.apotema*2)) + " " + (cy + (this.lado/2)) + ",";
		pointsTR +=  (cx + (this.apotema*2)) + " " + (cy + (this.lado * 1.5)) + ",";
		pointsTR += this.coordenadas.x + " " + this.coordenadas.y;
		var trianguloR = this.snap.polygon(pointsTR).attr({fill: this.colorFondo });

		
		//Creamos preview

		this.preview = this.snap.group(portada, detalles, trianguloL, trianguloR ).attr({opacity:0});		
		this.preview.appendTo(this.pattern);

		/*====== Textos del preview ======*/
		var x = this.coordenadas.x ;
		var y = this.coordenadas.y;
		var arraySnapText = [];
		var yText = y ;	


		var tmpSede = this.data.sede;
		if(tmpSede.length>50){
			tmpSede = tmpSede.substring(0,50) + "(...)";
		}

		arraySnapText = cadenaAMultilinea(tmpSede, 15);

		if(arraySnapText.length>1){
			yText = y + 15 ;	
		}else{
			yText = y + 18 ;	
		}			

		this.sede = this.snap.text(x,y-8,arraySnapText)
								.attr({fill:"#fff", fontSize:"6px",  "text-anchor": "middle",   "font-weight": 500, opacity: 0, class: "uppercase"});
		this.sede.selectAll("tspan").forEach(function(tspan, j){											
									      tspan.attr({x:x,  y:yText + 6 *(j+1)});
								   });
		var sede = this.sede;


		var tmpDisciplina = this.data.disciplina;
		if(tmpDisciplina.length>50){
			tmpDisciplina = tmpDisciplina.substring(0,50) + "(...)";
		}

		arraySnapText = cadenaAMultilinea(tmpDisciplina, 15);

		if(arraySnapText.length>1){
			yText = y + 29 ;	
		}else{
			yText = y + 30.5 ;	
		}			

		this.disciplina = this.snap.text(x,y-8,arraySnapText)
								.attr({fill:"#fff", fontSize:"3px",  "text-anchor": "middle",   "font-weight": 500, opacity: 0, class: "uppercase"});
		this.disciplina.selectAll("tspan").forEach(function(tspan, j){											
									      tspan.attr({x:x,  y:yText + 3 *(j+1)});
								   });
		var disciplina = this.disciplina;
			

		this.inicio = this.snap.text(x,y+10,this.data.inicio)
								.attr({fill:"#FFF", fontSize:"3px",  "text-anchor": "middle",   "font-weight": 300, opacity: 0, class: "uppercase"});
		this.fin = this.snap.text(x,y+14,this.data.fin)
							.attr({fill:"#FFF", fontSize:"3px",  "text-anchor": "middle",   "font-weight": 300, opacity: 0, class: "uppercase"});
	
		var inicio = this.inicio;
		var fin = this.fin;	


		var tmpNombre = this.data.nombre;
		if(tmpNombre.length>50){
			tmpNombre = tmpNombre.substring(0,50) + "(...)";
		}

		arraySnapText = cadenaAMultilinea(tmpNombre, 16);
		yText = y - 7 ;		

		this.nombre = this.snap.text(x-apotema+1.5,y-8,arraySnapText)
								.attr({fill:"#fff", fontSize:"3.5",  "text-anchor": "start",   "font-weight": 300, opacity: 0});
		this.nombre.selectAll("tspan").forEach(function(tspan, j){											
									      tspan.attr({x:x-apotema+1.5,  y:yText + 4 *(j+1)});
								   });
		var nombre = this.nombre;

		var tmpDescripcion = this.data.descripcion;
		if(tmpDescripcion.length>70){
			tmpDescripcion = tmpDescripcion.substring(0,70) + "(...)";
		}
		arraySnapText = cadenaAMultilinea(tmpDescripcion, 22);
		yText = y - 9 ;	
		this.descripcion = this.snap.text(x+1.5,y-7,arraySnapText)
								.attr({fill:"#fff", fontSize:"2.7",  "text-anchor": "end",   "font-weight": 300, opacity: 0});
		this.descripcion.selectAll("tspan").forEach(function(tspan, j){											
									      tspan.attr({x:x+apotema -1.5,  y:yText + 4 *(j+1)});
								   });
		var descripcion = this.descripcion;


		/*====== Botones del preview ======*/
		
		var contexto = this;
		this.verFicha = this.snap.group().attr({ class: "btn-ver-ficha", id: "btn-ver-ficha-"+this.selector});
		this.descargar = this.snap.group().attr({ class: "btn-descargar", id: "btn-descargar-"+this.selector});

		// Ver Ficha
		Snap.load("../../img/iconos/verFicha.svg",function(f){
			var path = f.select("path");
			path.attr({fill:"#FFF"})
			path.appendTo(contexto.verFicha);
		});
		var t = new Snap.Matrix(0.0028,0,0,0.0028, coordenadas.x - 9, coordenadas.y + lado - 13);
		this.verFicha.transform(t);
		this.verFicha.attr({ opacity: 0})	;
		var verFicha = this.verFicha;

		// Descargar
		Snap.load("../../img/iconos/descargar.svg",function(f){
			var path = f.select("path");
			path.attr({fill:"#FFF"})
			path.appendTo(contexto.descargar);
		});
		var t = new Snap.Matrix(0.0028,0,0,0.0028, coordenadas.x + 4, coordenadas.y + lado - 13);
		this.descargar.transform(t);	
		this.descargar.attr({ opacity: 0})	;
		var descargar = this.descargar;
		/*===============================*/

		// Dibujamos el hexagono y lo rellenamos con el patron
		this.poligono = this.snap.polygon(points)
		var strokeDash = '';
		if(this.data.estado == '2'){
			strokeDash = '3,5';
		}
		this.poligono.attr({id:"hex-"+this.selector, stroke:this.colorBorde, strokeWidth:this.anchoBorde, 'stroke-dasharray':strokeDash});	
		
		var poligono = this.poligono;


		

		// Creamos el grupo de preview
		this.el = this.snap.group(poligono,verFicha, descargar, sede, disciplina, inicio, fin, nombre, descripcion);
		this.el.attr({fill: this.pattern, "fill-opacity":1, id:this.selector,  class:"hexagono"})
		



		// Configuramos los eventos
	
		$("body").on('mouseenter',"#"+this.selector, function(){ contexto.animateMouseOver()} );
		$("body").on('mouseleave',"#"+this.selector, function(){ contexto.animateMouseOut()} );
		$("body").on('click',"#"+this.selector, function(){ contexto.animateClick()} );

		$("#btn-ver-ficha-"+this.selector).on('click', function(){  window.open ("../../index.php/catalogo/ver/" + contexto.id, '_blank')  } );

		$("#btn-descargar-"+this.selector).on('click', function(){  alert("Descargar el Catálogo en Formato PDF (aun no funcional) de la Exposición: "+ contexto.id ) }  );

		return this;
	}
	this.animateMouseOver = function(){		
		// Traemos al frente
		this.el.appendTo(this.snap);
		var myMatrix = new Snap.Matrix();
		myMatrix.scale(1.5,1.5,this.coordenadas.x,this.coordenadas.y);	
		this.el.stop();
		this.el.animate({
			transform:myMatrix	
		},200, mina.easeout);	
		this.poligono.animate({strokeWidth: this.anchoBorde/1.5},200, mina.easeout);

		this.thumbnail.stop();
		this.thumbnail.animate({
			opacity:1	
		},200, mina.linear);			
	},
	this.animateMouseOut = function(){

		var myMatrix = new Snap.Matrix();
		myMatrix.scale(1,1,this.coordenadas.x,this.coordenadas.y);	
		this.el.stop();
		this.poligono.animate({strokeWidth: this.anchoBorde},200, mina.easeout);
		this.el.animate({
			transform: myMatrix
		},150, mina.easeout);
		
		this.thumbnail.stop();
		this.thumbnail.animate({
			opacity:0	
		},150, mina.linear);	

		this.preview.stop();
		this.preview.animate({
			opacity: 0
		},150, mina.easeout);	

		this.verFicha.stop();
		this.verFicha.animate({
			opacity: 0
		},50, mina.easeout);

		this.descargar.stop();
		this.descargar.animate({
			opacity: 0
		},50, mina.easeout);	
		
		this.sede.stop();
		this.sede.animate({
			opacity: 0
		},50, mina.easeout);

		this.disciplina.stop();
		this.disciplina.animate({
			opacity: 0
		},50, mina.easeout);

		this.inicio.stop();
		this.inicio.animate({
			opacity: 0
		},50, mina.easeout);	
		
		this.fin.stop();
		this.fin.animate({
			opacity: 0
		},50, mina.easeout);	

		this.nombre.stop();
		this.nombre.animate({
			opacity: 0
		},50, mina.easeout);	

		this.descripcion.stop();
		this.descripcion.animate({
			opacity: 0
		},50, mina.easeout);	
	}
	this.animateClick = function(){
		var contexto = this;
		var myMatrix = new Snap.Matrix();
		myMatrix.scale(3.23,3.23,this.coordenadas.x,this.coordenadas.y);	
	
		this.el.stop();
		this.poligono.animate({strokeWidth: this.anchoBorde/3.23},200, mina.easeout);
		this.el.animate({
			transform:myMatrix	
		},200, mina.easeout);

		this.preview.stop();
		this.preview.animate({
			opacity: 1
		},200, mina.easeout);	

		this.verFicha.stop();
		this.verFicha.animate({
			opacity: 1
		},200, mina.easeout);

		this.descargar.stop();
		this.descargar.animate({
			opacity: 1
		},200, mina.easeout);	

		this.sede.stop();
		this.sede.animate({
			opacity: 1
		},300, mina.easeout);

		this.disciplina.stop();
		this.disciplina.animate({
			opacity: 1
		},300, mina.easeout);	

		this.inicio.stop();
		this.inicio.animate({
			opacity: 1
		},300, mina.easeout);	

		this.fin.stop();
		this.fin.animate({
			opacity: 1
		},300, mina.easeout);

		this.nombre.stop();
		this.nombre.animate({
			opacity: 1
		},300, mina.easeout);

		this.descripcion.stop();
		this.descripcion.animate({
			opacity: 1
		},300, mina.easeout);
		
	}

	this.reset = function(){
		this.el.remove();
		this.preview.remove();
		this.thumbnail.remove();
		this.pattern.remove();
		this.verFicha.remove();
		this.descargar.remove();
		this.poligono.remove();
		

		//Textos
		this.inicio.remove();
		this.fin.remove();
		this.sede.remove();
		this.disciplina.remove();
		this.nombre.remove();
		this.descripcion.remove();
	}
}

function cadenaAMultilinea(texto, limite ){

	var arrayTexto = texto.split(" ");
	var linea = "";
	var lineaAux = "";
	var arrayReturnText = [];
	var limiteLinea = limite;

	for(i = 0; i< arrayTexto.length; i++){
		
		lineaAux = linea;
		if(i>0){
			linea += " ";
		}
		linea += arrayTexto[i];
		if(linea.length > limiteLinea){
			linea =  arrayTexto[i];
			if(lineaAux!=""){
				arrayReturnText.push(lineaAux);
			}
			
			if(i == arrayTexto.length-1){
				if(linea!=""){
					arrayReturnText.push(linea);
				}
			}

		}else{
			if(i == arrayTexto.length-1){
				if(linea!=""){
					arrayReturnText.push(linea);
				}
			}
		}
	}
	return arrayReturnText;
}