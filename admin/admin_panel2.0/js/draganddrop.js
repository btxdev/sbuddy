/*
 *  Swiftly Admin Panel v1.12 alpha
 *  (c) 2019-2020 INSOweb  <http://insoweb.ru>
 *  All rights reserved.
 */

var blockActiveDrag = true;


$(document).ready(function(){

	document.body.addEventListener('dragover', drag_over, false);
	document.body.addEventListener('dragleave', drag_leave, false);
	document.body.addEventListener('dragend', function(){dragAndDropGlobalHidden()}, false);

  // функция draganddrop для блока .slip-contacts (start)
  var draganddropBlock1 = '.slip-contacts';

  $(draganddropBlock1).on('dragover', function(){
    drag_over_elem(event, draganddropBlock1)
  });
  $(draganddropBlock1).on('dragleave', function(){
    drag_leave_elem(event, draganddropBlock1)
  });
  $(draganddropBlock1).on('drop', function(){
    drag_drop_elem(event, draganddropBlock1)
  });
  // функция draganddrop для блока .slip-contacts (end)

  // функция draganddrop для блока .finder-dragAndDrop (start)
  var draganddropBlock2 = '.finder-dragAndDrop';

  $(draganddropBlock2).on('dragover', function(){
    drag_over_elem(event, draganddropBlock2)
  });
  $(draganddropBlock2).on('dragleave', function(){
    drag_leave_elem(event, draganddropBlock2)
  });
  $(draganddropBlock2).on('drop', function(){
    drag_drop_elem(event, draganddropBlock2)
  });
  // функция draganddrop для блока .finder-dragAndDrop (end)

  // функция draganddrop для блока .window-block-drag_and_drop (start)
  var draganddropBlock3 = '.window-block-drag_and_drop';

  $(draganddropBlock3).on('dragover', function(){
    drag_over_elem(event, draganddropBlock3)
  });
  $(draganddropBlock3).on('dragleave', function(){
    drag_leave_elem(event, draganddropBlock3)
  });
  $(draganddropBlock3).on('drop', function(){
    drag_drop_elem(event, draganddropBlock3)
  });
  // функция draganddrop для блока .window-block-drag_and_drop (end)

	// функция draganddrop для блока .panel-conteiner-news-draganddrop-elem-border2 (start)
	var draganddropBlock4 = '.panel-conteiner-news-draganddrop-elem-border2';

	$(draganddropBlock4).on('dragover', function(){
		drag_over_elem(event, draganddropBlock4)
	});
	$(draganddropBlock4).on('dragleave', function(){
		drag_leave_elem(event, draganddropBlock4)
	});
	$(draganddropBlock4).on('drop', function(){
		drag_drop_elem(event, draganddropBlock4)
	});
	// функция draganddrop для блока .panel-conteiner-news-draganddrop-elem-border2 (end)

	// функция draganddrop для блока .panel-conteiner-news-draganddrop-elem-border (start)
	var draganddropBlock5 = '.panel-conteiner-news-draganddrop-elem-border';

	$(draganddropBlock5).on('dragover', function(){
		drag_over_elem(event, draganddropBlock5)
	});
	$(draganddropBlock5).on('dragleave', function(){
		drag_leave_elem(event, draganddropBlock5)
	});
	$(draganddropBlock5).on('drop', function(){
		drag_drop_elem(event, draganddropBlock5)
	});
	// функция draganddrop для блока .panel-conteiner-news-draganddrop-elem-border (end)

});

// функция отмены всех DragAndDrop
function dragAndDropGlobalHidden(){

  // карточка в контактах
  $('.slip-contacts-dragAndDrop').css({
    'visibility':'hidden',
    'opacity':'0'
  });
  $('.slip-contacts').css({
    'border':''
  });
  $('.slip-contacts-file-ico-upload').css({
    'visibility':'visible',
    'opacity':'1'
  });
  $('.slip-contacts-file-name-upload').css({
    'visibility':'visible',
    'opacity':'1'
  });
	$('.slip-contacts-file').css({
    'visibility':'visible',
    'opacity':'1'
  });

  // finder draganddrop
  $('.finder-dragAndDrop').css({
    'visibility':'hidden',
    'opacity':'0'
  })

  // загрузка фотографии в профиль
  $('.window-block-drag_and_drop').css({
    'visibility':'hidden',
    'opacity':'0'
  })

	// загрузка в новости
  $('.panel-conteiner-news-draganddrop').css({
    'visibility':'hidden',
    'opacity':'0'
  })
}

