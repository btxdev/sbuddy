var blockActiveDrag = true;


$(document).ready(function(){

	document.body.addEventListener('dragover', drag_over, false);
	document.body.addEventListener('dragleave', drag_leave, false);
	document.body.addEventListener('dragend', function(){dragAndDropGlobalHidden()}, false);

  // функция draganddrop для блока .drive-draganddrop (start)
  var draganddropBlock1 = '.drive-draganddrop';

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


});

// функция отмены всех DragAndDrop
function dragAndDropGlobalHidden(){

  $('.drive-draganddrop').css({
    'visibility':'hidden',
    'opacity':'0'
  });

}

// функция включения всех DragAndDrop
function dragAndDropGlobalShow(){

  $('.drive-draganddrop').css({
    'visibility':'visible',
    'opacity':'1'
  });

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

  if(block == '.drive-draganddrop'){
    $('.drive-draganddrop > .drive-draganddrop-ab').css({
      'background-color':'#fdd83557',
      'border':'3px solid #303036'
    })
  }

}

// функция скрытия для блока
function drag_leave_elem(e, block){
  e.stopPropagation();
  e.preventDefault();
  blockActiveDrag = true;

  if(block == '.drive-draganddrop'){
    $('.drive-draganddrop > .drive-draganddrop-ab').css({
      'background-color':'transparent',
      'border':'3px dashed #303036'
    })
  }


}

// функция загрузки для блока
function drag_drop_elem(e, block){
  blockActiveDrag = true;
  e.stopPropagation();
  e.preventDefault();

  if(block == '.drive-draganddrop'){
    Finder.upload(e.dataTransfer.files);
  }

  dragAndDropGlobalHidden();
}
