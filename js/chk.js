var checador=new Class({
	Implements:Options,
	options:{
		msgs:{
			msgUser:"La cadena de texto s&oacute;lo puede:<br/>1. Tener caracteres alfanum&eacute;ricos; (incluyendo '&ntilde', espacios y acentos)<br/><br/><center>Ejemplos:<b>Neymar Da Silva</b>, <b>Lateral Derecho</b>, <b>Atlante</b>, etc",
			msgUrl:"La URL debe ser v&aacute;lida. Ejemplo: <b>http://foodomain.com</b>",
			msgMail:"El correo debe ser v&aacute;lido. Ejemplo: <b>foo@foodomain.com</b>",
			msgText:"<center>Por favor escribe un comentario. Gracias</center>",
			msgHora:"<center>Formato de hora <b>No V&aacute;lido</b><br/>Formatos aceptados:<br/><b>08:05, 8:05, 8.05 y 08.05</b></center>",
			msgYt:"No es una direcci&oacute;n de youtube v&aacute;lida. Intenta nuevamente. Ejemplo: <b>http://www.youtube.com/watch?v=xUJ_naXtYC4</b>",
			msgInt:"S&oacute;lo puedes ingresar datos num&eacute;ricos",
			msgFon:"Son 10 d&iacute;gitos para el tel&eacute;fono",
			msgDate:"El formato de la fecha debe ser: dd/mm/aaaa<center>Ejemplo: 06/15/1980</center>",
			msgDatetime:"El formato de la fecha y hora debe ser: dd/mm/aaaa hh:mm:ss<br/><center>Ejemplo: 01/08/2010 08:00:00</center>"

		},
		hl:false,url:"?",
		method:false,
		msgFin:"Todo bien",
		bg:"#FFEBE8",
		borderc:"#f00",
		colortxt:"#333",
		ie:false,
		userMin:3,
		userMax:10,
		ie:false
	},
	initialize:function(b,a)
	{
		this.aux=b;
		this.cont=document.id(this.aux);
		this.setOptions(a);
		this.Els=this.cont.getElements('.chk');
		this.hl=(!this.options.hl)?"":this.options.hl;
		this.tmpVar=true;
		this.lol=0;
		this._boton=this.cont.getElement("input[type=submit]")
	},
	
	update:function(){this.initialize(this.aux)},
	
	boton:function(){return this._boton},
	_formEvent:function(a){
		if(!$defined(a)){return}
	},
	go:function(){
		this.lol=0;
		this.tmpVar=true;
		return this.chequea()
	},
	chequea:function(){
		var a=this.Els;
		a.each(function(g,f)
		{
			var d={obj:g,ind:f};
			var b=this.hl;
			var c=this.options.msgs;
			if(d.obj.hasClass("req")&&d.obj.get("value").trim()==""){
				d.obj.addClass("chkFalse");
				d.obj.highlight(b);
				this.tmpVar=false;
				if(!this.lol){
					d.obj.focus()
				}
				this.lol=1
			}else{
				if(d.obj.value!=""){
					if(d.obj.hasClass("user")&&!this.chkdata(d.obj.get("value"),"user")){
						this.notice(d,c.msgUser,true)
					}
					if(d.obj.hasClass("url")&&!this.chkdata(d.obj.get("value"),"url")){
						this.notice(d,c.msgUrl,false)
					}
					if(d.obj.hasClass("mail")&&!this.chkdata(d.obj.get("value"),"mail")){
						this.notice(d,c.msgMail,false)
					}
					if(d.obj.hasClass("text")&&!this.chkdata(d.obj.get("value"),"text")){
						this.notice(d,c.msgText,false)
					}
					if(d.obj.hasClass("hora")&&!this.chkdata(d.obj.get("value"),"hora")){
						this.notice(d,c.msgHora,false)
					}
					
					if(d.obj.hasClass("fon")&&!this.chkdata(d.obj.get("value"),"fon")){
						this.notice(d,c.msgFon,false)
					}
					if(d.obj.hasClass("int")&&!this.chkdata(d.obj.get("value"),"int")){
						this.notice(d,c.msgInt,false)
					}
					
					if(d.obj.hasClass("fechas")&&!this.chkdata(d.obj.get("value"),"fechas")){
						this.notice(d,c.msgDate,false)
					}
					
					if(d.obj.hasClass("datetime")&&!this.chkdata(d.obj.get("value"),"datetime")){
						this.notice(d,c.msgDatetime,false)
					}
					
					if(d.obj.hasClass("yt")&&!this.chkdata(d.obj.get("value"),"yt")){
						this.notice(d,c.msgYt,false)
					}}}
		},this);
		this.keyEvents();
		return this.tmpVar
	},
	keyEvents:function(){
		var a=this.Els;
		a.each(function(d,c)
		{
			var b=d.get("rel");
			d.addEvent("keydown",function(e)
			{
				if(e.key!=="backspace"&&e.key!=="delete"&&e.key!=="tab"&&e.key!=="enter")
				{
					if(d.hasClass("chkFalse"))
					{
						d.removeClass("chkFalse")
					}
					if($defined(b=document.id(b)))
					{
						this.destroyError(b)
					}
				}
			}.bind(this))
		},this)
	},
	destroyError:function(a){
		if(Browser.Engine.trident&&this.options.ie)
		{
			a.destroy()
		}
		else
		{
			a.set("morph",{transition:"sine:out",duration:200}).get("morph").start(
			{
				"font-size":"0px",
				height:0,
				width:0,
				margin:0,
				padding:0,
				opacity:0
			}).chain(function(){a.destroy()})
		}
	},
	notice:function(d,c,e){
		var b="error"+d.ind;
		c=(e?c+'<center style="margin-top:10px">Longitud de caracteres<br/>Min:<b>'+this.options.userMin+'</b> Max:<b>'+this.options.userMax+'</b></center>':c)
		if(!this.lol)
		{
			this.lol=1;
			this.tmpVar=false;
			d.obj.select()
		}
		if($defined(document.id(b))){return}
		var a=new Element("div",{styles:
		{
			background:this.options.bg,
			border:"solid 1px "+this.options.borderc,
			padding:"0px",
			opacity:0,
			height:0,
			color:"#FFF2CF",
			display:"block",
			"font-size":"11px",
			margin:"3px 9px 2px 3px"
		},
		html:c,
		"class":"warn",
		id:b
		});
		a.inject(d.obj,"before");
		if(Browser.Engine.trident&&this.options.ie)
		{
			a.setStyles(
			{
				height:a.getPosition.bottom,
				padding:10,
				opacity:1,
				color:this.options.colortxt
			})
		}
		else
		{
			a.set("morph",
			{
				duration:500,
				transition:"elastic:out"
			}).morph(
			{
				height:a.getPosition.bottom,
				padding:10,opacity:1
			})
			.set("tween",{duration:500})
			.tween("color",this.options.colortxt)
		}
		d.obj.set("rel",a.id)
	},
	chkdata:function(str,what){

		if(what=='url')
			return /^(http:\/\/)(www\.)?([0-9a-zA-Z][_-]?)+([0-9a-zA-Z])([.][\w]{2,3})?[.][\w]{2,3}$/i.test(str);
		else if(what=='mail')
			return /^([a-zA-Z0-9]+[\.|\w])+[a-zA-Z\d]@[a-zA-Z\d-]+([\.][\w]+)?[\.][a-zA-Z\d]{2,4}([\.][a-zA-Z]{2})?$/i.test(str)
		else if(what=='user')
		{

			if(	str.length >= this.options.userMin && str.length <= this.options.userMax )
				return /^[^_âêîôû\^][\w\d\s\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF][^_âêîôû\^]+$/i.test(str);
			else
				return false


		}
		else if(what=='hora')
			return /^([0-1][0-9]|[0-2][0-3]|[0-9])[\:|\.][0-5][0-9]([\:|\.]([0-5][0-9]|[0-59]))?$/i.test(str);
		else if(what=='yt')
			return /^(http\:\/\/)?(www\.)?youtube\.com\/watch\?v\=[\w\-]{11}/i.test(str);
		else if(what=='int')
			return /^[0-9]*$/.test(str);
		else if(what=='fon')
			return /^[0-9]{10,10}$/.test(str);
		else if(what=='fechas')
			return /^(0[1-9]|1[012])[\/|-]([123]0|[012][1-9]|31)[\/|-](19[0-9]{2}|2[0-9]{3})$/.test(str);
		else if(what=='datetime')
			return /^[\d]{2}\/[\d]{2}\/[\d]{4} [\d]{2}:[\d]{2}(:[\d]{2})?$/.test(str);
		
			
		return false;
	}
})
