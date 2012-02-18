/*###########################################################################
		Funcion para mostrar un mensaje en top respetando el scroll
#############################################################################*/
var _timer;
var _scroll;
function showMsg(msg,_type,time){
	$clear(_timer);
	var _id='',_styles={},msgBox,_events={};
			switch(_type){
				case 'error':
					_id='msgError';break;
				case 'warning':
					_id='msgWarning';break;
				case 'confirm':
					_id='msgConfirm';break;
				default:
					_id='msgInfo';break;
			}
			
			
			_styles={
				//'width':990,
				'width':'100%',
				'padding':'15px 10px 10px 10px',
				'display':'none',
				'visibility':'hidden',
				'border-top':'none',
				'text-align':'center',
				'z-index':'9999'
			}
			
	if(!$$('.message_box')[0])
	{
		msgBox=new Element('div',{
			styles:_styles,
			'id':_id,
			'class':'message message_box',
		})
		.inject(document.body);
	}else{
		msgBox=$$('.message_box')[0];
	}
	
	msgBox.setProperty('id',_id)
	.set('html',msg)
	.setStyle('display','block')
	.fade(0,1);
	if(Browser.ie){
		msgBox.position({
			position:'leftTop',
			relFixedPosition:true,
			ignoreMargins:true,
			ignoreScroll:true
			//offset:{x:((document.id(document.body).getWidth()-990)/2)-10,y:-8}
		});
	}else{
		msgBox.setStyles({
			'position':'fixed',
			'top':0
		});
	}
	
	
	_timer=(function(){
				msgBox.get('tween').start('opacity',1,0).chain(function(){msgBox.setStyle('display','none')});
			}).delay(time || 5000);
			
	if(!_scroll&&Browser.ie){
		_scroll=true;
		window.addEvent('scroll',function(){
			msgBox.position({
				position:'leftTop',
				relFixedPosition:true,
				ignoreMargins:true,
				ignoreScroll:true
				//offset:{x:((document.id(document.body).getWidth()-990)/2)-10,y:-5}
			})
		})
	}
	
}

/*###########################################################################
				STYLE MENSAJES (ERROR, CONFIRM Y NOTIFICACION)
#############################################################################

#msgConfirm {
    background-color: #D4F28C;
    background-image: url("img/icoconfirm.png");
    border-color: #B2C722;
}

#msgWarning {
    background-color: #FFF89D;
    background-image: url("img/icowarning.png");
    border-color: #9C894A;
    margin:5px
}

#msgInfo {
    background-color: #CDE5EC;
    background-image: url("img/icoinfo.png");
    border-color: #408ABD;
}
#msgError {
    background-color: #FDA790;
    background-image: url("img/icoerror.png");
    border-color: #BC3826;
}
.message {
    background-position: 10px 10px;
    background-repeat: no-repeat;
    border-style: solid;
    border-width: 1px;
    font-size: 12px;
    margin-bottom: 10px;
    padding: 10px 15px 10px 40px;
}

#############################################################################*/
