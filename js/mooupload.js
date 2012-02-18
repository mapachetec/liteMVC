//~ Proyecto:	Moopload v1.0
//~ Autor:		kCire
//~ Descripcion:Es un script muy simple pero funcional para hacer Upload de archivos, evitando 
//~ 			recargar la pagina principal mediante el uso de un iframe
//~ Fecha:		7 de noviembre de 2010, 3:02 hrs
			 
			
var moopload = new Class
({
	Implements:Options,
	options:
	{
		el:null,
		server:null,
		onComplete:null,
		onRequest:null,
		auto:false,
		maxFiles:1,
		fileTypes:['jpg','jpeg','gif','bmp','png'],
		maxSize:(1024*1024), //1MB por default
		filePath:'images',
		browseTxt:'browse...',
		browseTxtColor:'#fff',
		loader:'spinner.gif',
		loaderTxt:'subiendo...',
		addBtn:'Otro...',
		submitValue:'Upload',
		inputBg:'#fff',
		browseBg:'#000',
		debugMode:false,
		delIcon:'del.png',
		submitStyles:{
			'padding':'5px 5px 0 21px',
			top:5,
			height:18,
			color:'#333',
			border:'1px solid #999999'
		}
	},
	initialize:function (options)
	{
		this.setOptions(options);
		
		this.el=document.id(this.options.el);
		
		if(!this.el)
			return;
		
		this.srv=this.options.server;
		this.maxFiles=this.options.maxFiles;
		this.maxSize=this.options.maxSize;
		this.filePath=this.options.filePath;
		this.fileTypes=this.options.fileTypes;
		this.loader=this.options.loader;
		this.loaderTxt=this.options.loaderTxt;
		this.addBtn=this.options.addBtn;
		this.submitValue=this.options.submitValue;
		this.browseTxt=this.options.browseTxt;
		this.browseTxtColor=this.options.browseTxtColor;
		this.inputBg=this.options.inputBg;
		this.browseBg=this.options.browseBg;
		this.submitStyles=this.options.submitStyles;
		
		this.countFiles=0;
		
		this.el.addEvent('onComplete',this.options.onComplete);
		this.el.addEvent('onRequest',this.options.onRequest);
		this.creaIframe();
		this.creaForm();
		
		if(this.maxFiles>1&&!this.options.auto)
		 	this.creaBoton();
		 
		this.creaInput();
		this.creaLoader();
			
	},
	
	creaBoton:function(){
		new Element('a',{
			styles:{
				display:'block',
				'font-size':'11px'
			},
			'class':'addBtn',
			href:'javascript:;',
			html:this.addBtn,
			events:{
				'click':function(){
					if(!document.id('filetoUp'+this.maxFiles))
						this.creaInput();
				}.bind(this)
			}
			
		}).inject('tempFormtoUpload','before');
	},
	
	chkExt:function(types,str){
		if(types.contains(str.split('.').reverse()[0].toLowerCase()))
			return true;
			
		return false
	},
	
	infoFiles:function()
	{
		var self=this;
		$$('input.file').addEvents({
			'change':function(){
				
				var value=this.value.split('\\').reverse()[0];
				++self.countFiles;
				
				if(self.options.auto&&self.chkExt(self.fileTypes,value)){
						document.id('tempFormtoUpload').fireEvent('submit');
						document.id('tempFormtoUpload').submit();
						return;
				}
				
				
				if(!self.chkExt(self.fileTypes,value)){
					value='<span style="color:red">ARCHIVO NO PERMITIDO, SE IGNORA</span>'
					this.value='';
					--self.countFiles;
				}
				
				
				if(fileInfo=window.document.getElement('span[rel="'+this.id+'"]')){	fileInfo.set('html',value);return;	}
				
				var foo = new Element('span',{
					 rel:this.id,
					 'class':'wrapFileSelected',
					 styles:{
						 'border':'solid 1px #fff',
						 'font-size':'10px',
						 width:'auto',
						 height:'20px',
						 display:'block',
						 position:'absolute',
						 left:this.getPrevious('span').getWidth()+5,
						 top:5
					 },
					 html:value
				 
				 }).inject(this.getParent());
				 if(self.options.auto)
				 	return;
				 
				 document.id('submitBtn').focus();
			}
			
			
		})
	},
	creaInput:function (){
		for(idgen=1;document.id('filetoUp'+idgen);idgen++);
		var input=new Element('label',{
			styles:{
				position:'relative',
				display:'block',
				width:295,
				height:30,
				overflow:'hidden',
				background:this.inputBg
				
				
			},
			html:'<span id="_brwser'+idgen+'" style="position:absolute;height:100%;top:0;left:0;font-size:10px;max-width:78px ;width:auto !important;width:78px;word-wrap:break-word;padding:10px 10px;font-weight:bold;color:'+this.browseTxtColor+';background:'+this.browseBg+'">'
			+this.browseTxt+'</span>'
			+'<input type="file" id="filetoUp'+idgen+'"  class="file" name="filetoUp'+idgen+'" value="" style="cursor:pointer;position:relative;display:block;left:-149px;opacity:0;filter:alpha(opacity=0)" />'
			
			
			
		}).inject('submitBtn','before');
		
		document.id('_brwser'+idgen).setStyles(this.submitStyles||{});
		
		this.infoFiles();
		
		if(idgen==1)
			return;
	
		var delete_btn=new Element('span',{
			styles:{
				//display:'block',
				'font-size':'10px',
				position:'absolute',
				top:0,
				cursor:'pointer',
				right:0
			},
			html:'<img src="'+this.options.delIcon+'"/>',
			events:{
				'click':function (){
					if(fileInfo=window.document.getElement('span[rel="'+input.getElement('input[type="file"').id+'"]')){ fileInfo.destroy(); }
					input.highlight('#f00');
					
					new Fx.Morph(input).start({
						opacity:[1,0]
					}).chain(function(){input.destroy();this.destroy();}.bind(this));
				}
			}
		});
			delete_btn.inject(input,'before');
		
	},
	
	creaForm:function ()
	{
		
			var html='<div id="wrapperOfForm" style="position:relative">'
			+'<form target="tempFrametoUpload" enctype="multipart/form-data" id="tempFormtoUpload" method="post" action="'+this.srv+'">'
			+'<input type="hidden" name="fpath" value="'+this.filePath+'" />'
			+'<input type="submit" id="submitBtn" name="submitBtn" class="sbtBtn" style="margin:6px 0;padding:3px 8px;font-size:11px" value="'+this.submitValue+'" />'
			+'</form>';
			+'</div>';
			
			this.el.set('html',html);
			document.id('submitBtn').setStyles(this.submitStyles);
			if(this.options.auto)
				document.id('submitBtn').setStyle('visibility','hidden');
			
			document.id('tempFormtoUpload').addEvent('submit',function(){
				this.el.fireEvent('onRequest');
				this.wrapper=document.id('wrapperOfForm');
				this.current_h=this.wrapper.getHeight();
				this.morpha=new Fx.Morph(this.wrapper);
				document.id('theSpinner').setStyles({'visibility':'visible','opacity':'1'});
				this.wrapper.setStyles({
					height:45,
					'opacity':'0',
					'visibility':'hidden'
				})
				
			}.bind(this))
								
	},
	
	creaIframe:function (){
		var self=this;
		if(document.id('tempFrametoUpload'))
			return;
			
		new IFrame({
			name:'tempFrametoUpload',
			src:'#',
			styles:{
				border:'none',
				display:(!self.options.debugMode)?'none':'block',
				width:(!self.options.debugMode)?0:'100%',
				height:(!self.options.debugMode)?0:'200px'
				
			},
			events:{
				load:function (){
					if(this.getResponse().trim()!=''){
						this.el.fireEvent('onComplete',[new Hash(JSON.decode(this.getResponse(), true) || {})]);
						$$('.wrapFileSelected').set('html','');
						document.id('tempFormtoUpload').reset();
					}
					document.id('theSpinner').setStyles({'visibility':'hidden','opacity':'0'});
						document.id('wrapperOfForm').setStyles({
							height:20,
							'opacity':'1',
							'visibility':'visible',
							height:'auto'
						});
				}.bind(this)
			}
		}).inject(document.body);
	},
	
	creaLoader:function(){
		new Element('div',{
			styles:{
				position:'absolute',
				//'z-index':1,
				opacity:0,
				'filter':'alpha(opacity=0)'
			},
			id:'theSpinner',
			html:'<center><span style="font-size:10px">'+this.loaderTxt+'</span></center><img src="'+this.loader+'"/>'
		}).inject(this.el,'top')
	},
	
	getResponse:function (){
		return frames.tempFrametoUpload.document.body.innerHTML
	}
})