// функция включения всех DragAndDrop
function dragAndDropGlobalShow(){

  // карточка в контактах
  $('.slip-contacts-dragAndDrop').css({
    'visibility':'visible',
    'opacity':'1'
  });
	$('.slip-contacts-file').css({
    'visibility':'hidden',
    'opacity':'0'
  });
  $('.slip-contacts-file-ico-upload').css({
    'visibility':'hidden',
    'opacity':'0'
  });
  $('.slip-contacts-file-name-upload').css({
    'visibility':'hidden',
    'opacity':'0'
  });
  $('.slip-contacts').css({
    'border':'2px dashed #5d78ff'
  })

  // finder draganddrop
  $('.finder-dragAndDrop').css({
    'visibility':'visible',
    'opacity':'1'
  })

  // загрузка фотографии в профиль
  $('.window-block-drag_and_drop').css({
    'visibility':'visible',
    'opacity':'1'
  })

	// загрузка в новости
  $('.panel-conteiner-news-draganddrop').css({
    'visibility':'visible',
    'opacity':'1'
  })
}

var drag_over = function(e){
  e.stopPropagation();
  e.preventDefault();
  if(blockActiveDrag){
    e.stopPropagation();
    e.preventDefault();
    dragAndDropGlobalShow();
  }
}

var drag_leave = function(e){
  e.stopPropagation();
  e.preventDefault();
  if(blockActiveDrag){
    if(e.x == 0 && e.y == 0){
      dragAndDropGlobalHidden();
    }
  }
}

// функция отображения для блока
function drag_over_elem(e, block){
  e.stopPropagation();
  e.preventDefault();
  blockActiveDrag = false;

  // функция отображения для блока .slip-contacts
  if(block == '.slip-contacts'){
    $(block).css({
      'border':'2px solid #5d78ff'
    })
  }

  // функция отображения для блока .finder-dragAndDrop
  if(block == '.finder-dragAndDrop'){
    $(block + ' > div').css({
      'border':'2px solid #5d78ff'
    })
  }

  // функция отображения для блока фотографии пользователя
  if(block == '.window-block-drag_and_drop'){
    $(block + ' > .window-block-drag_and_drop-border').css({
      'border':'2px solid #fff'
    })
  }

	if(block == '.panel-conteiner-news-draganddrop-elem-border2'){
		$(block).css({
      'border':'2px solid #5d78ff',
			'background-color':'#5d78ff40'
    })
	}

	if(block == '.panel-conteiner-news-draganddrop-elem-border'){
		$(block).css({
      'border':'2px solid #5d78ff',
			'background-color':'#5d78ff40'
    })
	}

}

// функция скрытия для блока
function drag_leave_elem(e, block){
  e.stopPropagation();
  e.preventDefault();
  blockActiveDrag = true;

  // функция скрытия для блока .slip-contacts
  if(block == '.slip-contacts'){
    $(block).css({
      'border':'2px dashed #5d78ff'
    })
  }

  // функция скрытия для блока .finder-dragAndDrop
  if(block == '.finder-dragAndDrop'){
    $(block + ' > div').css({
      'border':'2px dashed #5d78ff'
    })
  }

  // функция скрытия для блока фотографии пользователя
  if(block == '.window-block-drag_and_drop'){
    $(block + ' > .window-block-drag_and_drop-border').css({
      'border':'2px dashed #fff'
    })
  }

	if(block == '.panel-conteiner-news-draganddrop-elem-border2'){
		$(block).css({
      'border':'2px dashed #5d78ff',
			'background-color':'transparent'
    })
	}

	if(block == '.panel-conteiner-news-draganddrop-elem-border'){
		$(block).css({
      'border':'2px dashed #5d78ff',
			'background-color':'transparent'
    })
	}

}

// функция загрузки для блока
function drag_drop_elem(e, block){
  blockActiveDrag = true;
  e.stopPropagation();
  e.preventDefault();

  // функция загрузки для блока карточки в контактах
  if(block == '.slip-contacts'){
    contactsUploadCard(true, e.dataTransfer.files[0]);
  }

  // функция загрузки для блока finder
  if(block == '.finder-dragAndDrop'){
    dragAndDrop(e);
  }

  // функция загрузки для блока фотографии пользователя
  if(block == '.window-block-drag_and_drop'){
    uploadProfileIcon(true, e.dataTransfer.files);
  }

	// функция загрузки для блока другое новости
	if(block == '.panel-conteiner-news-draganddrop-elem-border'){
		upload_news_file(true, e.dataTransfer.files, 'other');
		setTimeout(function(){
			$(block).css({
	      'border':'2px dashed #5d78ff',
				'background-color':'transparent'
	    });
		}, 250)
  }

	// функция загрузки для блока фотографии новости
  if(block == '.panel-conteiner-news-draganddrop-elem-border2'){
		upload_news_file(true, e.dataTransfer.files, 'image');
		setTimeout(function(){
			$(block).css({
	      'border':'2px dashed #5d78ff',
				'background-color':'transparent'
	    });
		}, 250)
  }

  dragAndDropGlobalHidden();
}
