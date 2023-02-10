<style>
  html{
  }

  .R0k4bRWxY25Wt1B{
    width: 600px;
    height: 535px;
    overflow: hidden;
    background-color: #546ce6;
    position: relative;
    background-position: bottom;
    background-image: url('media/external_module/theme/undraw.svg');
    background-size: cover;
    background-repeat: no-repeat;
  }

  .R0k4bRWxY25Wt1B-text{
    position: absolute;
    left: 0;
    right: 0;
    top: 42px;
    margin: auto;
    width: 70%;
    height: 190px;
    text-align: center;
    font-size: 20px;
    font-family: pfl;
    color: var(--colorI);
  }
  .R0k4bRWxY25Wt1B-text-title{
    font-family: pfb;
    font-size: 33px;
  }
  .R0k4bRWxY25Wt1B-text-titleSmall2{
    margin-top: 10px;
    font-family: pfl;
    font-size: 18px;
  }
  .R0k4bRWxY25Wt1B-light{
    position: absolute;
    height: 75px;
    width: 75px;
    right: -219px;
    left: 0;
    bottom: 0;
    top: 0;
    margin: auto;
    transform: translate(0, 1.2vh);
    border-radius: 640px;
    opacity: 0;
    transition: 0.35s all ease-in-out;
    background: radial-gradient(ellipse at center, #ffdf03a6 0%, #f0b81829 50%, #f6cc0e00 70%, #fff20000 100%);
  }
  .R0k4bRWxY25Wt1B-text-btn{
    display: inline-block;
    padding: 10px 25px;
    font-family: pfb;
    border-radius: 7px;
    cursor: pointer;
    transition: 0.15s all;
    z-index: 10;
    bottom: 45px;
    position: absolute;
    user-select: none;
    left: 0;
    right: 0;
    margin: auto;
    color: #fff;
    width: 107px;
    text-align: center;
    /* backdrop-filter: saturate(50%) blur(4px); */
    border: 2px solid #ffffff80;;
    /* background-image: url('media/img/whitenoise.png'); */
  }
  .R0k4bRWxY25Wt1B-text-btn:hover{
    border: 2px solid #ffffff;
  }
  .R0k4bRWxY25Wt1B-text-btn:active{
    transform: scale(0.98);
  }

  @media only screen and (max-width: 600px)  {
    .R0k4bRWxY25Wt1B{
      width: 100%;
    }
  }

  @media only screen and (max-width: 836px)  {
    .R0k4bRWxY25Wt1B{
      width: 100%;
    }
  }

</style>
<script type="text/javascript">
  function themeDarkAuto(){
    GlobalTheme = 'black';
    theme_chart = "dark";
    updateChartsNew('dark');
    $('html').get(0).style.setProperty('--color','#fff')
    $('html').get(0).style.setProperty('--colorI','#fff')
    $('html').get(0).style.setProperty('--dark','#121212')
    $('html').get(0).style.setProperty('--menu','#121212')
    $('html').get(0).style.setProperty('--menu-profile','#2b2b2b')
    $('html').get(0).style.setProperty('--main-bg-search','#2b2b2b')
    $('html').get(0).style.setProperty('--main-bg','#1a1a1a')
    $('html').get(0).style.setProperty('--white','#222')
    $('html').get(0).style.setProperty('--border-color','#353535')
    $('html').get(0).style.setProperty('--main-bg-2','#1e1e2d')
    $('html').get(0).style.setProperty('--border-bg','#121212')
    $('html').get(0).style.setProperty('--shadow-name','rgba(43, 43, 43, 1)')
    $('html').get(0).style.setProperty('--menu-status','#434343')
    $('html').get(0).style.setProperty('--bg-color-btn','#333')
    $('html').get(0).style.setProperty('--color-btn-hover','#fff')
    $('html').get(0).style.setProperty('--bg-color-scrollbar','#2b2b2b')
    $('html').get(0).style.setProperty('--noise-bg',"url('../media/img/whitenoise.png')")
    opacity_save_settings();
    $.cookie('theme', GlobalTheme, {expires: 99999});
    $('.R0k4bRWxY25Wt1B-light').css('opacity','1');
    close_window_themeAuto();
    setTimeout(function(){
      close_window('#themeDarkMobile-close');
    }, 700)
  }
  function close_window_themeAuto(){
    $.cookie('theme_mobile_app', 'false', {expires: 99999});
  }
</script>
<?php

  $newsYearHref = '';

  if(!$newYearPanel){
    $newsYearHref = '$(location).attr('."'".'href'."'".','."'".'#'."'".');';
  } else{
    $newsYearHref = 'open_window('."'".'#settings'."'".')';
  }

?>
<div class='window-zindex' id='themeDarkMobile' style="display: block; opacity: 1;">
  <div class='window-block'>
    <div class='to_close icon-close' id='themeDarkMobile-close' style='background-color: transparent; color: var(--colorI);' title='Закрыть' onclick="close_window(this); close_window_themeAuto();"></div>
    <div class='R0k4bRWxY25Wt1B'>
      <div class="R0k4bRWxY25Wt1B-light">

      </div>
      <div class='R0k4bRWxY25Wt1B-text'>
        <div class='R0k4bRWxY25Wt1B-text-titleSmall'>Темная тема</div>
        <div class='R0k4bRWxY25Wt1B-text-title'>Освежи свой взгляд на мир!</div>
        <div class='R0k4bRWxY25Wt1B-text-titleSmall2'>Смени оформление под цвет своего смартфона</div>
      </div>
      <div class='R0k4bRWxY25Wt1B-text-btn' onclick="themeDarkAuto();">Сменить тему</div>

    </div>
  </div>
</div>
