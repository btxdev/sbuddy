$(document).ready(function(){
  $('.preloader-block-ico > svg').css({
    'opacity':'1'
  })
});

window.onload = function(){
  setTimeout(function(){
    $('#g-block1-lvl4-elem1').on('animationiteration', function(){
      $('#g-block1-lvl4-elem1').css('animation','initial');
      $('#g-block1-lvl2').css('animation','initial');
      $('#g-block2-lvl4-elem1').css('animation','initial');
      $('#g-block2-lvl2').css({
        'animation':'initial'
      });
      setTimeout(function(){
        $('#g-block2-lvl2').css({
          'transform':'rotate3d(1, 0, 0, 0deg)'
        });
        setTimeout(function(){
          $('#g-block2-lvl4-elem1').css({
            'transform':'rotate3d(1, 0, 0, 0deg)'
          });
          setTimeout(function(){
            $('#g-block1-lvl4-elem1').css({
              'transform':'rotate3d(1, 0, 0, 0deg)'
            });
            setTimeout(function(){
              $('.preloader-block').css({
                'opacity':'0'
              })
              $('.preloader-block-ico').css({
                'transform-origin':'center center',
                'transform':'scale(18)'
              })
              setTimeout(function(){
                $('.preloader').css('opacity','0')
                setTimeout(function(){
                  if($.cookie('style_finder') == 'block'){
                    change_style_finder('block')
                  } else{
                    change_style_finder('line')
                  }
                }, 350)
                setTimeout(function(){
                  $('.preloader').css('display','none')
                },500)
              }, 150)
            }, 500)
          }, 307)
        }, 307)
      }, 1)

    })
    $('#g-block1-lvl4-elem1').on('animationstart', function(){
      $('#g-block1-lvl4-elem1').css('animation','initial');
      $('#g-block1-lvl2').css('animation','initial');
      $('#g-block2-lvl4-elem1').css('animation','initial');
      $('#g-block2-lvl2').css({
        'animation':'initial'
      });
      setTimeout(function(){
        $('#g-block2-lvl2').css({
          'transform':'rotate3d(1, 0, 0, 0deg)'
        });
        setTimeout(function(){
          $('#g-block2-lvl4-elem1').css({
            'transform':'rotate3d(1, 0, 0, 0deg)'
          });
          setTimeout(function(){
            $('#g-block1-lvl4-elem1').css({
              'transform':'rotate3d(1, 0, 0, 0deg)'
            });
            setTimeout(function(){
              $('.preloader-block').css({
                'opacity':'0'
              })
              $('.preloader-block-ico').css({
                'transform-origin':'center center',
                'transform':'scale(18)'
              })
              setTimeout(function(){
                $('.preloader').css('opacity','0')
                setTimeout(function(){
                  if($.cookie('style_finder') == 'block'){
                    change_style_finder('block')
                  } else{
                    change_style_finder('line')
                  }
                }, 350)
                setTimeout(function(){
                  $('.preloader').css('display','none')
                },500)
              }, 150)
            }, 500)
          }, 307)
        }, 307)
      }, 1)

    })
  }, 100)
}
